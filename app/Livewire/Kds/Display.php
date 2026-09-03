<?php

namespace App\Livewire\Kds;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Models\OrderItem;
use App\Events\OrderStatusUpdated;

#[Layout('layouts.kds')]
class Display extends Component
{
    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->status = $status;
            $order->save();

            // Also update all items if order is completed
            if ($status === 'completed') {
                $order->items()->update(['status' => 'served']);
            }

            // Broadcast the status change to the KDS channel in real time.
            broadcast(new OrderStatusUpdated($order))->toOthers();
        }
    }

    public function render()
    {
        $activeOrders = Order::with(['items.product', 'table'])
            ->whereIn('status', ['pending', 'processing'])
            ->orderBy('created_at', 'asc')
            ->get();

        $newCount = $activeOrders->where('status', 'pending')->count();
        $processingCount = $activeOrders->where('status', 'processing')->count();

        return view('livewire.kds.display', [
            'activeOrders' => $activeOrders,
            'newCount' => $newCount,
            'processingCount' => $processingCount
        ]);
    }
}
