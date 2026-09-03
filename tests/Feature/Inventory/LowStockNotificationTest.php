<?php

namespace Tests\Feature\Inventory;

use App\Jobs\CheckLowStock;
use App\Models\Ingredient;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LowStockNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Owner/Admin']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Barista/Gudang']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Kasir']);

        $this->manager = User::factory()->create();
        $this->manager->assignRole('Manager/Supervisor');
    }

    public function test_low_stock_job_sends_notification_when_enabled(): void
    {
        Setting::updateOrCreate(
            ['key' => 'low_stock_notification_enabled'],
            ['value' => 'true', 'type' => 'boolean', 'group' => 'stock']
        );

        Ingredient::create([
            'name' => 'Biji Kopi',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 50, // below minimum
        ]);

        Ingredient::create([
            'name' => 'Susu',
            'unit' => 'ml',
            'minimum_stock' => 100,
            'current_stock' => 500, // above minimum
        ]);

        app(CheckLowStock::class)->handle();

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->manager->id,
            'notifiable_type' => User::class,
        ]);

        $notification = Notification::where('notifiable_id', $this->manager->id)->first();
        $this->assertNotNull($notification);
        $data = json_decode($notification->data, true);
        $this->assertStringContainsString('Biji Kopi', json_encode($data));
    }

    public function test_low_stock_job_is_noop_when_disabled(): void
    {
        Setting::updateOrCreate(
            ['key' => 'low_stock_notification_enabled'],
            ['value' => 'false', 'type' => 'boolean', 'group' => 'stock']
        );

        Ingredient::create([
            'name' => 'Gula',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 10, // below minimum
        ]);

        app(CheckLowStock::class)->handle();

        $this->assertDatabaseCount('notifications', 0);
    }
}