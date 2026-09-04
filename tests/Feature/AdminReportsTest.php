<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Shift;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Admin\Reports\Index;

class AdminReportsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::firstOrCreate(['name' => 'Owner/Admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Owner/Admin');
    }

    public function test_can_view_sales_daily_report()
    {
        $order = Order::create([
            'order_number' => 'ORD-123',
            'user_id' => $this->admin->id,
            'type' => 'take-away',
            'status' => 'completed',
            'payment_status' => 'paid',
            'subtotal' => 50000,
            'total' => 55000,
        ]);

        Livewire::actingAs($this->admin)
            ->test(Index::class)
            ->set('reportType', 'sales_daily')
            ->assertSee('55.000') // Total revenue
            ->assertSee('1'); // Total transactions
    }

    public function test_can_view_sales_period_report()
    {
        $order = Order::create([
            'order_number' => 'ORD-456',
            'user_id' => $this->admin->id,
            'type' => 'take-away',
            'status' => 'completed',
            'payment_status' => 'paid',
            'subtotal' => 100000,
            'total' => 110000,
            'created_at' => Carbon::now()->subDays(2)
        ]);

        Livewire::actingAs($this->admin)
            ->test(Index::class)
            ->set('reportType', 'sales_period')
            ->set('startDate', Carbon::now()->subDays(5)->format('Y-m-d'))
            ->set('endDate', Carbon::now()->format('Y-m-d'))
            ->assertSee('110.000')
            ->assertSee('1');
    }

    public function test_can_view_stock_report_with_critical_items()
    {
        Ingredient::create([
            'name' => 'Biji Kopi Aman',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 500,
        ]);

        Ingredient::create([
            'name' => 'Susu Kritis',
            'unit' => 'ml',
            'minimum_stock' => 2000,
            'current_stock' => 500,
        ]);

        Livewire::actingAs($this->admin)
            ->test(Index::class)
            ->set('reportType', 'stock')
            ->assertSee('Total Jenis Bahan Baku')
            ->assertSee('2') // Total items
            ->assertSee('Item Kritis')
            ->assertSee('1') // Critical items
            ->assertSee('Susu Kritis')
            ->assertSee('Biji Kopi Aman');
    }

    public function test_can_view_cashier_shift_performance()
    {
        $shift = Shift::create([
            'user_id' => $this->admin->id,
            'start_time' => Carbon::now()->subHours(8),
            'end_time' => Carbon::now(),
            'starting_cash' => 100000,
            'ending_cash' => 250000,
            'expected_cash' => 255000,
            'difference' => -5000,
            'status' => 'closed',
            'notes' => 'Kurang lima ribu',
        ]);

        Order::create([
            'order_number' => 'ORD-789',
            'user_id' => $this->admin->id,
            'shift_id' => $shift->id,
            'type' => 'take-away',
            'status' => 'completed',
            'payment_status' => 'paid',
            'subtotal' => 150000,
            'total' => 155000,
        ]);

        Livewire::actingAs($this->admin)
            ->test(Index::class)
            ->set('reportType', 'cashier_shift')
            ->assertSee('Total Shift')
            ->assertSee('1') // Shift count
            ->assertSee('155.000') // Total Pencairan
            ->assertSee('-5.000') // Selisih
            ->assertSee('Kurang lima ribu');
    }
}
