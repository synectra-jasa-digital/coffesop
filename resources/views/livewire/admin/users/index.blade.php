<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-ui.heading level="2" class="text-xl">
                {{ __('Manajemen Pengguna') }}
            </x-ui.heading>
            <x-ui.button wire:click="create">Tambah Pengguna</x-ui.button>
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
            <x-ui.table :headers="['Nama', 'Email', 'Role (Akses)', 'Status', 'Aksi']">
                @foreach($users as $user)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $user->name }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $user->email }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @php
                            $role = $user->roles->first();
                            $roleName = $role ? $role->name : 'Tanpa Role';
                            $variant = 'default';
                            if(str_contains($roleName, 'Owner')) $variant = 'danger';
                            elseif(str_contains($roleName, 'Manager')) $variant = 'warning';
                            elseif(str_contains($roleName, 'Kasir')) $variant = 'primary';
                            elseif(str_contains($roleName, 'Barista')) $variant = 'success';
                        @endphp
                        <x-ui.badge :variant="$variant">{{ $roleName }}</x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($user->is_active)
                            <x-ui.badge variant="success">Aktif</x-ui.badge>
                        @else
                            <x-ui.badge variant="default">Nonaktif</x-ui.badge>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex gap-3">
                            <button wire:click="edit({{ $user->id }})" class="text-primary hover:underline text-sm font-semibold">Edit</button>
                            @if($user->id !== auth()->id())
                                <button wire:click="toggleStatus({{ $user->id }})" class="text-blue-600 hover:underline text-sm font-semibold">
                                    {{ $user->is_active ? 'Nonaktif' : 'Aktif' }}
                                </button>
                            @endif
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforeach
            </x-ui.table>
        </x-ui.card>
    </x-ui.section>

    <!-- User Modal -->
    <x-modal wire:model.live="showModal" maxWidth="md" :show="$showModal">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">
                {{ $isEditing ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <x-input-label for="name" value="Nama Lengkap" />
                    <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" type="email" class="mt-1 block w-full" wire:model="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="role_id" value="Hak Akses (Role)" />
                    <select id="role_id" wire:model="role_id" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm">
                        <option value="">Pilih Role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <x-input-label for="password" value="{{ $isEditing ? 'Password Baru (Kosongkan jika tidak diubah)' : 'Password' }}" />
                    <x-text-input id="password" type="password" class="mt-1 block w-full" wire:model="password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                    <x-text-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="password_confirmation" />
                </div>

                <div class="flex items-center mt-2">
                    <input id="is_active" type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-[#398263] shadow-sm focus:ring-[#398263]">
                    <label for="is_active" class="ml-2 text-sm text-gray-600">Akun Aktif (bisa login)</label>
                </div>

                <div class="flex justify-end mt-6 gap-3 pt-4">
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