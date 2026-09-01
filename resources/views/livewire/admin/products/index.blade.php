<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-ui.heading level="2" class="text-xl">
                {{ __('Manajemen Produk & Menu') }}
            </x-ui.heading>
            <x-ui.button>Tambah Produk</x-ui.button>
        </div>
    </x-slot>

    <x-ui.section spacing="py-12">
        <x-ui.card>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-3 px-4 font-semibold text-sm">Produk</th>
                            <th class="py-3 px-4 font-semibold text-sm">Kategori</th>
                            <th class="py-3 px-4 font-semibold text-sm">Harga Dasar</th>
                            <th class="py-3 px-4 font-semibold text-sm">Status</th>
                            <th class="py-3 px-4 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-sm"></div>
                                    <span class="font-medium">Cafe Latte</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">Kopi</td>
                            <td class="py-3 px-4">Rp 28.000</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-sm bg-green-100 text-green-800">Aktif</span>
                            </td>
                            <td class="py-3 px-4">
                                <button class="text-primary hover:underline text-sm font-semibold">Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </x-ui.section>
</x-app-layout>
