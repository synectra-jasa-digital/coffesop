<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Simulated payment gateway approval page (development only).
     */
    public function simulator(Request $request, Order $order, string $method, string $gatewayId)
    {
        return view('payment.simulator', [
            'order' => $order,
            'method' => $method,
            'gatewayId' => $gatewayId,
            'amount' => (float) $order->total,
        ]);
    }

    /**
     * Simulated webhook called after the customer "pays".
     */
    public function webhook(Request $request, Order $order)
    {
        $validated = $request->validate([
            'gateway_id' => 'required|string',
            'status' => 'required|in:success,failed,pending',
            'method' => 'nullable|string',
        ]);

        $method = $validated['method'] ?? 'qris';

        if ($validated['status'] === 'success') {
            app(PaymentGatewayService::class)->settle($order, $validated['gateway_id'], $method);

            return response()->json(['status' => 'ok', 'message' => 'Pembayaran berhasil']);
        }

        return response()->json(['status' => 'pending', 'message' => 'Pembayaran belum selesai']);
    }
}