<?php

namespace App\Notifications;

use App\Models\Ingredient;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Collection;

class LowStockNotification implements ShouldBroadcast
{
    public Collection $ingredients;

    public function __construct(Collection $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Stok Kritis',
            'message' => $this->ingredients->count() . ' bahan baku di bawah batas minimum.',
            'ingredients' => $this->ingredients->map(fn ($i) => [
                'name' => $i->name,
                'current_stock' => $i->current_stock,
                'minimum_stock' => $i->minimum_stock,
                'unit' => $i->unit,
            ]),
        ];
    }

    public function broadcastOn()
    {
        return new Channel('notifications.global');
    }

    public function broadcastWith($notifiable): array
    {
        return $this->toArray($notifiable);
    }
}