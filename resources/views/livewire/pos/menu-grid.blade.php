<div class="h-full flex flex-col">
    <!-- Categories Tab -->
    <div class="h-16 border-b border-gray-200 bg-white flex items-center px-6 overflow-x-auto gap-4 hide-scrollbar flex-shrink-0">
        @foreach($categories as $category)
        <button 
            wire:click="filterCategory('{{ $category }}')"
            class="whitespace-nowrap px-4 py-2 font-medium rounded-sm {{ $activeCategory === $category ? 'bg-[#398263] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            {{ $category }}
        </button>
        @endforeach
    </div>

    <!-- Menu Grid -->
    <div class="flex-1 overflow-y-auto p-6">
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($filteredProducts as $product)
            <div wire:click="addToCart({{ $product->id }})" class="bg-white border border-gray-100 rounded-sm overflow-hidden cursor-pointer hover:border-[#398263] hover:shadow-sm transition-all group relative {{ $product->out_of_stock ? 'opacity-50 pointer-events-none' : '' }}">
                <div class="aspect-square bg-gray-100 relative">
                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=300&auto=format&fit=crop' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @if($product->out_of_stock)
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="text-white font-bold px-2 py-1 bg-red-600 rounded-sm text-sm">Habis</span>
                        </div>
                    @endif
                </div>
                <div class="p-3">
                    <div class="font-semibold text-gray-800 text-sm mb-1 truncate group-hover:text-[#398263]">{{ $product->name }}</div>
                    <div class="text-[#398263] font-bold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($filteredProducts->isEmpty())
            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 mb-2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p>Tidak ada produk di kategori ini</p>
            </div>
        @endif
    </div>
</div>
