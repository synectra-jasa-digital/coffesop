<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-ui.heading level="2" class="text-xl">
                {{ __('Promo & Diskon') }}
            </x-ui.heading>
            <x-ui.button wire:click="create">Buat Promo</x-ui.button>
        </div>
    </x-slot>

    <x-ui.section spacing="py-12">
        @if (session()->has('message'))
            <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-sm shadow-sm flex justify-between items-center">
                <span class="text-sm font-semibold">{{ session('message') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-green-800 hover:text-green-900">&times;</button>
            </div>
        @endif

        <x-ui.card padding="p-0" class="overflow-hidden">
            <x-ui.table :headers="['Nama', 'Kode', 'Tipe', 'Nilai', 'Mulai', 'Berakhir', 'Status', 'Aksi']">
                @forelse($discounts as $discount)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $discount->name }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $discount->code ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ ucfirst($discount->type) }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($discount->type === 'percentage')
                            {{ $discount->value }}%
                        @else
                            Rp {{ number_format($discount->value, 0, ',', '.') }}
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell class="text-xs">{{ $discount->start_date?->format('d M Y') ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell class="text-xs">{{ $discount->end_date?->format('d M Y') ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($discount->isActive())
                            <x-ui.badge variant="success">Aktif</x-ui.badge>
                        @else
                            <x-ui.badge variant="default">Nonaktif</x-ui.badge>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex gap-3">
                            <button wire:click="edit({{ $discount->id }})" class="text-primary hover:underline text-sm font-semibold">Edit</button>
                            <button wire:click="toggleStatus({{ $discount->id }})" class="text-blue-600 hover:underline text-sm font-semibold">
                                {{ $discount->isActive() ? 'Nonaktif' : 'Aktif' }}
                            </button>
                            <button wire:click="delete({{ $discount->id }})" class="text-red-600 hover:underline text-sm font-semibold" onclick="return confirm('Hapus promo ini?')">Hapus</button>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="8" class="text-center text-gray-500">Tidak ada data promo.</x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table>

            <div class="p-4 border-t border-gray-100">
                {{ $discounts->links() }}
            </div>
        </x-ui.card>
    </x-ui.section>

    <!-- Promo Modal -->
    <x-modal wire:model.live="showModal" maxWidth="md" :show="$showModal">
        <div class="p-6">
            <h2 class="text-lg font-bold font-serif text-gray-900 mb-6">
                {{ $isEditing ? 'Edit Promo' : 'Buat Promo Baru' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <x-input-label for="name" value="Nama Promo" />
                    <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="code" value="Kode Promo (Opsional)" />
                    <x-text-input id="code" type="text" class="mt-1 block w-full" wire:model="code" placeholder="Contoh: PROMO10" />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="type" value="Tipe Diskon" />
                        <select id="type" wire:model="type" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="value" value="Nilai" />
                        <x-text-input id="value" type="number" step="0.01" class="mt-1 block w-full" wire:model="value" />
                        <x-input-error :messages="$errors->get('value')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="startDate" value="Mulai Tgl" />
                        <x-text-input id="startDate" type="date" class="mt-1 block w-full" wire:model="startDate" />
                        <x-input-error :messages="$errors->get('startDate')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="endDate" value="Berakhir Tgl" />
                        <x-text-input id="endDate" type="date" class="mt-1 block w-full" wire:model="endDate" />
                        <x-input-error :messages="$errors->get('endDate')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="minimumPurchase" value="Pembelian Minimum (Rp, opsional)" />
                    <x-text-input id="minimumPurchase" type="number" step="0.01" class="mt-1 block w-full" wire:model="minimumPurchase" />
                    <x-input-error :messages="$errors->get('minimumPurchase')" class="mt-2" />
                </div>

                <div class="flex items-center mt-2">
                    <input id="isActive" type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-[#398263] shadow-sm focus:ring-[#398263]">
                    <label for="isActive" class="ml-2 text-sm text-gray-600">Aktif (Tampil di POS)</label>
                </div>

                <div class="flex justify-end mt-6 gap-3">
                    <button type="button" wire:click="$set('showModal', false)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        Batal
                    </button>
                    <x-ui.button type="submit">
                        Simpan
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>