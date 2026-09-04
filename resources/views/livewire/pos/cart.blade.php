<div class="h-full flex flex-col bg-white relative" x-data="{ printReceipt: false }" @print-receipt.window="
        const printWindow = window.open(`/pos/receipt/${$event.detail.order_id}`, '_blank');
        if (printWindow) {
            printWindow.onload = function() { printWindow.print(); };
        }
    ">
    <!-- Close Button (Mobile Only) -->
    <div class="lg:hidden absolute top-4 right-4 z-10" x-show="isCartOpen">
        <button @click="closeCart()" class="text-gray-500 hover:text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="absolute top-4 left-4 right-4 z-50 p-3 bg-primary-light border border-primary/20 text-primary rounded-sm flex justify-between items-center animate-fade-in-down" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <span class="text-sm font-semibold">{{ session('message') }}</span>
            <button @click="show = false" class="text-primary hover:text-primary-hover">&times;</button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="absolute top-4 left-4 right-4 z-50 p-3 bg-red-50 border border-red-200 text-red-600 rounded-sm flex justify-between items-center animate-fade-in-down" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <span class="text-sm font-semibold">{{ session('error') }}</span>
            <button @click="show = false" class="text-red-600 hover:text-red-700">&times;</button>
        </div>
    @endif

    <!-- Order Header -->
    <div class="h-16 border-b border-line flex items-center justify-between px-4">
        <div class="font-serif font-bold text-lg text-ink">Pesanan Baru</div>
        <button wire:click="clearCart" class="text-danger hover:text-red-700 text-sm font-semibold transition-colors">Kosongkan</button>
    </div>

    <!-- Order Type / Table -->
    <div class="p-4 border-b border-line">
        <div class="flex bg-gray-50 p-1 rounded-sm border border-line">
            <button wire:click="$set('orderType', 'dine-in')" class="flex-1 py-1.5 {{ $orderType === 'dine-in' ? 'bg-white border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-ink' }} rounded-sm text-sm font-semibold transition-all">Dine In</button>
            <button wire:click="$set('orderType', 'take-away')" class="flex-1 py-1.5 {{ $orderType === 'take-away' ? 'bg-white border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-ink' }} rounded-sm text-sm font-semibold transition-all">Take Away</button>
        </div>

        @if($orderType === 'dine-in')
        <div class="mt-4 animate-fade-in-up">
            <select wire:model.live="tableId" class="w-full border border-line rounded-sm px-4 py-3 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all appearance-none bg-white bg-no-repeat bg-[url('data:image/svg+xml;utf8,<svg%20fill=%22none%22%20viewBox=%220%200%2024%2024%20%22%20stroke=%22currentColor%22%20stroke-width=%221.5%22%20xmlns=%22http://www.w3.org/2000/svg%22><path%20stroke-linecap=%22round%22%20stroke-linejoin=%22round%22%20d=%22M8.25%2015L12%2018.75%2015.75%2015m-7.5-6L12%205.25%2015.75%209%22/></svg>')] bg-[position:right_0.5rem_center] bg-[length:1.5em_1.5em] pr-10">
                <option value="">Pilih Meja...</option>
                @foreach(\App\Models\Table::all() as $table)
                    <option value="{{ $table->id }}">{{ $table->number }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 hide-scrollbar">
        @forelse($items as $index => $item)
        <div class="bg-white p-3 rounded-sm border border-line flex gap-3 group animate-fade-in-right" style="animation-delay: {{ $loop->index * 50 }}ms">
            <div class="w-16 h-16 bg-gray-100 rounded-sm overflow-hidden flex-shrink-0">
                <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start gap-2">
                        <div class="font-semibold text-sm text-ink line-clamp-2">{{ $item['name'] }}</div>
                        <div class="font-bold text-sm text-ink shrink-0">{{ number_format($item['price'], 0, ',', '.') }}</div>
                    </div>
                    @if($item['notes'])
                        <div class="text-xs text-gray-500 mt-1 line-clamp-1" title="{{ $item['notes'] }}">{{ $item['notes'] }}</div>
                    @endif
                </div>
                <div class="flex justify-between items-center mt-3">
                    <button class="text-xs text-primary font-semibold hover:text-primary-hover transition-colors flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                        Catatan
                    </button>
                    <div class="flex items-center bg-gray-50 rounded-sm border border-line h-7">
                        <button wire:click="decrementQuantity({{ $index }})" class="w-7 h-full flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">-</button>
                        <span class="text-sm font-semibold w-8 text-center">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity({{ $index }})" class="w-7 h-full flex items-center justify-center text-white bg-primary hover:bg-primary-hover transition-colors">+</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center h-full text-gray-400">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-gray-300">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </div>
            <p class="font-medium text-ink-secondary">Belum ada pesanan</p>
            <p class="text-xs mt-1">Pilih menu dari sebelah kiri</p>
        </div>
        @endforelse
    </div>

    <!-- Checkout Summary -->
    <div class="p-4 border-t border-line bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-medium text-ink">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">PPN (11%)</span>
                <span class="font-medium text-ink">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
            </div>
            @if($discountAmount > 0)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Diskon</span>
                <span class="font-medium text-primary">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between items-end mt-2">
                <span class="font-serif font-bold text-lg text-ink">Total Bayar</span>
                <span class="font-serif font-bold text-2xl text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            @php
                $activeShift = \App\Models\Shift::where('user_id', auth()->id())->where('status', 'open')->exists();
                $isDisabled = count($items) === 0 || ($orderType === 'dine-in' && empty($tableId)) || !$activeShift;
            @endphp

            @if($discountAmount > 0)
            <div class="flex justify-between items-center mt-2 text-sm">
                <span class="text-gray-500">Diskon</span>
                <span class="font-medium text-primary flex items-center gap-2">
                    - Rp {{ number_format($discountAmount, 0, ',', '.') }}
                    <button wire:click="removeDiscount" class="text-gray-400 hover:text-red-500 text-xs">Hapus</button>
                </span>
            </div>
            @endif

            @if(count($payments) > 0)
            <div class="mt-3 space-y-1 text-sm border-t border-line pt-3">
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Metode Pembayaran</span>
                    <span>Sisa: Rp {{ number_format($this->getRemainingPayment(), 0, ',', '.') }}</span>
                </div>
                @foreach($payments as $index => $line)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $line['method'])) }}</span>
                    <span class="font-medium flex items-center gap-2">
                        Rp {{ number_format($line['amount'], 0, ',', '.') }}
                        <button wire:click="removePayment({{ $index }})" class="text-gray-400 hover:text-red-500 text-xs">Hapus</button>
                    </span>
                </div>
                @endforeach
            </div>
            @endif

            <div class="flex gap-2 mt-4">
                <button wire:click="openDiscountModal" class="flex-1 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-sm hover:bg-gray-50 transition-colors">
                    + Diskon
                </button>
                <button wire:click="openPaymentModal" class="flex-1 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-sm hover:bg-gray-50 transition-colors">
                    + Bayar
                </button>
                <button wire:click="processCheckout" class="flex-2 relative overflow-hidden bg-primary hover:bg-primary-hover text-white font-bold py-2.5 rounded-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary/30 group {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $isDisabled ? 'disabled' : '' }}>
                    <div class="flex items-center justify-center gap-2">
                        <span>Konfirmasi</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 group-hover:translate-x-1 transition-transform">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>

        @if(!$activeShift)
            <div class="mt-3 p-2 bg-red-50 text-red-600 rounded-sm flex items-center justify-center gap-2 border border-red-200 animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <p class="text-xs font-semibold">Buka shift kasir terlebih dahulu</p>
            </div>
        @elseif(count($items) > 0 && $orderType === 'dine-in' && empty($tableId))
            <div class="mt-3 p-2 bg-yellow-50 text-yellow-700 rounded-sm flex items-center justify-center gap-2 border border-yellow-200 animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <p class="text-xs font-semibold">Pilih meja untuk Dine-in</p>
            </div>
        @endif
    </div>

    <!-- Discount Modal -->
    <x-modal wire:model.live="showDiscountModal" maxWidth="sm" :show="$showDiscountModal">
        <div class="p-6">
            <h2 class="text-lg font-bold font-serif text-gray-900 mb-6">Diskon Manual</h2>

            <form wire:submit="applyDiscount" class="space-y-4">
                <div>
                    <x-input-label for="discountValue" value="Nominal Diskon (Rp)" />
                    <x-text-input id="discountValue" type="number" class="mt-1 block w-full" wire:model="discountValue" min="0.01" />
                    <x-input-error :messages="$errors->get('discountValue')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="discountNote" value="Alasan Diskon (Opsional)" />
                    <x-text-input id="discountNote" type="text" class="mt-1 block w-full" wire:model="discountNote" placeholder="Contoh: repeat customer, promo" />
                    <x-input-error :messages="$errors->get('discountNote')" class="mt-2" />
                </div>

                <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-sm text-xs text-yellow-800">
                    Diskon di atas <strong>Rp {{ number_format(\App\Models\Setting::where('key', 'discount_auto_approval_limit')->value('value') ?? 10000, 0, ',', '.') }}</strong> memerlukan <strong>approval Manager</strong>.
                </div>

                <div>
                    <x-input-label for="managerCode" value="Kode Manager (jika diperlukan)" />
                    <x-text-input id="managerCode" type="text" class="mt-1 block w-full" wire:model="managerCode" placeholder="Kode approval Manager" />
                    <x-input-error :messages="$errors->get('managerCode')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-6 gap-3">
                    <button type="button" wire:click="$set('showDiscountModal', false)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        Batal
                    </button>
                    <x-ui.button type="submit">
                        Terapkan
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Payment / Split Payment Modal -->
    <x-modal wire:model.live="showPaymentModal" maxWidth="sm" :show="$showPaymentModal">
        <div class="p-6">
            <h2 class="text-lg font-bold font-serif text-gray-900 mb-2">Tambah Pembayaran</h2>
            <p class="text-sm text-gray-500 mb-6">Sisa tagihan: <strong class="text-primary">Rp {{ number_format($this->getRemainingProperty(), 0, ',', '.') }}</strong></p>

            <form wire:submit="addPayment" class="space-y-4">
                <div>
                    <x-input-label for="paymentMethod" value="Metode Pembayaran" />
                    <select id="paymentMethod" wire:model="paymentMethod" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm">
                        @foreach($paymentMethods as $pm)
                            <option value="{{ $pm['value'] }}">{{ $pm['label'] }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('paymentMethod')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="paymentAmount" value="Nominal (Rp)" />
                    <x-text-input id="paymentAmount" type="number" step="0.01" class="mt-1 block w-full" wire:model="paymentAmount" min="0.01" />
                    <x-input-error :messages="$errors->get('paymentAmount')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-6 gap-3">
                    <button type="button" wire:click="$set('showPaymentModal', false)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        Batal
                    </button>
                    <x-ui.button type="submit">
                        Tambah
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
