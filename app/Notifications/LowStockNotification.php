<?php

namespace App\Notifications;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    public Collection $ingredients;

    public function __construct(Collection $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    public function via($notifiable): array
    {
        return ['database'];
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
}