<div>
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="mb-6 p-4 bg-primary-light border border-primary/20 text-primary rounded-sm flex justify-between items-center gap-4" x-init="setTimeout(() => show = false, 5000)">
            <span class="text-sm font-semibold">{{ session('message') }}</span>
            <button @click="show = false" class="text-primary hover:text-primary-hover p-1 shrink-0" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @hasanyrole('Manager/Supervisor|Owner/Admin')
        <div class="bg-white border border-line rounded-sm p-6 hover:border-primary transition-colors duration-200">
            <div class="text-sm text-gray-400 mb-2">Total Penjualan Hari Ini</div>
            <h3 class="font-bold text-2xl md:text-3xl leading-[1.3] text-primary">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white border border-line rounded-sm p-6 hover:border-primary transition-colors duration-200">
            <div class="text-sm text-gray-400 mb-2">Total Transaksi</div>
            <h3 class="font-bold text-2xl md:text-3xl leading-[1.3] text-ink">{{ number_format($totalTransactions, 0, ',', '.') }}</h3>
        </div>
        @endhasanyrole

        @hasanyrole('Barista/Gudang|Manager/Supervisor|Owner/Admin')
        <div class="bg-white border rounded-sm p-6 transition-colors duration-200 {{ $criticalStockCount > 0 ? 'border-red-300 bg-red-50/30 hover:border-red-400' : 'border-line hover:border-primary' }}">
            <div class="text-sm text-gray-400 mb-2">Stok Kritis (Bahan Baku)</div>
            <h3 class="font-bold text-2xl md:text-3xl leading-[1.3] {{ $criticalStockCount > 0 ? 'text-red-600' : 'text-ink' }}">{{ $criticalStockCount }} Item</h3>
        </div>
        @endhasanyrole

        @hasanyrole('Kasir|Manager/Supervisor|Owner/Admin')
        <div class="bg-white border border-line rounded-sm p-6 hover:border-primary transition-colors duration-200">
            <div class="text-sm text-gray-400 mb-2">Status Kasir</div>
            <h3 class="font-bold text-2xl md:text-3xl leading-[1.3] {{ $activeShift ? 'text-primary' : 'text-gray-400' }}">
                {{ $activeShift ? 'Shift Aktif' : 'Tutup' }}
            </h3>
            @if($activeShift)
                <div class="text-xs text-gray-400 mt-2 flex items-center gap-1.5">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-pulse-dot absolute inline-flex h-full w-full rounded-full bg-green-500 opacity-50"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-600"></span>
                    </span>
                    {{ $activeShift->user->name ?? 'Kasir' }}
                </div>
            @endif
        </div>
        @endhasanyrole
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @hasanyrole('Manager/Supervisor|Owner/Admin')
        <div class="bg-white border border-line rounded-sm p-6">
            <h4 class="font-bold text-lg md:text-xl leading-[1.4] text-ink mb-5">Menu Terlaris Hari Ini</h4>
            @if($topSelling->isEmpty())
                <div class="text-gray-400 text-sm text-center py-10">Belum ada data transaksi hari ini.</div>
            @else
                <div class="space-y-4">
                    @foreach($topSelling as $index => $item)
                        <div class="flex items-center justify-between gap-4 {{ !$loop->last ? 'pb-4 border-b border-line' : '' }}">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="text-primary font-bold text-lg w-6 shrink-0">{{ $index + 1 }}</span>
                                <span class="font-medium text-ink truncate">{{ $item->name }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-400 shrink-0">{{ $item->total_qty }} terjual</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endhasanyrole

        <div class="bg-white border border-line rounded-sm p-6 {{ auth()->user()->hasRole('Kasir') || auth()->user()->hasRole('Barista/Gudang') ? 'md:col-span-2' : '' }}">
            <h4 class="font-bold text-lg md:text-xl leading-[1.4] text-ink mb-5">Akses Cepat</h4>
            <div class="flex flex-col sm:flex-row gap-3">
                @hasanyrole('Kasir|Manager/Supervisor|Owner/Admin')
                <a href="{{ route('pos.index') }}" wire:navigate class="inline-flex flex-1 items-center justify-center gap-2 font-semibold rounded-sm bg-primary hover:bg-primary-hover text-white px-6 py-3 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21h.75c.621 0 1.125-.504 1.125-1.125V5.372c0-.621-.504-1.125-1.125-1.125h-1.5c-.621 0-1.125.504-1.125 1.125v14.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    Buka Kasir
                </a>
                @endhasanyrole

                @hasanyrole('Barista/Gudang|Manager/Supervisor|Owner/Admin')
                <a href="{{ route('kds.index') }}" wire:navigate class="inline-flex flex-1 items-center justify-center gap-2 font-semibold rounded-sm bg-transparent border border-primary text-primary hover:bg-primary hover:text-white px-6 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                    </svg>
                    Buka Dapur
                </a>
                @endhasanyrole
            </div>
        </div>
    </div>
</div>
