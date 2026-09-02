<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-ui.heading level="2" class="text-xl">
                {{ __('Pengaturan Sistem') }}
            </x-ui.heading>
        </div>
    </x-slot>

    <x-ui.section spacing="py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Store Information -->
            <x-ui.card>
                <h3 class="font-bold text-lg border-b border-gray-100 pb-4 mb-4">Informasi Toko</h3>
                
                @if (session()->has('message_store'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-sm shadow-sm text-sm font-semibold">
                        {{ session('message_store') }}
                    </div>
                @endif

                <form wire:submit="saveStoreInfo" class="space-y-4">
                    <div>
                        <x-input-label for="store_name" value="Nama Toko" />
                        <x-text-input id="store_name" type="text" class="mt-1 block w-full" wire:model="store_name" />
                        <x-input-error :messages="$errors->get('store_name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="store_phone" value="Nomor Telepon" />
                        <x-text-input id="store_phone" type="text" class="mt-1 block w-full" wire:model="store_phone" />
                    </div>
                    <div>
                        <x-input-label for="store_address" value="Alamat Toko (Dicetak di struk)" />
                        <textarea id="store_address" wire:model="store_address" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm text-sm" rows="3"></textarea>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <x-ui.button type="submit">Simpan Informasi</x-ui.button>
                    </div>
                </form>
            </x-ui.card>

            <!-- Tax & Charges -->
            <x-ui.card>
                <h3 class="font-bold text-lg border-b border-gray-100 pb-4 mb-4">Pajak & Biaya Layanan</h3>
                
                @if (session()->has('message_tax'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-sm shadow-sm text-sm font-semibold">
                        {{ session('message_tax') }}
                    </div>
                @endif

                <form wire:submit="saveTaxInfo" class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                        <div class="flex items-center mb-4">
                            <input id="tax_enabled" type="checkbox" wire:model="tax_enabled" class="rounded border-gray-300 text-[#398263] shadow-sm focus:ring-[#398263]">
                            <label for="tax_enabled" class="ml-2 font-medium text-gray-700">Aktifkan Pajak (PPN)</label>
                        </div>
                        <div>
                            <x-input-label for="tax_percentage" value="Persentase Pajak (%)" />
                            <x-text-input id="tax_percentage" type="number" step="0.1" class="mt-1 block w-full" wire:model="tax_percentage" />
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                        <div class="flex items-center mb-4">
                            <input id="service_charge_enabled" type="checkbox" wire:model="service_charge_enabled" class="rounded border-gray-300 text-[#398263] shadow-sm focus:ring-[#398263]">
                            <label for="service_charge_enabled" class="ml-2 font-medium text-gray-700">Aktifkan Service Charge (Dine-in)</label>
                        </div>
                        <div>
                            <x-input-label for="service_charge_percentage" value="Persentase Service Charge (%)" />
                            <x-text-input id="service_charge_percentage" type="number" step="0.1" class="mt-1 block w-full" wire:model="service_charge_percentage" />
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <x-ui.button type="submit">Simpan Pajak</x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </x-ui.section>
</x-app-layout>