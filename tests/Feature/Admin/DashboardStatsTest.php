<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup owner user
        Role::create(['name' => 'Owner/Admin']);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('Owner/Admin');
    }

    public function test_dashboard_displays_correct_today_sales_and_transactions()
    {
        // Buat order secara manual (tidak pakai factory karena belum tersedia)
        Order::create([
            'order_number' => 'ORD-1',
            'type' => 'take-away',
            'subtotal' => 50000,
            'tax_amount' => 0,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => 50000,
            'status' => 'completed',
            'user_id' => $this->owner->id,
            'created_at' => now()
        ]);
        Order::create([
            'order_number' => 'ORD-2',
            'type' => 'take-away',
            'subtotal' => 50000,
            'tax_amount' => 0,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => 50000,
            'status' => 'completed',
            'user_id' => $this->owner->id,
            'created_at' => now()
        ]);
        Order::create([
            'order_number' => 'ORD-3',
            'type' => 'take-away',
            'subtotal' => 50000,
            'tax_amount' => 0,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => 50000,
            'status' => 'completed',
            'user_id' => $this->owner->id,
            'created_at' => now()
        ]);

        // Buat order kemarin
        $yesterdayOrder = Order::create([
            'order_number' => 'ORD-4',
            'type' => 'take-away',
            'subtotal' => 100000,
            'tax_amount' => 0,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => 100000,
            'status' => 'completed',
            'user_id' => $this->owner->id,
        ]);
        $yesterdayOrder->created_at = now()->subDay();
        $yesterdayOrder->save();

        $this->actingAs($this->owner);

        Livewire::test('dashboard-stats')
            ->assertViewHas('totalSales', 150000)
            ->assertViewHas('totalTransactions', 3)
            ->assertSee('150.000') // Format rupiah (abaikan Rp prefix di strict assert)
            ->assertSee('3'); // Jumlah transaksi
    }

    public function test_dashboard_shows_critical_stock_count()
    {
        // Stok aman
        Ingredient::create(['name' => 'Aman', 'unit' => 'g', 'current_stock' => 50, 'minimum_stock' => 10]);

        // Stok kritis (dibawah minimum)
        Ingredient::create(['name' => 'Kritis 1', 'unit' => 'g', 'current_stock' => 5, 'minimum_stock' => 10]);
        Ingredient::create(['name' => 'Kritis 2', 'unit' => 'g', 'current_stock' => 0, 'minimum_stock' => 5]);

        $this->actingAs($this->owner);

        Livewire::test('dashboard-stats')
            ->assertViewHas('criticalStockCount', 2)
            ->assertSee('2 Item')
            ->assertSee('text-red-600'); // Class UI untuk warning
    }

    public function test_dashboard_shows_top_selling_products()
    {
        $category = Category::create(['name' => 'Test Cat', 'description' => 'Test']);

        $product1 = Product::create(['name' => 'Kopi Susu', 'category_id' => $category->id, 'price' => 10000, 'is_active' => true]);
        $product2 = Product::create(['name' => 'Espresso', 'category_id' => $category->id, 'price' => 20000, 'is_active' => true]);

        $order = Order::create([
            'order_number' => 'ORD-100',
            'type' => 'take-away',
            'subtotal' => 90000,
            'tax_amount' => 0,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => 90000,
            'status' => 'completed',
            'user_id' => $this->owner->id,
            'created_at' => now()
        ]);

        // Kopi Susu terjual 5
        OrderItem::create(['order_id' => $order->id, 'product_id' => $product1->id, 'name' => $product1->name, 'quantity' => 5, 'price' => 10000, 'subtotal' => 50000]);
        // Espresso terjual 2
        OrderItem::create(['order_id' => $order->id, 'product_id' => $product2->id, 'name' => $product2->name, 'quantity' => 2, 'price' => 20000, 'subtotal' => 40000]);

        $this->actingAs($this->owner);

        Livewire::test('dashboard-stats')
            ->assertSee('Kopi Susu')
            ->assertSee('5 terjual')
            ->assertSee('Espresso')
            ->assertSee('2 terjual');
    }
}