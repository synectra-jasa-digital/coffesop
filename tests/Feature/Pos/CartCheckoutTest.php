<?php

namespace Tests\Feature\Pos;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected User $cashier;
    protected Table $table;
    protected Product $product;
    protected Ingredient $ingredient;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        Role::firstOrCreate(['name' => 'Kasir']);
        Role::firstOrCreate(['name' => 'Barista/Gudang']);

        $this->cashier = User::factory()->create();
        $this->cashier->assignRole('Kasir');

        $category = Category::create([
            'name' => 'Kopi',
            'description' => 'Minuman kopi',
        ]);

        $this->table = Table::create([
            'number' => 'Meja 01',
            'status' => 'available',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Es Kopi',
            'description' => 'Tes',
            'price' => 25000,
            'is_active' => true,
        ]);

        $this->ingredient = Ingredient::create([
            'name' => 'Biji Kopi',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 50, // kurang untuk 1x jual
        ]);

        $recipe = Recipe::create([
            'product_id' => $this->product->id,
            'name' => 'Resep '.$this->product->name,
        ]);

        RecipeIngredient::create([
            'recipe_id' => $recipe->id,
            'ingredient_id' => $this->ingredient->id,
            'quantity' => 100, // 1x jual butuh 100g, stok hanya 50g
        ]);

        Setting::updateOrCreate(['key' => 'tax_enabled'], ['value' => 'true', 'type' => 'boolean', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'tax_percentage'], ['value' => '11', 'type' => 'number', 'group' => 'tax']);
    }

    /**
     * Helper: create an open shift for the cashier.
     */
    protected function createOpenShift(): Shift
    {
        return Shift::create([
            'user_id' => $this->cashier->id,
            'start_time' => Carbon::now(),
            'starting_cash' => 0,
            'status' => 'open',
        ]);
    }

    /**
     * Helper: add a cash payment via the component.
     */
    protected function addCashPayment($component, float $amount)
    {
        return $component
            ->call('openPaymentModal')
            ->assertSet('showPaymentModal', true)
            ->set('paymentMethod', 'cash')
            ->set('paymentAmount', $amount)
            ->call('addPayment');
    }

    public function test_checkout_is_rejected_when_stock_is_insufficient_and_no_partial_state_remains(): void
    {
        $this->actingAs($this->cashier);
        $this->createOpenShift();

        $stockBefore = (float) $this->ingredient->fresh()->current_stock;

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        $this->addCashPayment($component, 30000);

        $component->call('processCheckout');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertDatabaseCount('payments', 0);
        $this->assertDatabaseCount('stock_movements', 0);
        $this->assertSame(
            $stockBefore,
            (float) $this->ingredient->fresh()->current_stock,
            'Stok harus tetap sama ketika checkout gagal karena stok kurang.'
        );
    }

    public function test_checkout_succeeds_and_reads_tax_from_settings(): void
    {
        $this->actingAs($this->cashier);

        Setting::updateOrCreate(['key' => 'tax_enabled'], ['value' => 'true', 'type' => 'boolean', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'tax_percentage'], ['value' => '5', 'type' => 'number', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'service_charge_enabled'], ['value' => 'false', 'type' => 'boolean', 'group' => 'tax']);

        $this->ingredient->update(['current_stock' => 1000]);
        $this->createOpenShift();

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        $component->assertSet('subtotal', 25000)
            ->assertSet('taxAmount', 1250)
            ->assertSet('serviceChargeAmount', 0)
            ->assertSet('total', 26250);

        $this->addCashPayment($component, 26250);
        $component->call('processCheckout');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseCount('stock_movements', 1);

        $this->assertSame(
            900.0,
            (float) $this->ingredient->fresh()->current_stock,
            'Stok harus berkurang 100g setelah checkout baru.'
        );

        $order = \App\Models\Order::first();
        $this->assertSame(26250.0, (float) $order->total);
        $this->assertSame(1250.0, (float) $order->tax_amount);
        $this->assertSame(0.0, (float) $order->service_charge_amount);
    }

    public function test_service_charge_is_applied_when_enabled_in_settings(): void
    {
        $this->actingAs($this->cashier);

        Setting::updateOrCreate(['key' => 'tax_enabled'], ['value' => 'true', 'type' => 'boolean', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'tax_percentage'], ['value' => '11', 'type' => 'number', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'service_charge_enabled'], ['value' => 'true', 'type' => 'boolean', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'service_charge_percentage'], ['value' => '5', 'type' => 'number', 'group' => 'tax']);

        $this->ingredient->update(['current_stock' => 1000]);
        $this->createOpenShift();

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        // subtotal = 25000, tax 11% = 2750, service charge 5% = 1250, total = 29000
        $component->assertSet('subtotal', 25000)
            ->assertSet('taxAmount', 2750)
            ->assertSet('serviceChargeAmount', 1250)
            ->assertSet('total', 29000);

        $this->addCashPayment($component, 29000);
        $component->call('processCheckout');

        $order = \App\Models\Order::first();
        $this->assertSame(29000.0, (float) $order->total);
        $this->assertSame(2750.0, (float) $order->tax_amount);
        $this->assertSame(1250.0, (float) $order->service_charge_amount);
    }

    public function test_split_payment_allows_multiple_methods(): void
    {
        $this->actingAs($this->cashier);

        Setting::updateOrCreate(['key' => 'tax_enabled'], ['value' => 'true', 'type' => 'boolean', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'tax_percentage'], ['value' => '11', 'type' => 'number', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'service_charge_enabled'], ['value' => 'false', 'type' => 'boolean', 'group' => 'tax']);

        $this->ingredient->update(['current_stock' => 1000]);
        $this->createOpenShift();

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        // Total: subtotal 25000 + tax 11% = 2750 = 27750
        $component->assertSet('total', 27750);

        // Split: 10000 cash + 17750 via QRIS
        $component->call('openPaymentModal')->assertSet('showPaymentModal', true);
        $component->set('paymentMethod', 'cash')->set('paymentAmount', 10000)->call('addPayment');
        $component->set('paymentMethod', 'qris')->set('paymentAmount', 17750)->call('addPayment');

        // Verify payments array has both entries.
        $component->assertSet('payments', [
            ['method' => 'cash', 'amount' => 10000.0],
            ['method' => 'qris', 'amount' => 17750.0],
        ]);

        $component->call('processCheckout');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('payments', 2);

        $order = \App\Models\Order::first();
        $cashPayment = \App\Models\Payment::where('order_id', $order->id)->where('payment_method', 'cash')->first();
        $qrisPayment = \App\Models\Payment::where('order_id', $order->id)->where('payment_method', 'qris')->first();

        $this->assertNotNull($cashPayment);
        $this->assertNotNull($qrisPayment);
        $this->assertSame(10000.0, (float) $cashPayment->amount);
        $this->assertSame(17750.0, (float) $qrisPayment->amount);
    }

    public function test_checkout_rejects_when_payment_incomplete(): void
    {
        $this->actingAs($this->cashier);

        Setting::updateOrCreate(['key' => 'tax_enabled'], ['value' => 'true', 'type' => 'boolean', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'tax_percentage'], ['value' => '0', 'type' => 'number', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'service_charge_enabled'], ['value' => 'false', 'type' => 'boolean', 'group' => 'tax']);

        $this->ingredient->update(['current_stock' => 1000]);
        $this->createOpenShift();

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        // Total is 25000 but only pay 10000 cash.
        $component->set('paymentMethod', 'cash')->set('paymentAmount', 10000)->call('addPayment');
        $component->call('processCheckout');

        // Should NOT create an order.
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_seeder_creates_user_with_password_using_settings_value(): void
    {
        // Kita akan melakukan config binding alih-alih bergantung penuh pada .env/putenv di seeder
        $expected = Str::password(18);
        config(['app.seed_admin_password' => $expected]);
        $_ENV['SEED_ADMIN_PASSWORD'] = $expected;

        $this->artisan('db:seed', ['--class' => \Database\Seeders\RolesAndPermissionsSeeder::class])
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@coffeeshop.com',
        ]);

        $admin = User::where('email', 'admin@coffeeshop.com')->first();
        $this->assertNotNull($admin);
        $this->assertTrue(Hash::check($expected, $admin->password));
        $this->assertFalse(Hash::check('password123', $admin->password));

        putenv('SEED_ADMIN_PASSWORD');
    }
}