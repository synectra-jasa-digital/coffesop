<?php

namespace Tests\Feature\Kds;

use App\Models\User;
use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KdsDisplayTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Barista/Gudang']);
        Role::create(['name' => 'Kasir']);

        $this->barista = User::factory()->create();
        $this->barista->assignRole('Barista/Gudang');

        $this->kasir = User::factory()->create();
        $this->kasir->assignRole('Kasir');
    }

    public function test_kasir_cannot_access_kds()
    {
        $this->actingAs($this->kasir);
        $response = $this->get('/kds');

        // Biasanya middleware role akan redirect ke 403 atau dashboard
        $response->assertStatus(403);
    }

    public function test_barista_can_access_kds_and_see_pending_orders()
    {
        $table = Table::factory()->create(['number' => 'Meja 1']);
        $product = Product::factory()->create(['name' => 'Americano']);

        // Buat order pending
        $order = Order::factory()->create([
            'order_number' => 'ORD-001',
            'type' => 'dine-in',
            'table_id' => $table->id,
            'status' => 'pending'
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'notes' => 'Less ice'
        ]);

        $this->actingAs($this->barista);

        Livewire::test('kds.display')
            ->assertSee('ORD-001')
            ->assertSee('Meja 1')
            ->assertSee('Dine in')
            ->assertSee('Americano')
            ->assertSee('Less ice')
            ->assertSee('Mulai Proses'); // Button untuk pending
    }

    public function test_barista_can_update_order_status_to_processing()
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $this->actingAs($this->barista);

        Livewire::test('kds.display')
            ->call('updateOrderStatus', $order->id, 'processing')
            ->assertHasNoErrors();

        $this->assertEquals('processing', $order->fresh()->status);
    }

    public function test_completed_orders_disappear_from_kds()
    {
        $order = Order::factory()->create(['status' => 'processing', 'order_number' => 'ORD-123']);

        $this->actingAs($this->barista);

        Livewire::test('kds.display')
            ->assertSee('ORD-123')
            ->call('updateOrderStatus', $order->id, 'completed')
            ->assertDontSee('ORD-123'); // Harus hilang dari layar aktif

        $this->assertEquals('completed', $order->fresh()->status);
    }
}