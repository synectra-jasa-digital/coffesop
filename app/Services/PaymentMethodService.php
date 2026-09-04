<?php

namespace App\Services;

use App\Models\Setting;

class PaymentMethodService
{
    /**
     * Default set of supported payment methods.
     */
    public const ALL_METHODS = ['cash', 'qris', 'ewallet', 'bank_transfer', 'card'];

    /**
     * Get currently enabled payment methods (intersection with default set).
     *
     * @return array<int, string>
     */
    public function enabledMethods(): array
    {
        $raw = Setting::where('key', 'payment_methods_enabled')->value('value');
        $selected = $raw ? array_map('trim', explode(',', $raw)) : self::ALL_METHODS;

        // Normalize + filter to known methods.
        $selected = array_intersect(array_map('strtolower', $selected), self::ALL_METHODS);

        return array_values($selected ?: self::ALL_METHODS);
    }

    /**
     * Whether a method is enabled.
     */
    public function isEnabled(string $method): bool
    {
        return in_array(strtolower($method), $this->enabledMethods(), true);
    }

    public function label(string $method): string
    {
        return match (strtolower($method)) {
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'ewallet' => 'E-Wallet',
            'bank_transfer' => 'Transfer Bank',
            'card' => 'Kartu Debit/Kredit',
            default => ucfirst($method),
        };
    }
}