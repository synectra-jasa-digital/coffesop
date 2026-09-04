<div wire:poll.10s>
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
        <div class="bg-white border border-line rounded-lg p-6 hover:border-primary/40 hover:shadow-[0_4px_20px_-4px_rgba(57,130,99,0.1)] transition-all duration-300">
            <div class="text-sm font-medium text-ink-secondary mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                Total Penjualan Hari Ini
            </div>
            <h3 class="font-black text-2xl md:text-3xl leading-tight text-primary font-serif">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white border border-line rounded-lg p-6 hover:border-ink/20 hover:shadow-sm transition-all duration-300">
            <div class="text-sm font-medium text-ink-secondary mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" /></svg>
                Total Transaksi
            </div>
            <h3 class="font-black text-2xl md:text-3xl leading-tight text-ink font-serif">{{ number_format($totalTransactions, 0, ',', '.') }}</h3>
        </div>
        @endhasanyrole

        @hasanyrole('Barista/Gudang|Manager/Supervisor|Owner/Admin')
        <div class="bg-white border rounded-lg p-6 transition-all duration-300 {{ $criticalStockCount > 0 ? 'border-red-300 bg-red-50 hover:shadow-[0_4px_20px_-4px_rgba(239,68,68,0.15)]' : 'border-line hover:border-ink/20 hover:shadow-sm' }}">
            <div class="text-sm font-medium {{ $criticalStockCount > 0 ? 'text-red-700' : 'text-ink-secondary' }} mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                Stok Kritis (Bahan Baku)
            </div>
            <h3 class="font-black text-2xl md:text-3xl leading-tight {{ $criticalStockCount > 0 ? 'text-red-600' : 'text-ink' }} font-serif">{{ $criticalStockCount }} <span class="text-lg font-bold">Item</span></h3>
        </div>
        @endhasanyrole

        @hasanyrole('Kasir|Manager/Supervisor|Owner/Admin')
        <div class="bg-white border border-line rounded-lg p-6 hover:border-primary/40 hover:shadow-[0_4px_20px_-4px_rgba(57,130,99,0.1)] transition-all duration-300 relative overflow-hidden">
            <div class="text-sm font-medium text-ink-secondary mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                Status Kasir
            </div>
            <h3 class="font-black text-2xl md:text-3xl leading-tight font-serif {{ $activeShift ? 'text-primary' : 'text-ink-secondary/60' }}">
                {{ $activeShift ? 'Shift Aktif' : 'Tutup' }}
            </h3>
            @if($activeShift)
                <div class="text-xs font-semibold text-ink-secondary mt-2 flex items-center gap-2 bg-surface-alt py-1 px-2.5 rounded border border-line w-fit">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-pulse absolute inline-flex h-full w-full rounded-full bg-green-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-600"></span>
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
