<div class="h-full flex flex-col" x-data="{ activeCategory: null }" @keydown.escape.window="activeCategory = null">
    <!-- Categories Tab (mobile scroll) -->
    <div class="h-16 border-b border-line bg-white flex items-center px-4 overflow-x-auto gap-2 hide-scrollbar shrink-0 touch-pan-x">
        @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category }}'" :class="{ 'bg-primary text-white font-medium': activeCategory === '{{ $category }}', 'text-ink-secondary bg-surface-alt hover:bg-line-light': activeCategory !== '{{ $category }}' }" class="whitespace-nowrap px-5 py-2.5 rounded-full text-sm transition-all focus-flat select-none">
                {{ $category }}
            </button>
        @endforeach
    </div>

    <!-- Menu Grid -->
    <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-surface-alt">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-5">
            @foreach($filteredProducts as $product)
                <div @click="$wire.addToCart({{ $product->id }})" class="bg-white border border-line rounded-lg overflow-hidden cursor-pointer hover:border-primary/50 hover:shadow-[0_4px_20px_-4px_rgba(57,130,99,0.15)] transition-all duration-300 group relative {{ $product->out_of_stock ? 'opacity-60 grayscale-[50%] pointer-events-none' : '' }} animate-fade-in-up flex flex-col h-full"
                    style="animation-delay: {{ $loop->index * 30 }}ms"
                >
                    <div class="aspect-square bg-line-light relative overflow-hidden">
                        <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=300&auto=format&fit=crop' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                        @if($product->out_of_stock)
                            <div class="absolute inset-0 bg-ink/30 backdrop-blur-[2px] flex items-center justify-center">
                                <span class="text-white font-medium px-3 py-1.5 bg-ink/80 rounded-full text-xs tracking-wide uppercase">Habis</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-3 sm:p-4 flex flex-col flex-1 justify-between gap-1">
                        <div class="font-medium text-ink text-sm sm:text-base leading-tight line-clamp-2 group-hover:text-primary transition-colors">{{ $product->name }}</div>
                        <div class="text-primary font-bold text-sm sm:text-base mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($filteredProducts->isEmpty())
            <div class="flex flex-col items-center justify-center h-full min-h-[50vh] text-ink-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-16 mb-4 text-line">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="mt-2 text-sm font-medium">Tidak ada produk di kategori ini.</p>
            </div>
        @endif
    </div>
</div>
