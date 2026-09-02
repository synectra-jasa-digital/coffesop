<div class="h-full flex flex-col bg-gray-100" x-data="{ refreshInterval: null }" wire:poll.10s>
    <!-- Header -->
    <div class="h-16 bg-white border-b border-line flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div class="font-serif font-bold text-xl text-primary">Kitchen Display</div>
        </div>
        <div class="flex gap-4">
            <!-- Badge Baru -->
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-red-500 animate-pulse-dot"></div>
                <span class="text-sm font-medium">Baru ({{ $newCount }})</span>
            </div>
            <!-- Badge Diproses -->
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-yellow-500 animate-pulse-dot"></div>
                <span class="text-sm font-medium">Diproses ({{ $processingCount }})</span>
            </div>
        </div>
    </div>

    <!-- Order List -->
    <div class="flex-1 p-6 overflow-x-auto overflow-y-hidden">
        <div class="flex gap-6 h-full items-start">
            @forelse($activeOrders as $order)
                <!-- Ticket Card -->
                <div class="w-80 flex-shrink-0 flex flex-col max-h-full bg-white rounded-sm border-t-4 {{ $order->status === 'pending' ? 'border-red-500' : 'border-yellow-500' }} shadow-sm overflow-hidden animate-fade-in-up">
                    <!-- Header -->
                    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <div>
                            <div class="font-bold text-lg">
                                {{ $order->order_number }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $order->type === 'dine-in' && $order->table ? $order->table->number . ' • ' : '' }}
                                {{ ucfirst(str_replace('-', ' ', $order->type)) }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="{{ $order->status === 'pending' ? 'text-red-500' : 'text-yellow-600' }} font-bold">
                                {{ $order->created_at->diffForHumans(null, true) }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $order->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="p-4 flex-1 overflow-y-auto space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold">
                                        {{ $item->quantity }}x {{ $item->product->name ?? 'Produk Dihapus' }}
                                    </div>
                                    @if($item->notes)
                                        <div class="text-sm text-gray-500 ml-4 border-l-2 border-gray-200 pl-2 mt-1">
                                            {{ $item->notes }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer Actions -->
                    <div class="p-4 border-t border-gray-100 bg-gray-50 flex gap-2">
                        @if($order->status === 'pending')
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'processing')" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-sm transition-colors">
                                Mulai Proses
                            </button>
                        @else
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'pending')" class="flex-1 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 rounded-sm transition-colors">
                                Batal
                            </button>
                            <button wire:click="updateOrderStatus({{ $order->id }}, 'completed')" class="flex-1 bg-primary hover:bg-primary-hover text-white font-bold py-3 rounded-sm transition-colors">
                                Selesai
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-16 mb-4 opacity-50">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    <p class="text-xl font-medium">Tidak ada pesanan aktif</p>
                    <p class="text-sm mt-2">Pesanan baru akan muncul di sini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
