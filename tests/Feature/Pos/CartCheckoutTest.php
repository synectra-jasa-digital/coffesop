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

        // Seed roles required by the cart flow and policies.
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

    public function test_checkout_is_rejected_when_stock_is_insufficient_and_no_partial_state_remains(): void
    {
        $this->actingAs($this->cashier);

        Shift::create([
            'user_id' => $this->cashier->id,
            'start_time' => Carbon::now(),
            'starting_cash' => 0,
            'status' => 'open',
        ]);

        $stockBefore = (float) $this->ingredient->fresh()->current_stock;

        Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ])
            ->call('processCheckout');

        // No order should be persisted, and stock must not have been mutated.
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

        // Set the tax to 5% in settings and ensure the cart honours it.
        Setting::updateOrCreate(['key' => 'tax_enabled'], ['value' => 'true', 'type' => 'boolean', 'group' => 'tax']);
        Setting::updateOrCreate(['key' => 'tax_percentage'], ['value' => '5', 'type' => 'number', 'group' => 'tax']);

        $this->ingredient->update(['current_stock' => 1000]);

        Shift::create([
            'user_id' => $this->cashier->id,
            'start_time' => Carbon::now(),
            'starting_cash' => 0,
            'status' => 'open',
        ]);

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        // subtotal = 25000, tax 5% = 1250, total = 26250
        $component->assertSet('subtotal', 25000)
            ->assertSet('taxAmount', 1250)
            ->assertSet('total', 26250);

        $component->call('processCheckout');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseCount('stock_movements', 1);

        $this->assertSame(
            900.0,
            (float) $this->ingredient->fresh()->current_stock,
            'Stok harus berkurang 100g setelah checkout berhasil.'
        );

        $order = \App\Models\Order::first();
        $this->assertSame(26250.0, (float) $order->total);
        $this->assertSame(1250.0, (float) $order->tax_amount);
    }

    public function test_seeder_creates_user_with_password_using_settings_value(): void
    {
        $expected = Str::password(18);
        putenv('SEED_ADMIN_PASSWORD='.$expected);

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