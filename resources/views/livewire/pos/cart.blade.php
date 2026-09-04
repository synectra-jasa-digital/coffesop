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
    <div class="h-16 border-b border-line flex items-center justify-between px-5 shrink-0 bg-white">
        <div class="font-bold text-lg text-ink">Pesanan Baru</div>
        <button wire:click="clearCart" class="text-red-500 hover:text-red-600 text-sm font-medium transition-colors px-3 py-1.5 rounded-full hover:bg-red-50 focus-flat">Kosongkan</button>
    </div>

    <!-- Order Type / Table -->
    <div class="p-4 sm:p-5 border-b border-line bg-white shrink-0">
        <div class="flex bg-surface-alt p-1 rounded-lg border border-line">
            <button wire:click="$set('orderType', 'dine-in')" class="flex-1 py-2 {{ $orderType === 'dine-in' ? 'bg-white shadow-[0_1px_3px_rgba(0,0,0,0.1)] text-primary font-bold' : 'text-ink-secondary hover:text-ink font-medium' }} rounded-md text-sm transition-all focus-flat">Dine In</button>
            <button wire:click="$set('orderType', 'take-away')" class="flex-1 py-2 {{ $orderType === 'take-away' ? 'bg-white shadow-[0_1px_3px_rgba(0,0,0,0.1)] text-primary font-bold' : 'text-ink-secondary hover:text-ink font-medium' }} rounded-md text-sm transition-all focus-flat">Take Away</button>
        </div>

        @if($orderType === 'dine-in')
        <div class="mt-4 animate-fade-in-down">
            <select wire:model.live="tableId" class="w-full border-line focus:border-primary focus:ring-1 focus:ring-primary rounded-lg px-4 py-3 text-sm transition-all appearance-none bg-white bg-no-repeat bg-[url('data:image/svg+xml;utf8,<svg%20fill=%22none%22%20viewBox=%220%200%2024%2024%20%22%20stroke=%22%239CA3AF%22%20stroke-width=%222%22%20xmlns=%22http://www.w3.org/2000/svg%22><path%20stroke-linecap=%22round%22%20stroke-linejoin=%22round%22%20d=%22M8.25%2015L12%2018.75%2015.75%2015m-7.5-6L12%205.25%2015.75%209%22/></svg>')] bg-[position:right_1rem_center] bg-[length:1.25em_1.25em] pr-10 shadow-sm">
                <option value="">Pilih Meja...</option>
                @foreach(\App\Models\Table::all() as $table)
                    <option value="{{ $table->id }}">{{ $table->number }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-3 bg-surface-alt hide-scrollbar">
        @forelse($items as $index => $item)
        <div class="bg-white p-3 rounded-lg border border-line flex gap-3.5 group animate-fade-in-right shadow-sm" style="animation-delay: {{ $loop->index * 30 }}ms">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-line-light rounded-md overflow-hidden flex-shrink-0 border border-line">
                <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start gap-2">
                        <div class="font-medium text-sm text-ink line-clamp-2 leading-tight">{{ $item['name'] }}</div>
                        <div class="font-bold text-sm text-ink shrink-0">{{ number_format($item['price'], 0, ',', '.') }}</div>
                    </div>
                    @if($item['notes'])
                        <div class="text-xs text-ink-secondary mt-1.5 line-clamp-1 italic bg-surface-alt px-2 py-0.5 rounded-sm inline-block" title="{{ $item['notes'] }}">{{ $item['notes'] }}</div>
                    @endif
                </div>
                <div class="flex justify-between items-center mt-3">
                    <button class="text-xs text-primary font-medium hover:text-primary-hover transition-colors flex items-center gap-1 bg-primary/5 px-2 py-1 rounded-sm focus-flat">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                        Catatan
                    </button>
                    <div class="flex items-center bg-white rounded-md border border-line h-8 shadow-sm">
                        <button wire:click="decrementQuantity({{ $index }})" class="w-8 h-full flex items-center justify-center text-ink-secondary hover:bg-surface-alt hover:text-ink transition-colors rounded-l-md active:bg-line-light">-</button>
                        <span class="text-sm font-semibold w-8 text-center text-ink border-x border-line h-full flex items-center justify-center bg-surface-alt/30">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQuantity({{ $index }})" class="w-8 h-full flex items-center justify-center text-white bg-primary hover:bg-primary-hover transition-colors rounded-r-md active:bg-primary/90">+</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center h-full text-ink-secondary">
            <div class="w-20 h-20 rounded-full bg-white border border-line shadow-sm flex items-center justify-center mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-10 text-line">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </div>
            <p class="font-medium text-ink">Belum ada pesanan</p>
            <p class="text-sm mt-1">Pilih menu dari sebelah kiri</p>
        </div>
        @endforelse
    </div>

    <!-- Checkout Summary -->
    <div class="p-4 sm:p-5 border-t border-line bg-white shadow-[0_-4px_10px_rgba(0,0,0,0.02)] shrink-0 z-10">
        <div class="space-y-2.5 mb-5">
            <div class="flex justify-between text-sm">
                <span class="text-ink-secondary">Subtotal</span>
                <span class="font-medium text-ink">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-ink-secondary">PPN (11%)</span>
                <span class="font-medium text-ink">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
            </div>
            
            @if($discountAmount > 0)
            <div class="flex justify-between text-sm items-center bg-primary/5 p-2 rounded-md border border-primary/10">
                <span class="text-primary font-medium flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4"><path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 0 0 1.06 0l7.22-7.22v5.69a.75.75 0 0 0 1.5 0v-7.5a.75.75 0 0 0-.75-.75h-7.5a.75.75 0 0 0 0 1.5h5.69l-7.22 7.22a.75.75 0 0 0 0 1.06Z" clip-rule="evenodd" /></svg>
                    Diskon
                </span>
                <span class="font-bold text-primary flex items-center gap-3">
                    - Rp {{ number_format($discountAmount, 0, ',', '.') }}
                    <button wire:click="removeDiscount" class="text-primary/60 hover:text-red-500 transition-colors bg-white rounded-full p-1 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </span>
            </div>
            @endif

            <div class="flex justify-between items-end mt-4 pt-4 border-t border-line">
                <span class="font-bold text-lg text-ink">Total Bayar</span>
                <span class="font-bold text-2xl md:text-3xl text-primary font-serif tracking-tight">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            @php
                $activeShift = \App\Models\Shift::where('user_id', auth()->id())->where('status', 'open')->exists();
                $isDisabled = count($items) === 0 || ($orderType === 'dine-in' && empty($tableId)) || !$activeShift;
            @endphp

            @if(count($payments) > 0)
            <div class="mt-4 bg-surface-alt rounded-lg border border-line overflow-hidden">
                <div class="bg-line-light px-3 py-2 flex justify-between text-xs font-medium text-ink-secondary border-b border-line">
                    <span>Pembayaran Sebagian</span>
                    <span class="{{ $this->getRemainingPayment() <= 0 ? 'text-primary font-bold' : '' }}">Sisa: Rp {{ number_format($this->getRemainingPayment(), 0, ',', '.') }}</span>
                </div>
                <div class="divide-y divide-line">
                    @foreach($payments as $index => $line)
                    <div class="flex justify-between items-center text-sm px-3 py-2.5">
                        <span class="text-ink font-medium">{{ ucfirst(str_replace('_', ' ', $line['method'])) }}</span>
                        <span class="font-semibold text-ink flex items-center gap-2">
                            Rp {{ number_format($line['amount'], 0, ',', '.') }}
                            <button wire:click="removePayment({{ $index }})" class="text-ink-secondary hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            </button>
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex gap-2.5 mt-5">
                <button wire:click="openDiscountModal" class="flex-[0.8] py-3 text-sm font-semibold text-ink bg-surface-alt border border-line rounded-lg hover:bg-line-light transition-colors focus-flat shadow-sm flex items-center justify-center gap-1.5 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" /></svg>
                    Diskon
                </button>
                <button wire:click="openPaymentModal" class="flex-[0.8] py-3 text-sm font-semibold text-ink bg-surface-alt border border-line rounded-lg hover:bg-line-light transition-colors focus-flat shadow-sm flex items-center justify-center gap-1.5 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                    Split
                </button>
                <button wire:click="processCheckout" class="flex-[1.4] relative overflow-hidden bg-primary hover:bg-primary-hover text-white font-bold py-3 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-primary/30 group shadow-md active:scale-[0.98] {{ $isDisabled ? 'opacity-50 cursor-not-allowed bg-ink-secondary/50 hover:bg-ink-secondary/50 shadow-none text-white' : '' }}" {{ $isDisabled ? 'disabled' : '' }}>
                    <div class="flex items-center justify-center gap-2">
                        <span>Konfirmasi</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5 group-hover:translate-x-1 transition-transform">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>

        @if(!$activeShift)
            <div class="mt-3 p-3 bg-red-50 text-red-700 rounded-lg flex items-center justify-center gap-2.5 border border-red-200 animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <p class="text-sm font-semibold">Buka shift kasir terlebih dahulu</p>
            </div>
        @elseif(count($items) > 0 && $orderType === 'dine-in' && empty($tableId))
            <div class="mt-3 p-3 bg-yellow-50 text-yellow-800 rounded-lg flex items-center justify-center gap-2.5 border border-yellow-200 animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <p class="text-sm font-semibold">Pilih meja untuk Dine-in</p>
            </div>
        @endif
    </div>

    <!-- Discount Modal -->
    <x-modal wire:model.live="showDiscountModal" maxWidth="sm" :show="$showDiscountModal">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Diskon Manual</h2>

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
            <h2 class="text-lg font-bold text-gray-900 mb-2">Tambah Pembayaran</h2>
            <p class="text-sm text-gray-500 mb-6">Sisa tagihan: <strong class="text-primary">Rp {{ number_format($this->getRemainingProperty(), 0, ',', '.') }}</strong></p>

            <form wire:submit="addPayment" class="space-y-4">
                <div>
                    <x-input-label for="paymentMethod" value="Metode Pembayaran" />
                    <select id="paymentMethod" wire:model="paymentMethod" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm">
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
