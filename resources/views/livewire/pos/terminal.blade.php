<div class="flex h-full w-full" x-data="posTerminal()">
    <!-- Left Panel: Menu Items (Full width mobile, 70% desktop) -->
    <div class="w-full lg:w-[70%] bg-gray-50 border-r border-line hidden lg:flex flex-col">
        <livewire:pos.menu-grid />
    </div>

    <!-- Mobile Menu - Full screen with bottom sheet cart -->
    <div class="lg:hidden flex flex-col h-full">
        <livewire:pos.menu-grid />
    </div>

    <!-- Right Panel: Cart & Checkout (Full width mobile, 30% desktop) -->
    <div x-show="isCartOpen || window.innerWidth >= 1024" x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="lg:w-[30%] lg:translate-x-0 fixed inset-y-0 right-0 z-50 w-full lg:static lg:relative bg-white border-l border-line flex flex-col">
        <livewire:pos.cart />
    </div>

    <!-- Mobile Cart Toggle Button -->
    <div class="lg:hidden fixed bottom-4 right-4 z-40" x-show="!isCartOpen">
        <button @click="openCart()" class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center hover:bg-primary-hover transition-colors animate-scale-in" aria-label="Buka keranjang">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-7">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
        </button>
    </div>

    <!-- Cart Overlay on Mobile -->
    <div x-show="isCartOpen" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="lg:hidden fixed inset-0 bg-black/40 z-40" @click="closeCart()"></div>
</div>

<script>
function posTerminal() {
    return {
        isCartOpen: false,
        openCart() {
            this.isCartOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeCart() {
            this.isCartOpen = false;
            document.body.style.overflow = '';
        }
    }
}
</script>