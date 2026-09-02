<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <x-ui.heading level="2" class="text-xl">
                {{ __('Laporan & Analitik') }}
            </x-ui.heading>
            <div class="flex flex-wrap items-center gap-2">
                <select wire:model.live="reportType" class="border-gray-300 rounded-sm text-sm focus:border-[#398263] focus:ring-[#398263] py-2 pl-3 pr-8">
                    <option value="sales_daily">Penjualan Harian</option>
                    <option value="sales_period">Penjualan Periode</option>
                    <option value="stock">Laporan Stok</option>
                </select>
                
                @if($reportType === 'sales_daily')
                    <input type="date" wire:model.live="dateFilter" class="border-gray-300 rounded-sm text-sm focus:border-[#398263] focus:ring-[#398263] py-2 px-3">
                @endif

                @if($reportType === 'sales_period')
                    <div class="flex items-center gap-2">
                        <input type="date" wire:model.live="startDate" class="border-gray-300 rounded-sm text-sm focus:border-[#398263] focus:ring-[#398263] py-2 px-3 w-36">
                        <span class="text-gray-500 text-sm">s/d</span>
                        <input type="date" wire:model.live="endDate" class="border-gray-300 rounded-sm text-sm focus:border-[#398263] focus:ring-[#398263] py-2 px-3 w-36">
                    </div>
                @endif
                
                <x-ui.button variant="outline" onclick="window.print()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Cetak PDF
                </x-ui.button>
            </div>
        </div>
    </x-slot>

    <x-ui.section spacing="py-6">
        @if($reportType === 'sales_daily' || $reportType === 'sales_period')
            <!-- Sales Report -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-ui.card class="hover:border-[#398263] transition-colors">
                    <div class="text-gray-500 text-sm mb-1">Total Pendapatan</div>
                    <div class="text-3xl font-bold text-[#398263]">Rp {{ number_format($salesData['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                </x-ui.card>
                <x-ui.card>
                    <div class="text-gray-500 text-sm mb-1">Total Transaksi Selesai</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($salesData['total_transactions'] ?? 0, 0, ',', '.') }}</div>
                </x-ui.card>
                <x-ui.card>
                    <div class="text-gray-500 text-sm mb-1">Rata-rata Transaksi</div>
                    <div class="text-3xl font-bold text-gray-800">Rp {{ number_format($salesData['avg_transaction'] ?? 0, 0, ',', '.') }}</div>
                </x-ui.card>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <x-ui.card padding="p-0" class="overflow-hidden h-full">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="font-bold text-gray-800 text-lg">Menu Terlaris</h3>
                        </div>
                        <x-ui.table :headers="['Peringkat', 'Nama Menu', 'Total Terjual', 'Pendapatan']">
                            @forelse($topProducts as $index => $prod)
                                <x-ui.table-row>
                                    <x-ui.table-cell class="font-bold text-[#398263]">#{{ $index + 1 }}</x-ui.table-cell>
                                    <x-ui.table-cell class="font-medium">{{ $prod->name }}</x-ui.table-cell>
                                    <x-ui.table-cell>{{ $prod->total_qty }}</x-ui.table-cell>
                                    <x-ui.table-cell>Rp {{ number_format($prod->total_revenue, 0, ',', '.') }}</x-ui.table-cell>
                                </x-ui.table-row>
                            @empty
                                <x-ui.table-row>
                                    <x-ui.table-cell colspan="4" class="text-center text-gray-500 py-8">Belum ada data penjualan pada periode ini.</x-ui.table-cell>
                                </x-ui.table-row>
                            @endforelse
                        </x-ui.table>
                    </x-ui.card>
                </div>
                
                <div class="lg:col-span-1">
                    <x-ui.card class="h-full flex flex-col justify-center items-center text-center p-8 bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-16 mb-4 text-gray-300">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                        <h4 class="font-bold text-gray-700 mb-2">Grafik Visual</h4>
                        <p class="text-sm text-gray-500">Integrasi modul grafik/chart khusus (Chart.js / ApexCharts) dapat ditambahkan di area ini pada fase pengembangan berikutnya.</p>
                    </x-ui.card>
                </div>
            </div>
            
        @elseif($reportType === 'stock')
            <!-- Stock Report -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <x-ui.card>
                    <div class="text-gray-500 text-sm mb-1">Total Jenis Bahan Baku</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($stockData['total_items'] ?? 0, 0, ',', '.') }}</div>
                </x-ui.card>
                <x-ui.card class="{{ ($stockData['critical_items'] ?? 0) > 0 ? 'border-red-200 bg-red-50/50' : '' }}">
                    <div class="{{ ($stockData['critical_items'] ?? 0) > 0 ? 'text-red-600 font-bold' : 'text-gray-500' }} text-sm mb-1">Item Kritis (Butuh Restock)</div>
                    <div class="text-3xl font-bold {{ ($stockData['critical_items'] ?? 0) > 0 ? 'text-red-700' : 'text-gray-800' }}">{{ number_format($stockData['critical_items'] ?? 0, 0, ',', '.') }}</div>
                </x-ui.card>
            </div>
            
            <x-ui.card padding="p-0" class="overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800 text-lg">Valuasi & Status Stok Fisik</h3>
                </div>
                <x-ui.table :headers="['Bahan Baku', 'Stok Sistem', 'Batas Minimum', 'Satuan', 'Status Keamanan']">
                    @forelse($stockData['ingredients'] ?? [] as $ingredient)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $ingredient->name }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <span class="{{ $ingredient->current_stock <= $ingredient->minimum_stock ? 'text-red-600 font-bold' : '' }}">
                                    {{ number_format($ingredient->current_stock, 0, ',', '.') }}
                                </span>
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ number_format($ingredient->minimum_stock, 0, ',', '.') }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $ingredient->unit }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                @if($ingredient->current_stock <= $ingredient->minimum_stock)
                                    <x-ui.badge variant="danger">Kritis</x-ui.badge>
                                @else
                                    <x-ui.badge variant="success">Aman</x-ui.badge>
                                @endif
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="5" class="text-center text-gray-500 py-8">Tidak ada data bahan baku.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table>
            </x-ui.card>
        @endif
    </x-ui.section>
</x-app-layout>