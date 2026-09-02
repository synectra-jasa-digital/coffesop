<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\Setting;

class Index extends Component
{
    // Store Info
    public $store_name;
    public $store_address;
    public $store_phone;

    // Taxes & Charges
    public $tax_enabled = false;
    public $tax_percentage = 11;
    public $service_charge_enabled = false;
    public $service_charge_percentage = 5;

    public function mount()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $this->store_name = $settings['store_name'] ?? 'Good Coffee.';
        $this->store_address = $settings['store_address'] ?? '';
        $this->store_phone = $settings['store_phone'] ?? '';
        
        $this->tax_enabled = filter_var($settings['tax_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $this->tax_percentage = $settings['tax_percentage'] ?? 11;
        $this->service_charge_enabled = filter_var($settings['service_charge_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $this->service_charge_percentage = $settings['service_charge_percentage'] ?? 5;
    }

    public function saveStoreInfo()
    {
        $this->validate([
            'store_name' => 'required|string',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string',
        ]);

        $this->updateSetting('store_name', $this->store_name, 'string', 'store');
        $this->updateSetting('store_address', $this->store_address, 'string', 'store');
        $this->updateSetting('store_phone', $this->store_phone, 'string', 'store');

        session()->flash('message_store', 'Informasi toko berhasil diperbarui.');
    }

    public function saveTaxInfo()
    {
        $this->validate([
            'tax_percentage' => 'required|numeric|min:0',
            'service_charge_percentage' => 'required|numeric|min:0',
        ]);

        $this->updateSetting('tax_enabled', $this->tax_enabled ? 'true' : 'false', 'boolean', 'tax');
        $this->updateSetting('tax_percentage', $this->tax_percentage, 'number', 'tax');
        $this->updateSetting('service_charge_enabled', $this->service_charge_enabled ? 'true' : 'false', 'boolean', 'tax');
        $this->updateSetting('service_charge_percentage', $this->service_charge_percentage, 'number', 'tax');

        session()->flash('message_tax', 'Pengaturan pajak & biaya berhasil diperbarui.');
    }

    private function updateSetting($key, $value, $type, $group)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
    }

    public function render()
    {
        return view('livewire.admin.settings.index');
    }
}