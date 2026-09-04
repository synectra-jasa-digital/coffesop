<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-ui.heading level="2" class="text-xl">
                {{ __('Supplier') }}
            </x-ui.heading>
            <x-ui.button wire:click="create">Supplier Baru</x-ui.button>
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
            <x-ui.table :headers="['Nama', 'Kontak', 'Telepon', 'Email', 'Aksi']">
                @forelse($suppliers as $supplier)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $supplier->name }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $supplier->contact_name ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $supplier->phone ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $supplier->email ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex gap-3">
                            <button wire:click="edit({{ $supplier->id }})" class="text-primary hover:underline text-sm font-semibold">Edit</button>
                            <button wire:click="delete({{ $supplier->id }})" class="text-red-600 hover:underline text-sm font-semibold" onclick="return confirm('Hapus supplier ini?')">Hapus</button>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center text-gray-500">Tidak ada data supplier.</x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table>

            <div class="p-4 border-t border-gray-100">
                {{ $suppliers->links() }}
            </div>
        </x-ui.card>
    </x-ui.section>

    <!-- Supplier Modal -->
    <x-modal wire:model.live="showModal" maxWidth="md" :show="$showModal">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">
                {{ $isEditing ? 'Edit Supplier' : 'Supplier Baru' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <x-input-label for="name" value="Nama Supplier" />
                    <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="contactName" value="Nama Kontak (Opsional)" />
                    <x-text-input id="contactName" type="text" class="mt-1 block w-full" wire:model="contactName" />
                    <x-input-error :messages="$errors->get('contactName')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="phone" value="Telepon (Opsional)" />
                        <x-text-input id="phone" type="text" class="mt-1 block w-full" wire:model="phone" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="email" value="Email (Opsional)" />
                        <x-text-input id="email" type="email" class="mt-1 block w-full" wire:model="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="address" value="alamat (Opsional)" />
                    <textarea id="address" wire:model="address" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm text-sm" rows="3"></textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
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
</div>
