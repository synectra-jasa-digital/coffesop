<?php

namespace Tests\Feature\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shift;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CashierShiftReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        Role::firstOrCreate(['name' => 'Kasir']);

        $this->manager = User::factory()->create();
        $this->manager->assignRole('Manager/Supervisor');

        $this->kasir = User::factory()->create();
        $this->kasir->assignRole('Kasir');

        $category = Category::create(['name' => 'Kopi', 'description' => 'Minuman kopi']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cafe Latte',
            'description' => 'Espresso dengan steamed milk',
            'price' => 28000,
            'is_active' => true,
        ]);

        $ingredient = Ingredient::create([
            'name' => 'Biji Kopi',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 10000,
        ]);

        $recipe = Recipe::create([
            'product_id' => $product->id,
            'name' => 'Resep '.$product->name,
        ]);

        RecipeIngredient::create([
            'recipe_id' => $recipe->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 18,
        ]);

        $this->table = Table::create(['number' => 'Meja 01', 'status' => 'available']);
        $this->product = $product;
    }

    public function test_cashier_shift_report_displays_closed_shifts(): void
    {
        $this->actingAs($this->manager);

        $shift = Shift::create([
            'user_id' => $this->kasir->id,
            'start_time' => Carbon::now()->subHours(3),
            'end_time' => Carbon::now(),
            'starting_cash' => 100000,
            'ending_cash' => 156000,
            'expected_cash' => 156000,
            'difference' => 0,
            'notes' => 'Shift pagi',
            'status' => 'closed',
        ]);

        Order::create([
            'order_number' => 'ORD-SHIFT1',
            'user_id' => $this->kasir->id,
            'shift_id' => $shift->id,
            'table_id' => $this->table->id,
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
            'order_id' => Order::where('order_number', 'ORD-SHIFT1')->first()->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => 28000,
            'subtotal' => 28000,
            'notes' => '',
            'status' => 'served',
        ]);

        Payment::create([
            'order_id' => Order::where('order_number', 'ORD-SHIFT1')->first()->id,
            'payment_method' => 'cash',
            'amount' => 31080,
            'status' => 'success',
        ]);

        $component = Livewire::test(\App\Livewire\Admin\Reports\Index::class)
            ->set('reportType', 'cashier_shift');

        $cashierData = $component->viewData('cashierData');
        $this->assertNotEmpty($cashierData);

        $row = $cashierData->first();
        $this->assertSame($this->kasir->name, $row['user_name']);
        $this->assertSame(1, $row['total_transactions']);
        $this->assertSame(31080.0, $row['total_sales']);
        $this->assertSame(0.0, $row['difference']);
    }

    public function test_cashier_shift_report_can_filter_by_cashier(): void
    {
        $this->actingAs($this->manager);

        $shift1 = Shift::create([
            'user_id' => $this->kasir->id,
            'start_time' => Carbon::now()->subHours(3),
            'end_time' => Carbon::now(),
            'starting_cash' => 100000,
            'ending_cash' => 100000,
            'expected_cash' => 100000,
            'difference' => 0,
            'notes' => 'Shift 1',
            'status' => 'closed',
        ]);

        $otherKasir = User::factory()->create();
        $otherKasir->assignRole('Kasir');

        $shift2 = Shift::create([
            'user_id' => $otherKasir->id,
            'start_time' => Carbon::now()->subHours(3),
            'end_time' => Carbon::now(),
            'starting_cash' => 50000,
            'ending_cash' => 50000,
            'expected_cash' => 50000,
            'difference' => 0,
            'notes' => 'Shift 2',
            'status' => 'closed',
        ]);

        $component = Livewire::test(\App\Livewire\Admin\Reports\Index::class)
            ->set('reportType', 'cashier_shift')
            ->set('selectedCashierId', $this->kasir->id);

        $cashierData = $component->viewData('cashierData');
        $this->assertCount(1, $cashierData);
        $this->assertSame($this->kasir->name, $cashierData->first()['user_name']);
    }
}