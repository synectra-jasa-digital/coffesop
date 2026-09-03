<?php

namespace Tests\Feature\Pos;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ActivityLog;
use App\Models\StockMovement;
use App\Models\Shift;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VoidTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected User $kasir;
    protected User $manager;
    protected Order $order;
    protected Ingredient $ingredient;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        Role::firstOrCreate(['name' => 'Kasir']);

        $this->kasir = User::factory()->create();
        $this->kasir->assignRole('Kasir');

        $this->manager = User::factory()->create();
        $this->manager->assignRole('Manager/Supervisor');

        $category = Category::create(['name' => 'Kopi', 'description' => 'Minuman kopi']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cafe Latte',
            'description' => 'Espresso dengan steamed milk',
            'price' => 28000,
            'is_active' => true,
        ]);

        $this->ingredient = Ingredient::create([
            'name' => 'Biji Kopi',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 1000,
        ]);

        $recipe = Recipe::create([
            'product_id' => $product->id,
            'name' => 'Resep '.$product->name,
        ]);

        RecipeIngredient::create([
            'recipe_id' => $recipe->id,
            'ingredient_id' => $this->ingredient->id,
            'quantity' => 18,
        ]);

        $table = Table::create(['number' => 'Meja 01', 'status' => 'available']);
        $shift = Shift::create([
            'user_id' => $this->kasir->id,
            'start_time' => Carbon::now(),
            'starting_cash' => 0,
            'status' => 'open',
        ]);

        $this->order = Order::create([
            'order_number' => 'ORD-TEST01',
            'user_id' => $this->kasir->id,
            'shift_id' => $shift->id,
            'table_id' => $table->id,
            'type' => 'dine-in',
            'status' => 'completed',
            'payment_status' => 'paid',
            'subtotal' => 28000,
            'tax_amount' => 3080,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => 31080,
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 28000,
            'subtotal' => 28000,
            'notes' => '',
            'status' => 'served',
        ]);

        Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => 'cash',
            'amount' => 31080,
            'status' => 'success',
        ]);

        // Simulate the stock deduction that would have happened at checkout.
        $this->ingredient->decrement('current_stock', 18);
    }

    public function test_void_requires_reason_and_manager_code_for_cashier(): void
    {
        $this->actingAs($this->kasir);

        // Missing reason.
        Livewire::test(\App\Livewire\Pos\History::class)
            ->call('openVoid', $this->order->id)
            ->set('voidNotes', '')
            ->call('processVoid')
            ->assertHasErrors(['voidNotes']);

        // Short reason (min 5 chars).
        Livewire::test(\App\Livewire\Pos\History::class)
            ->call('openVoid', $this->order->id)
            ->set('voidNotes', 'AB')
            ->call('processVoid')
            ->assertHasErrors(['voidNotes']);

        // Reason OK but no manager code.
        Livewire::test(\App\Livewire\Pos\History::class)
            ->call('openVoid', $this->order->id)
            ->set('voidNotes', 'Produk salah pesan')
            ->call('processVoid');

        $this->order->refresh();
        $this->assertSame('completed', $this->order->status);
    }

    public function test_cashier_can_void_with_valid_manager_code(): void
    {
        $this->actingAs($this->kasir);
        config()->set('app.discount_manager_code', 'MANAGER123');

        Livewire::test(\App\Livewire\Pos\History::class)
            ->call('openVoid', $this->order->id)
            ->set('voidNotes', 'Produk salah pesan')
            ->set('voidManagerCode', 'MANAGER123')
            ->call('processVoid');

        $this->order->refresh();
        $this->assertSame('cancelled', $this->order->status);
        $this->assertStringContainsString('Produk salah pesan', $this->order->notes);

        // Stock restored: 1000 - 18 (checkout) + 18 (void) = 1000
        $this->assertSame(1000.0, (float) $this->ingredient->fresh()->current_stock);

        // Movement recorded.
        $this->assertDatabaseHas('stock_movements', [
            'reference_id' => $this->order->items()->first()->id,
            'type' => 'in',
            'quantity' => 18,
        ]);

        // Audit trail.
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'void_transaction',
        ]);
    }

    public function test_invalid_manager_code_rejects_void(): void
    {
        $this->actingAs($this->kasir);
        config()->set('app.discount_manager_code', 'MANAGER123');

        Livewire::test(\App\Livewire\Pos\History::class)
            ->call('openVoid', $this->order->id)
            ->set('voidNotes', 'Produk salah pesan')
            ->set('voidManagerCode', 'WRONG')
            ->call('processVoid');

        $this->order->refresh();
        $this->assertSame('completed', $this->order->status);
    }

    public function test_manager_can_void_without_code(): void
    {
        $this->actingAs($this->manager);

        Livewire::test(\App\Livewire\Pos\History::class)
            ->call('openVoid', $this->order->id)
            ->set('voidNotes', 'Customer batalkan pesanan')
            ->call('processVoid');

        $this->order->refresh();
        $this->assertSame('cancelled', $this->order->status);
    }
}