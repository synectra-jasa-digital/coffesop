<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-ui.heading level="2" class="text-xl">
                {{ __('Manajemen Stok Bahan Baku') }}
            </x-ui.heading>
            <div class="flex flex-wrap gap-2">
                <x-ui.button wire:click="openAddIngredient" variant="outline" class="border-gray-300 text-gray-700 hover:bg-gray-50">+ Bahan Baru</x-ui.button>
                <x-ui.button wire:click="openStockIn" variant="outline">Stok Masuk</x-ui.button>
                <x-ui.button wire:click="openOpname">Stok Opname</x-ui.button>
            </div>
        </div>
    </x-slot>

    <x-ui.section spacing="py-12">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-sm shadow-sm flex justify-between items-center">
                <span class="text-sm font-semibold">{{ session('message') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-green-800 hover:text-green-900">&times;</button>
            </div>
        @endif

        <x-ui.card padding="p-0" class="overflow-hidden">
            <x-ui.table :headers="['Bahan Baku', 'Stok Saat Ini', 'Stok Minimum', 'Satuan', 'Aksi']">
                @forelse($ingredients as $ingredient)
                <x-ui.table-row class="{{ $ingredient->current_stock <= $ingredient->minimum_stock ? 'bg-red-50/30' : '' }}">
                    <x-ui.table-cell class="font-medium">{{ $ingredient->name }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <span class="flex items-center gap-2 {{ $ingredient->current_stock <= $ingredient->minimum_stock ? 'text-red-600 font-bold' : '' }}">
                            {{ number_format($ingredient->current_stock, 0, ',', '.') }}
                            @if($ingredient->current_stock <= $ingredient->minimum_stock)
                                <x-ui.badge variant="danger">Kritis</x-ui.badge>
                            @endif
                        </span>
                    </x-ui.table-cell>
                    <x-ui.table-cell>{{ number_format($ingredient->minimum_stock, 0, ',', '.') }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $ingredient->unit }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <button class="text-primary hover:underline text-sm font-semibold">Riwayat</button>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="5" class="text-center text-gray-500 py-4">Belum ada bahan baku.</x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table>
            <div class="p-4 border-t border-gray-100">
                {{ $ingredients->links() }}
            </div>
        </x-ui.card>
    </x-ui.section>

    <!-- Stock In Modal -->
    <x-modal wire:model.live="showStockInModal" maxWidth="md" :show="$showStockInModal">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Penerimaan Barang (Stok Masuk)</h2>
            <form wire:submit="processStockIn" class="space-y-4">
                <div>
                    <x-input-label for="stockInIngredientId" value="Bahan Baku" />
                    <select id="stockInIngredientId" wire:model="stockInIngredientId" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm">
                        <option value="">Pilih...</option>
                        @foreach(\App\Models\Ingredient::orderBy('name')->get() as $ing)
                            <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('stockInIngredientId')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="stockInQuantity" value="Jumlah Tambahan" />
                    <x-text-input id="stockInQuantity" type="number" step="0.01" class="mt-1 block w-full" wire:model="stockInQuantity" />
                    <x-input-error :messages="$errors->get('stockInQuantity')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="stockInNotes" value="Catatan / Nomor Faktur" />
                    <textarea id="stockInNotes" wire:model="stockInNotes" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm text-sm" rows="2"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="stockInUnitCost" value="Harga Beli per Satuan (Rp)" />
                        <x-text-input id="stockInUnitCost" type="number" step="0.01" class="mt-1 block w-full" wire:model="stockInUnitCost" />
                        <x-input-error :messages="$errors->get('stockInUnitCost')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="stockInExpiryDate" value="Kedaluwarsa (Opsional)" />
                        <x-text-input id="stockInExpiryDate" type="date" class="mt-1 block w-full" wire:model="stockInExpiryDate" />
                        <x-input-error :messages="$errors->get('stockInExpiryDate')" class="mt-2" />
                    </div>
                </div>

                <div class="flex justify-end mt-6 gap-3">
                    <button type="button" wire:click="$set('showStockInModal', false)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">Batal</button>
                    <x-ui.button type="submit">Simpan Stok</x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Stock Opname Modal -->
    <x-modal wire:model.live="showOpnameModal" maxWidth="2xl" :show="$showOpnameModal">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Stok Opname (Penyesuaian Fisik)</h2>
            <form wire:submit="processOpname">
                <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-sm mb-4">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-gray-700">Bahan Baku</th>
                                <th class="px-4 py-3 font-semibold text-gray-700">Stok Sistem</th>
                                <th class="px-4 py-3 font-semibold text-gray-700">Stok Fisik Real</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach(\App\Models\Ingredient::orderBy('name')->get() as $ing)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium">{{ $ing->name }} ({{ $ing->unit }})</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $ing->current_stock }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" step="0.01" wire:model="opnameData.{{ $ing->id }}" class="border-gray-300 focus:border-primary focus:ring-primary rounded-sm shadow-sm w-32 py-1 px-2 text-sm text-right">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-6">
                    <x-input-label for="opnameNotes" value="Catatan Stok Opname" />
                    <textarea id="opnameNotes" wire:model="opnameNotes" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm text-sm" rows="2" placeholder="Catatan opsional mengenai kegiatan opname hari ini..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="$set('showOpnameModal', false)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">Batal</button>
                    <x-ui.button type="submit">Konfirmasi Selisih & Simpan</x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>
    <!-- Add Ingredient Modal -->
    <x-modal wire:model.live="showAddModal" maxWidth="sm" :show="$showAddModal">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Tambah Bahan Baku Baru</h2>
            <form wire:submit="saveNewIngredient" class="space-y-4">
                <div>
                    <x-input-label for="newIngName" value="Nama Bahan Baku" />
                    <x-text-input id="newIngName" type="text" class="mt-1 block w-full" wire:model="newIngName" placeholder="Contoh: Gula Aren" />
                    <x-input-error :messages="$errors->get('newIngName')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="newIngUnit" value="Satuan (gram, ml, pcs, dll)" />
                    <x-text-input id="newIngUnit" type="text" class="mt-1 block w-full" wire:model="newIngUnit" />
                    <x-input-error :messages="$errors->get('newIngUnit')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="newIngMinStock" value="Batas Stok Minimum (Peringatan Kritis)" />
                    <x-text-input id="newIngMinStock" type="number" step="0.01" class="mt-1 block w-full" wire:model="newIngMinStock" />
                    <x-input-error :messages="$errors->get('newIngMinStock')" class="mt-2" />
                </div>
                <div class="flex justify-end pt-4 gap-3">
                    <button type="button" wire:click="$set('showAddModal', false)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-sm font-semibold text-xs uppercase">Batal</button>
                    <x-ui.button type="submit">Simpan</x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>
</div>

