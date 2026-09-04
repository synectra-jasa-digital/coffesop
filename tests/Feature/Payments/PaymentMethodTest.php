<?php

namespace Tests\Feature\Payments;

use App\Models\Setting;
use App\Services\PaymentMethodService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_methods_enabled_by_default(): void
    {
        $service = app(PaymentMethodService::class);

        $this->assertContains('cash', $service->enabledMethods());
        $this->assertContains('qris', $service->enabledMethods());
        $this->assertTrue($service->isEnabled('cash'));
    }

    public function test_can_restrict_methods_via_settings(): void
    {
        Setting::updateOrCreate(
            ['key' => 'payment_methods_enabled'],
            ['value' => 'cash,qris', 'type' => 'string', 'group' => 'payment']
        );

        $service = app(PaymentMethodService::class);
        $enabled = $service->enabledMethods();

        $this->assertContains('cash', $enabled);
        $this->assertContains('qris', $enabled);
        $this->assertNotContains('ewallet', $enabled);
        $this->assertNotContains('card', $enabled);

        $this->assertTrue($service->isEnabled('qris'));
        $this->assertFalse($service->isEnabled('card'));
    }

    public function test_unknown_methods_are_filtered_out(): void
    {
        Setting::updateOrCreate(
            ['key' => 'payment_methods_enabled'],
            ['value' => 'cash,bitcoin,card', 'type' => 'string', 'group' => 'payment']
        );

        $service = app(PaymentMethodService::class);
        $enabled = $service->enabledMethods();

        $this->assertNotContains('bitcoin', $enabled);
        $this->assertContains('cash', $enabled);
        $this->assertContains('card', $enabled);
    }

    public function test_labels_are_available(): void
    {
        $service = app(PaymentMethodService::class);
        $this->assertSame('Tunai', $service->label('cash'));
        $this->assertSame('QRIS', $service->label('qris'));
        $this->assertSame('E-Wallet', $service->label('ewallet'));
    }
}