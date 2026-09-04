<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

/**
 * Mock payment gateway simulating Midtrans/Xendit integration.
 *
 * In production this would call a real gateway. For development/testing this
 * returns deterministic, fake responses. To switch to a real provider, replace
 * the contents of createTransaction() with a real HTTP call to the provider.
 */
class PaymentGatewayService
{
    /**
     * Create a fake payment transaction. Returns [external_id, redirect_url].
     */
    public function createTransaction(Order $order, string $method = 'qris'): array
    {
        $externalId = 'GATEWAY-' . strtoupper(Str::random(12));

        return [
            'external_id' => $externalId,
            'redirect_url' => route('payment.simulator', ['order' => $order->id, 'method' => $method, 'gatewayId' => $externalId]),
            'method' => $method,
            'amount' => (float) $order->total,
            'expires_at' => now()->addMinutes(15)->toIso8601String(),
            'status' => 'pending',
        ];
    }

    /**
     * Mark the payment as settled (called by the simulator or webhook in production).
     */
    public function settle(Order $order, string $externalId, string $method = 'qris'): Payment
    {
        $payment = $order->payments()->where('payment_method', $method)->latest()->first();

        if (! $payment) {
            $payment = $order->payments()->create([
                'payment_method' => $method,
                'amount' => (float) $order->total,
                'status' => 'success',
                'reference_number' => $externalId,
                'gateway_response' => ['external_id' => $externalId, 'settled_at' => now()->toIso8601String()],
            ]);
        } else {
            $payment->update([
                'status' => 'success',
                'reference_number' => $externalId,
                'gateway_response' => array_merge((array) $payment->gateway_response, [
                    'external_id' => $externalId,
                    'settled_at' => now()->toIso8601String(),
                ]),
            ]);
        }

        $order->update(['payment_status' => 'paid']);

        return $payment;
    }
}