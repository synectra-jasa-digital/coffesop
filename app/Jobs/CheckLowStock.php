<?php

namespace App\Jobs;

use App\Models\Ingredient;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockNotification;

class CheckLowStock implements ShouldQueue
{
    public function handle(): void
    {
        $enabled = filter_var(
            Setting::where('key', 'low_stock_notification_enabled')->value('value') ?? false,
            FILTER_VALIDATE_BOOLEAN
        );

        if (! $enabled) {
            return;
        }

        $critical = Ingredient::whereColumn('current_stock', '<=', 'minimum_stock')->get();

        if ($critical->isEmpty()) {
            return;
        }

        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Owner/Admin', 'Manager/Supervisor']);
        })->get();

        foreach ($users as $user) {
            Notification::send($user, new LowStockNotification($critical));
        }

        Log::info('Low stock notification sent', ['count' => $critical->count()]);
    }
}