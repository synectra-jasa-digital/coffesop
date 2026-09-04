<?php

namespace Tests\Feature\Payments;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentGatewayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected Order $order;
    protected User $cashier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cashier = User::factory()->create();
        $this->order = Order::create([
            'order_number' => 'ORD-PAY1',
            'user_id' => $this->cashier->id,
            'type' => 'dine-in',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'subtotal' => 28000,
            'tax_amount' => 3080,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => 31080,
        ]);
    }

    public function test_gateway_creates_pending_transaction(): void
    {
        $service = app(PaymentGatewayService::class);
        $tx = $service->createTransaction($this->order, 'qris');

        $this->assertStringStartsWith('GATEWAY-', $tx['external_id']);
        $this->assertSame('qris', $tx['method']);
        $this->assertSame('pending', $tx['status']);
        $this->assertSame(31080.0, $tx['amount']);

        // Validate route() resolution succeeds (catches parameter name mismatches).
        $url = route('payment.simulator', [
            'order' => $this->order->id,
            'method' => 'qris',
            'gatewayId' => $tx['external_id'],
        ]);

        $this->assertStringContainsString('payment/simulator', $url);
        $this->assertStringContainsString($tx['external_id'], $url);
    }

    public function test_settle_marks_order_as_paid(): void
    {
        $this->order->payments()->create([
            'payment_method' => 'qris',
            'amount' => 31080,
            'status' => 'pending',
        ]);

        app(PaymentGatewayService::class)->settle($this->order, 'GATEWAY-TEST123', 'qris');

        $this->order->refresh();
        $this->assertSame('paid', $this->order->payment_status);

        $payment = $this->order->payments()->first();
        $this->assertSame('success', $payment->status);
        $this->assertSame('GATEWAY-TEST123', $payment->reference_number);
    }

    public function test_settle_creates_payment_if_none_exists(): void
    {
        app(PaymentGatewayService::class)->settle($this->order, 'GATEWAY-NEW1', 'qris');

        $this->assertDatabaseCount('payments', 1);
        $this->assertSame(31080.0, (float) $this->order->payments()->first()->amount);
    }

    public function test_simulator_webhook_settles_payment(): void
    {
        $this->order->payments()->create([
            'payment_method' => 'qris',
            'amount' => 31080,
            'status' => 'pending',
        ]);

        $response = $this->postJson(route('payment.webhook', $this->order->id), [
            'gateway_id' => 'GATEWAY-SIM1',
            'status' => 'success',
            'method' => 'qris',
        ]);

        $response->assertOk()->assertJson(['status' => 'ok']);

        $this->order->refresh();
        $this->assertSame('paid', $this->order->payment_status);
    }
}