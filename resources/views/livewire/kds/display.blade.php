<div class="h-full flex flex-col bg-gray-100" wire:poll.5s>
    <!-- Header -->
    <div class="h-16 bg-white border-b border-line flex items-center justify-between px-6 shrink-0 shadow-sm z-10 relative">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-ink-secondary hover:text-primary transition-colors focus-flat p-1 rounded-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div class="font-bold text-xl text-primary font-serif tracking-tight">Kitchen Display</div>
        </div>
        <div class="flex gap-5">
            <!-- Badge Baru -->
            <div class="flex items-center gap-2 bg-red-50 px-3 py-1.5 rounded-md border border-red-100">
                <div class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></div>
                <span class="text-sm font-bold text-red-700">Baru ({{ $newCount }})</span>
            </div>
            <!-- Badge Diproses -->
            <div class="flex items-center gap-2 bg-yellow-50 px-3 py-1.5 rounded-md border border-yellow-100">
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500"></div>
                <span class="text-sm font-bold text-yellow-700">Diproses ({{ $processingCount }})</span>
            </div>
        </div>
    </div>

    <!-- Order List -->
    <div class="flex-1 p-6 overflow-x-auto overflow-y-hidden bg-surface-alt relative">
        <div class="flex gap-6 h-full items-start">
            @forelse($activeOrders as $order)
                <!-- Ticket Card -->
                <div class="w-[340px] flex-shrink-0 flex flex-col max-h-full bg-white rounded-lg border-2 {{ $order->status === 'pending' ? 'border-red-500 shadow-[0_4px_20px_-4px_rgba(239,68,68,0.2)]' : 'border-yellow-500 shadow-[0_4px_20px_-4px_rgba(234,179,8,0.2)]' }} overflow-hidden animate-fade-in-up">
                    <!-- Header -->
                    <div class="p-4 {{ $order->status === 'pending' ? 'bg-red-50' : 'bg-yellow-50' }} flex justify-between items-center border-b border-line">
                        <div>
                            <div class="font-bold text-2xl tracking-tight text-ink">
                                {{ $order->order_number }}
                            </div>
                            <div class="text-sm font-semibold text-ink-secondary mt-1 flex items-center gap-1.5">
                                @if($order->type === 'dine-in')
                                    <span class="px-2 py-0.5 bg-white rounded border border-line text-ink shadow-sm">{{ $order->table ? 'Meja ' . $order->table->number : 'Meja -' }}</span>
                                @endif
                                <span>{{ ucfirst(str_replace('-', ' ', $order->type)) }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="{{ $order->status === 'pending' ? 'text-red-600' : 'text-yellow-700' }} font-black text-xl tabular-nums">
                                {{ $order->created_at->diffInMinutes(now()) }}<span class="text-xs font-bold ml-0.5">m</span>
                            </div>
                            <div class="text-xs font-semibold text-ink-secondary mt-1">
                                {{ $order->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="p-5 flex-1 overflow-y-auto space-y-5 hide-scrollbar">
                        @foreach($order->items as $item)
                            <div class="flex flex-col gap-1.5 pb-4 {{ !$loop->last ? 'border-b border-dashed border-line' : '' }}">
                                <div class="flex items-start gap-3">
                                    <div class="font-black text-xl {{ $order->status === 'pending' ? 'text-red-600' : 'text-yellow-600' }} mt-0.5">{{ $item->quantity }}x</div>
                                    <div class="font-bold text-lg text-ink leading-tight">
                                        {{ $item->product->name ?? 'Produk Dihapus' }}
                                    </div>
                                </div>
                                @if($item->notes)
                                    <div class="text-sm font-medium text-ink-secondary bg-surface-alt p-2.5 rounded border border-line ml-[2.25rem]">
                                        <span class="uppercase text-[10px] tracking-wider text-primary font-bold block mb-0.5">Catatan:</span>
                                        {{ $item->notes }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer Actions -->
                    <div class="p-4 border-t border-line bg-white flex gap-3 shrink-0">
                        @if($order->status === 'pending')
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'processing')" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-black text-lg py-4 rounded-md transition-colors shadow-sm active:scale-[0.98] focus-flat">
                                MULAI PROSES
                            </button>
                        @else
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'pending')" class="flex-[0.8] bg-white border-2 border-line hover:bg-surface-alt text-ink-secondary font-bold py-4 rounded-md transition-colors active:scale-[0.98] focus-flat">
                                BATAL
                            </button>
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'completed')" class="flex-[1.2] bg-primary hover:bg-primary-hover text-white font-black text-lg py-4 rounded-md transition-colors shadow-sm active:scale-[0.98] focus-flat">
                                SELESAI
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="w-full h-full flex flex-col items-center justify-center text-ink-secondary">
                    <div class="w-24 h-24 rounded-full bg-white border border-line flex items-center justify-center mb-6 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 text-line">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-ink">Tidak ada pesanan aktif</p>
                    <p class="text-base mt-2">Dapur kosong. Pesanan baru akan otomatis muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>