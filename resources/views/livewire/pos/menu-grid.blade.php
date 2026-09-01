<div class="h-full flex flex-col">
    <!-- Categories Tab -->
    <div class="h-16 border-b border-gray-200 bg-white flex items-center px-6 overflow-x-auto gap-4 hide-scrollbar flex-shrink-0">
        @foreach($categories as $category)
        <button 
            wire:click="filterCategory('{{ $category }}')"
            class="whitespace-nowrap px-4 py-2 font-medium rounded-sm {{ $activeCategory === $category ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            {{ $category }}
        </button>
        @endforeach
    </div>

    <!-- Menu Grid -->
    <div class="flex-1 overflow-y-auto p-6">
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($filteredProducts as $product)
            <div wire:click="addToCart({{ $product['id'] }})" class="bg-white border border-gray-100 rounded-sm overflow-hidden cursor-pointer hover:border-primary hover:shadow-sm transition-all group">
                <div class="aspect-square bg-gray-100 relative">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                </div>
                <div class="p-3">
                    <div class="font-semibold text-gray-800 text-sm mb-1 truncate group-hover:text-primary">{{ $product['name'] }}</div>
                    <div class="text-primary font-bold text-sm">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
