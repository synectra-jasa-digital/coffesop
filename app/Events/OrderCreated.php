<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Broadcast;

class OrderCreated implements ShouldBroadcast
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load(['items.product', 'table']);
    }

    public function broadcastOn(): Channel
    {
        return new Channel('kds');
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'type' => $this->order->type,
            'table' => $this->order->table ? $this->order->table->number : null,
            'status' => $this->order->status,
            'total' => (float) $this->order->total,
            'items' => $this->order->items->map(fn ($i) => [
                'name' => $i->product->name ?? 'Produk Dihapus',
                'quantity' => $i->quantity,
            ]),
            'created_at' => $this->order->created_at->toIso8601String(),
        ];
    }
}