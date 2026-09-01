<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-ui.heading level="2" class="text-xl">
                {{ __('Manajemen Stok Bahan Baku') }}
            </x-ui.heading>
            <div class="flex gap-2">
                <x-ui.button variant="outline">Stok Masuk</x-ui.button>
                <x-ui.button>Stok Opname</x-ui.button>
            </div>
        </div>
    </x-slot>

    <x-ui.section spacing="py-12">
        <x-ui.card>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-3 px-4 font-semibold text-sm">Bahan Baku</th>
                            <th class="py-3 px-4 font-semibold text-sm">Stok Saat Ini</th>
                            <th class="py-3 px-4 font-semibold text-sm">Stok Minimum</th>
                            <th class="py-3 px-4 font-semibold text-sm">Satuan</th>
                            <th class="py-3 px-4 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 font-medium">Biji Kopi Arabica</td>
                            <td class="py-3 px-4">2.500</td>
                            <td class="py-3 px-4">500</td>
                            <td class="py-3 px-4">gram</td>
                            <td class="py-3 px-4">
                                <button class="text-primary hover:underline text-sm font-semibold">Riwayat</button>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 bg-red-50/50">
                            <td class="py-3 px-4 font-medium">Susu Fresh Milk</td>
                            <td class="py-3 px-4 text-red-600 font-bold">1.200</td>
                            <td class="py-3 px-4">2.000</td>
                            <td class="py-3 px-4">ml</td>
                            <td class="py-3 px-4">
                                <button class="text-primary hover:underline text-sm font-semibold">Riwayat</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </x-ui.section>
</x-app-layout>
