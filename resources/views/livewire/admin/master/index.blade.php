<x-app-layout>
    <x-slot name="header">
        <x-ui.heading level="2" class="text-xl">
            {{ __('Data Master') }}
        </x-ui.heading>
    </x-slot>

    <x-ui.section spacing="py-6">
        @if (session()->has('message'))
            <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-sm shadow-sm text-sm font-semibold flex justify-between">
                <span>{{ session('message') }}</span>
                <button onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif

        <div class="mb-6 flex border-b border-gray-200">
            <button wire:click="switchTab('categories')" class="px-6 py-3 font-semibold text-sm {{ $activeTab === 'categories' ? 'border-b-2 border-[#398263] text-[#398263]' : 'text-gray-500 hover:text-gray-700' }}">Kategori Menu</button>
            <button wire:click="switchTab('tables')" class="px-6 py-3 font-semibold text-sm {{ $activeTab === 'tables' ? 'border-b-2 border-[#398263] text-[#398263]' : 'text-gray-500 hover:text-gray-700' }}">Manajemen Meja</button>
        </div>

        @if($activeTab === 'categories')
        <!-- Categories Tab -->
        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800">Daftar Kategori</h3>
                <x-ui.button wire:click="openCategoryModal">Tambah Kategori</x-ui.button>
            </div>
            <x-ui.table :headers="['Nama Kategori', 'Deskripsi', 'Aksi']">
                @foreach($categories as $cat)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $cat->name }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $cat->description ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <button wire:click="openCategoryModal({{ $cat->id }})" class="text-primary hover:underline text-sm font-semibold">Edit</button>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforeach
            </x-ui.table>
        </x-ui.card>
        @endif

        @if($activeTab === 'tables')
        <!-- Tables Tab -->
        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800">Daftar Meja</h3>
                <x-ui.button wire:click="openTableModal">Tambah Meja</x-ui.button>
            </div>
            <x-ui.table :headers="['Nomor/Nama Meja', 'Status', 'Aksi']">
                @foreach($tables as $table)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $table->number }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge :variant="$table->status === 'available' ? 'success' : 'warning'">
                            {{ $table->status === 'available' ? 'Tersedia' : 'Terisi' }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <button wire:click="openTableModal({{ $table->id }})" class="text-primary hover:underline text-sm font-semibold">Edit</button>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforeach
            </x-ui.table>
        </x-ui.card>
        @endif

    </x-ui.section>

    <!-- Category Modal -->
    <x-modal wire:model.live="showCategoryModal" maxWidth="sm" :show="$showCategoryModal">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Form Kategori</h2>
            <form wire:submit="saveCategory" class="space-y-4">
                <div>
                    <x-input-label for="categoryName" value="Nama Kategori" />
                    <x-text-input id="categoryName" type="text" class="mt-1 block w-full" wire:model="categoryName" />
                    <x-input-error :messages="$errors->get('categoryName')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="categoryDesc" value="Deskripsi" />
                    <textarea id="categoryDesc" wire:model="categoryDesc" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm text-sm" rows="3"></textarea>
                </div>
                <div class="flex justify-end pt-4 gap-3">
                    <button type="button" wire:click="$set('showCategoryModal', false)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-sm font-semibold text-xs">Batal</button>
                    <x-ui.button type="submit">Simpan</x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Table Modal -->
    <x-modal wire:model.live="showTableModal" maxWidth="sm" :show="$showTableModal">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Form Meja</h2>
            <form wire:submit="saveTable" class="space-y-4">
                <div>
                    <x-input-label for="tableNumber" value="Nomor / Identitas Meja" />
                    <x-text-input id="tableNumber" type="text" class="mt-1 block w-full" wire:model="tableNumber" />
                    <x-input-error :messages="$errors->get('tableNumber')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tableStatus" value="Status Saat Ini" />
                    <select id="tableStatus" wire:model="tableStatus" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm text-sm">
                        <option value="available">Tersedia (Kosong)</option>
                        <option value="occupied">Terisi (Digunakan)</option>
                    </select>
                </div>
                <div class="flex justify-end pt-4 gap-3">
                    <button type="button" wire:click="$set('showTableModal', false)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-sm font-semibold text-xs">Batal</button>
                    <x-ui.button type="submit">Simpan</x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>