<div class="min-h-screen bg-gray-50" x-data="{ showCart: false }">
    <!-- Header -->
    <header class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between">
            <div>
                <h1 class="font-serif font-bold text-xl text-[#398263]">Good Coffee.</h1>
                <p class="text-xs text-gray-500">Meja {{ $tableNumber }} • Pesan Langsung</p>
            </div>
            <button x-on:click="showCart = !showCart" class="relative bg-[#398263] text-white rounded-full w-11 h-11 flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                @if($this->getCartCountProperty() > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $this->getCartCountProperty() }}</span>
                @endif
            </button>
        </div>
    </header>

    @if($orderPlaced)
        <div class="max-w-2xl mx-auto p-6">
            <div class="bg-white rounded-sm border border-gray-200 p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-8 text-green-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <h2 class="font-serif font-bold text-2xl text-gray-900 mb-2">Pesanan Diterima</h2>
                <p class="text-sm text-gray-600 mb-4">Pesanan Anda sudah diteruskan ke dapur dan barista kami.</p>
                <div class="bg-gray-50 rounded-sm p-4 inline-block">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">No. Order</p>
                    <p class="font-bold text-2xl text-[#398263] font-mono">{{ $orderNumber }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-6">Mohon menunggu di meja. Pembayaran dilakukan ke kasir.</p>
            </div>
        </div>
    @else

    <!-- Categories Tabs -->
    <div class="sticky top-[68px] z-20 bg-white border-b border-gray-200">
        <div class="max-w-2xl mx-auto px-4 py-2 flex gap-2 overflow-x-auto">
            <button wire:click="$set('activeCategory', 'Semua')" class="px-3 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap {{ $activeCategory === 'Semua' ? 'bg-[#398263] text-white' : 'bg-gray-100 text-gray-700' }}">Semua</button>
            @foreach($categories as $cat)
                <button wire:click="$set('activeCategory', '{{ $cat->name }}')" class="px-3 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap {{ $activeCategory === $cat->name ? 'bg-[#398263] text-white' : 'bg-gray-100 text-gray-700' }}">{{ $cat->name }}</button>
            @endforeach
        </div>
    </div>

    <!-- Product Grid -->
    <div class="max-w-2xl mx-auto p-4 pb-32">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @php
                $filtered = $activeCategory === 'Semua' ? $products : $products->where('category.name', $activeCategory);
            @endphp
            @forelse($filtered as $product)
            <button wire:click="addToCart({{ $product->id }})" class="bg-white rounded-sm border border-gray-200 overflow-hidden text-left hover:border-[#398263] hover:shadow-md transition-all">
                <div class="aspect-square bg-gray-100 overflow-hidden">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                        </div>
                    @endif
                </div>
                <div class="p-2">
                    <p class="text-xs font-medium text-gray-800 line-clamp-2 leading-tight">{{ $product->name }}</p>
                    <p class="text-sm font-bold text-[#398263] mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </button>
            @empty
            <p class="col-span-3 text-center text-gray-400 py-8">Tidak ada menu tersedia.</p>
            @endforelse
        </div>
    </div>

    <!-- Cart Drawer -->
    <div x-show="showCart" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-black/50 z-40" x-on:click="showCart = false"></div>
    <div x-show="showCart" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="fixed top-0 right-0 bottom-0 w-full max-w-md bg-white z-50 flex flex-col shadow-2xl">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-serif font-bold text-lg">Keranjang</h3>
            <button x-on:click="showCart = false" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @forelse($cart as $index => $item)
            <div class="flex gap-3 border-b border-gray-100 pb-3">
                <div class="w-16 h-16 bg-gray-100 rounded-sm overflow-hidden flex-shrink-0">
                    @if($item['image'])
                        <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $item['name'] }}</p>
                    <p class="text-xs text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    <input type="text" wire:model.blur="cart.{{ $index }}.notes" placeholder="Catatan (opsional)" class="mt-1 w-full text-xs px-2 py-1 border border-gray-200 rounded-sm">
                </div>
                <div class="flex flex-col items-end gap-1">
                    <div class="flex items-center bg-gray-100 rounded-sm">
                        <button wire:click="decrementQuantity({{ $index }})" class="w-6 h-6 text-sm">-</button>
                        <span class="w-6 text-center text-xs font-semibold">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity({{ $index }})" class="w-6 h-6 text-sm bg-[#398263] text-white">+</button>
                    </div>
                    <p class="text-xs font-bold text-[#398263]">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-400 py-8 text-sm">Belum ada pesanan.</p>
            @endforelse
        </div>

        <div class="p-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between mb-1 text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-medium">Rp {{ number_format($this->getSubtotalProperty(), 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between mb-3 text-base">
                <span class="font-bold">Total</span>
                <span class="font-bold text-[#398263]">Rp {{ number_format($this->getTotalProperty(), 0, ',', '.') }}</span>
            </div>
            <button wire:click="placeOrder" x-on:click="showCart = false" class="w-full bg-[#398263] hover:bg-[#2C6B4F] text-white font-bold py-3 rounded-sm disabled:opacity-50" @disabled(empty($cart))>
                Kirim Pesanan
            </button>
        </div>
    </div>

    @endif
</div>