<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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
        // Buat order hari ini
        Order::factory()->count(3)->create([
            'total' => 50000,
            'status' => 'completed',
            'created_at' => now()
        ]);

        // Buat order kemarin (tidak boleh masuk hitungan hari ini)
        Order::factory()->create([
            'total' => 100000,
            'status' => 'completed',
            'created_at' => now()->subDay()
        ]);

        $this->actingAs($this->owner);

        Livewire::test('dashboard-stats')
            ->assertViewHas('totalSales', 150000)
            ->assertViewHas('totalTransactions', 3)
            ->assertSee('Rp 150.000') // Format rupiah
            ->assertSee('3'); // Jumlah transaksi
    }

    public function test_dashboard_shows_critical_stock_count()
    {
        // Stok aman
        Ingredient::factory()->create(['current_stock' => 50, 'minimum_stock' => 10]);

        // Stok kritis (dibawah minimum)
        Ingredient::factory()->create(['current_stock' => 5, 'minimum_stock' => 10]);
        Ingredient::factory()->create(['current_stock' => 0, 'minimum_stock' => 5]);

        $this->actingAs($this->owner);

        Livewire::test('dashboard-stats')
            ->assertViewHas('criticalStockCount', 2)
            ->assertSee('2 Item')
            ->assertSee('text-red-600'); // Class UI untuk warning
    }

    public function test_dashboard_shows_top_selling_products()
    {
        $product1 = Product::factory()->create(['name' => 'Kopi Susu']);
        $product2 = Product::factory()->create(['name' => 'Espresso']);

        $order = Order::factory()->create(['status' => 'completed', 'created_at' => now()]);

        // Kopi Susu terjual 5
        OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product1->id, 'quantity' => 5]);
        // Espresso terjual 2
        OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product2->id, 'quantity' => 2]);

        $this->actingAs($this->owner);

        Livewire::test('dashboard-stats')
            ->assertSee('Kopi Susu')
            ->assertSee('5 terjual')
            ->assertSee('Espresso')
            ->assertSee('2 terjual');
    }
}