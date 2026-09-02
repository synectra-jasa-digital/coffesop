<x-app-layout>
    <x-slot name="header">
        <x-ui.heading level="2" class="text-xl">
            {{ __('Riwayat Transaksi') }}
        </x-ui.heading>
    </x-slot>

    <x-ui.section spacing="py-6">
        @if (session()->has('message'))
            <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-sm shadow-sm text-sm font-semibold flex justify-between">
                <span>{{ session('message') }}</span>
                <button onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 gap-4">
                <div class="flex-1 max-w-sm">
                    <x-text-input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No. Order..." class="w-full text-sm py-2" />
                </div>
                <div>
                    <input type="date" wire:model.live="dateFilter" class="border-gray-300 rounded-sm text-sm focus:border-primary focus:ring-primary py-2 px-3">
                </div>
            </div>

            <x-ui.table :headers="['Waktu', 'No. Order', 'Tipe', 'Total', 'Status', 'Kasir', 'Aksi']">
                @forelse($orders as $order)
                <x-ui.table-row class="{{ $order->status === 'void' ? 'opacity-50 bg-gray-50' : '' }}">
                    <x-ui.table-cell>{{ $order->created_at->format('H:i') }}</x-ui.table-cell>
                    <x-ui.table-cell class="font-bold">{{ $order->order_number }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        {{ ucfirst($order->type) }} 
                        @if($order->type === 'dine-in' && $order->table)
                            <span class="text-xs text-gray-500">({{ $order->table->number }})</span>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell class="font-medium text-[#398263]">Rp {{ number_format($order->total, 0, ',', '.') }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($order->status === 'completed')
                            <x-ui.badge variant="success">Selesai</x-ui.badge>
                        @elseif($order->status === 'void')
                            <x-ui.badge variant="danger">Void</x-ui.badge>
                        @else
                            <x-ui.badge variant="warning">{{ ucfirst($order->status) }}</x-ui.badge>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>{{ $order->user->name ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <button wire:click="openDetail({{ $order->id }})" class="text-primary hover:underline text-sm font-semibold">Detail</button>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="7" class="text-center text-gray-500 py-8">Tidak ada transaksi pada tanggal ini.</x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table>
            
            <div class="p-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        </x-ui.card>
    </x-ui.section>

    <!-- Detail Modal -->
    <x-modal wire:model.live="showDetailModal" maxWidth="md" :show="$showDetailModal">
        @if($selectedOrder)
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-lg font-bold font-serif text-gray-900">{{ $selectedOrder->order_number }}</h2>
                    <p class="text-xs text-gray-500">{{ $selectedOrder->created_at->format('d M Y, H:i') }} • Kasir: {{ $selectedOrder->user->name ?? '-' }}</p>
                </div>
                @if($selectedOrder->status !== 'void')
                    <x-ui.badge variant="success">Selesai</x-ui.badge>
                @else
                    <x-ui.badge variant="danger">Void</x-ui.badge>
                @endif
            </div>

            <div class="border-t border-b border-gray-100 py-4 mb-4 space-y-3 max-h-60 overflow-y-auto">
                @foreach($selectedOrder->items as $item)
                <div class="flex justify-between items-start text-sm">
                    <div>
                        <span class="font-medium">{{ $item->quantity }}x {{ $item->product->name ?? 'Produk Dihapus' }}</span>
                        @if($item->notes)
                            <div class="text-xs text-gray-500 mt-0.5">{{ $item->notes }}</div>
                        @endif
                    </div>
                    <span class="font-medium text-gray-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="space-y-1 text-sm mb-6">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($selectedOrder->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Pajak (PPN)</span>
                    <span>Rp {{ number_format($selectedOrder->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg pt-2 mt-2 border-t border-gray-100 text-[#398263]">
                    <span>Total</span>
                    <span>Rp {{ number_format($selectedOrder->total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($selectedOrder->notes)
                <div class="bg-red-50 text-red-700 p-3 rounded-sm text-sm mb-6 border border-red-100">
                    <span class="font-bold">Catatan:</span> {{ $selectedOrder->notes }}
                </div>
            @endif

            <div class="flex justify-between pt-4 gap-3">
                <div>
                    @if($selectedOrder->status !== 'void' && auth()->user()->hasAnyRole(['Manager/Supervisor', 'Owner/Admin']))
                        <button wire:click="openVoid({{ $selectedOrder->id }})" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-sm font-semibold text-xs transition-colors">
                            Void Transaksi
                        </button>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button type="button" wire:click="$set('showDetailModal', false)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-sm font-semibold text-xs">Tutup</button>
                    @if($selectedOrder->status !== 'void')
                        <x-ui.button class="!py-2 !px-4 !text-xs" onclick="window.print()">Cetak Ulang</x-ui.button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </x-modal>

    <!-- Void Confirmation Modal -->
    <x-modal wire:model.live="showVoidModal" maxWidth="sm" :show="$showVoidModal">
        <div class="p-6">
            <h2 class="text-lg font-bold font-serif text-gray-900 mb-2">Void Transaksi</h2>
            <p class="text-sm text-red-600 mb-4 font-medium">Tindakan ini akan membatalkan transaksi dan mengubah status laporan. Tindakan ini tidak dapat dikembalikan.</p>
            
            <form wire:submit="processVoid" class="space-y-4">
                <div>
                    <x-input-label for="voidNotes" value="Alasan Void (Wajib)" />
                    <textarea id="voidNotes" wire:model="voidNotes" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-sm shadow-sm text-sm" rows="3" required></textarea>
                    <x-input-error :messages="$errors->get('voidNotes')" class="mt-2" />
                </div>
                <div class="flex justify-end pt-4 gap-3">
                    <button type="button" wire:click="$set('showVoidModal', false)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-sm font-semibold text-xs">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-sm font-semibold text-xs">Konfirmasi Void</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>