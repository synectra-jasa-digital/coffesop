<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-sm border border-gray-100">
                    <div class="text-sm text-gray-500 mb-1">Total Penjualan Hari Ini</div>
                    <h3 class="font-serif font-bold text-2xl md:text-3xl leading-[1.3] text-[#1A1A1A]">Rp 0</h3>
                </div>
                <div class="bg-white p-6 rounded-sm border border-gray-100">
                    <div class="text-sm text-gray-500 mb-1">Total Transaksi</div>
                    <h3 class="font-serif font-bold text-2xl md:text-3xl leading-[1.3] text-[#1A1A1A]">0</h3>
                </div>
                <div class="bg-white p-6 rounded-sm border border-gray-100">
                    <div class="text-sm text-gray-500 mb-1">Stok Kritis</div>
                    <h3 class="font-serif font-bold text-2xl md:text-3xl leading-[1.3] text-red-600">0 Item</h3>
                </div>
                <div class="bg-white p-6 rounded-sm border border-gray-100">
                    <div class="text-sm text-gray-500 mb-1">Shift Aktif</div>
                    <h3 class="font-serif font-bold text-2xl md:text-3xl leading-[1.3] text-[#1A1A1A]">Tutup</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-sm border border-gray-100">
                    <h4 class="font-serif font-bold text-xl md:text-2xl leading-[1.4] text-[#1A1A1A] mb-4">Menu Terlaris</h4>
                    <div class="text-gray-500 text-sm text-center py-8">Belum ada data transaksi hari ini.</div>
                </div>
                <div class="bg-white p-6 rounded-sm border border-gray-100">
                    <h4 class="font-serif font-bold text-xl md:text-2xl leading-[1.4] text-[#1A1A1A] mb-4">Akses Cepat</h4>
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('pos.index') }}" class="inline-flex items-center justify-start font-semibold rounded-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-[#398263] hover:bg-[#2C6B4F] text-white px-8 py-3 w-full">
                            Buka Layar Kasir (POS) &rarr;
                        </a>
                        <a href="{{ route('kds.index') }}" class="inline-flex items-center justify-start font-semibold rounded-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-transparent border border-[#398263] text-[#398263] hover:bg-[#398263] hover:text-white px-8 py-3 w-full">
                            Buka Kitchen Display (KDS) &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
