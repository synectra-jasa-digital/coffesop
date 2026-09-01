<div class="h-full flex flex-col bg-white">
    <!-- Order Header -->
    <div class="h-16 border-b border-gray-200 flex items-center justify-between px-4">
        <div class="font-bold text-lg">Pesanan Baru</div>
        <button wire:click="clearCart" class="text-red-500 hover:text-red-700 text-sm font-semibold">Kosongkan</button>
    </div>

    <!-- Order Type / Table -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex bg-gray-100 p-1 rounded-sm">
            <button wire:click="$set('orderType', 'dine-in')" class="flex-1 py-1.5 {{ $orderType === 'dine-in' ? 'bg-white shadow-sm' : 'text-gray-500' }} rounded-sm text-sm font-semibold">Dine In</button>
            <button wire:click="$set('orderType', 'take-away')" class="flex-1 py-1.5 {{ $orderType === 'take-away' ? 'bg-white shadow-sm' : 'text-gray-500' }} rounded-sm text-sm font-semibold">Take Away</button>
        </div>
        
        @if($orderType === 'dine-in')
        <div class="mt-3">
            <select wire:model="tableId" class="w-full border-gray-300 rounded-sm text-sm focus:border-primary focus:ring-primary">
                <option value="">Pilih Meja...</option>
                <option value="1">Meja 01</option>
                <option value="2">Meja 02</option>
                <option value="3">Meja 03</option>
            </select>
        </div>
        @endif
    </div>

    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#FAFAFA]">
        @forelse($items as $index => $item)
        <div class="bg-white p-3 rounded-sm border border-gray-100 flex gap-3">
            <div class="w-12 h-12 bg-gray-100 rounded-sm overflow-hidden flex-shrink-0">
                <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <div class="font-semibold text-sm">{{ $item['name'] }}</div>
                    <div class="font-bold text-sm">{{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>
                <div class="text-xs text-gray-500 mb-2">{{ $item['notes'] }}</div>
                <div class="flex justify-between items-center mt-2">
                    <button class="text-xs text-primary font-semibold">Edit Note</button>
                    <div class="flex items-center gap-3">
                        <button wire:click="decrementQuantity({{ $index }})" class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200">-</button>
                        <span class="text-sm font-semibold">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity({{ $index }})" class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white hover:bg-[#2C6B4F]">+</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center h-full text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 mb-2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
            <p>Belum ada pesanan</p>
        </div>
        @endforelse
    </div>

    <!-- Checkout Summary -->
    <div class="p-4 border-t border-gray-200 bg-white">
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">PPN (11%)</span>
                <span class="font-medium">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
            </div>
            @if($discountAmount > 0)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Diskon</span>
                <span class="font-medium text-green-600">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="pt-2 border-t border-gray-100 flex justify-between items-center">
                <span class="font-bold text-lg">Total</span>
                <span class="font-bold text-xl text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <button class="w-full bg-primary hover:bg-[#2C6B4F] text-white font-bold py-4 rounded-sm transition-colors text-lg flex justify-between px-6 {{ count($items) === 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ count($items) === 0 ? 'disabled' : '' }}>
            <span>Bayar</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </button>
    </div>
</div>
