<div>
    @if($activeShift)
        <div class="flex items-center gap-3">
            <div class="px-3 py-1 bg-[#EAF3EF] text-[#398263] text-xs font-semibold rounded-sm border border-[#398263]/20 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#398263] animate-pulse"></span>
                Shift Terbuka
            </div>
            <button wire:click="initiateCloseShift" class="text-xs font-semibold text-gray-500 hover:text-red-600 transition-colors">Tutup Shift</button>
        </div>
    @else
        <div class="px-3 py-1 bg-red-50 text-red-600 text-xs font-semibold rounded-sm border border-red-100 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-red-600"></span>
            Shift Tertutup
        </div>
    @endif

    <!-- Open Shift Modal -->
    <x-modal wire:model.live="showOpenModal" maxWidth="md" :show="$showOpenModal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">
                Buka Shift Kasir
            </h2>
            <p class="text-sm text-gray-600 mb-6">
                Masukkan modal awal kasir sebelum memulai transaksi hari ini.
            </p>

            <div class="mb-4">
                <x-input-label for="startingCash" value="Modal Awal (Rp)" />
                <x-text-input id="startingCash" type="number" class="mt-1 block w-full" wire:model="startingCash" placeholder="0" />
                <x-input-error :messages="$errors->get('startingCash')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6 gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150">
                    Batal (Kembali)
                </a>
                <x-ui.button wire:click="openShift">
                    Buka Shift
                </x-ui.button>
            </div>
        </div>
    </x-modal>

    <!-- Close Shift Modal -->
    <x-modal wire:model.live="showCloseModal" maxWidth="md" :show="$showCloseModal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">
                Tutup Shift Kasir
            </h2>
            
            @if($activeShift)
            <div class="bg-gray-50 p-4 rounded-sm border border-gray-100 mb-6 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Modal Awal</span>
                    <span class="font-semibold">Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pendapatan Tunai</span>
                    <span class="font-semibold text-green-600">+ Rp {{ number_format($activeShift->expected_cash - $activeShift->starting_cash, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-gray-200 pt-2 flex justify-between mt-2">
                    <span class="font-bold">Ekspektasi Kas</span>
                    <span class="font-bold">Rp {{ number_format($activeShift->expected_cash, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <x-input-label for="actualEndingCash" value="Jumlah Uang Fisik (Rp)" />
                    <x-text-input id="actualEndingCash" type="number" class="mt-1 block w-full" wire:model="actualEndingCash" />
                    <x-input-error :messages="$errors->get('actualEndingCash')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="closingNotes" value="Catatan (Opsional)" />
                    <textarea id="closingNotes" wire:model="closingNotes" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm text-sm" rows="2" placeholder="Jelaskan jika ada selisih kas..."></textarea>
                </div>
            </div>
            @endif

            <div class="flex justify-end mt-6 gap-3">
                <button wire:click="$set('showCloseModal', false)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150">
                    Batal
                </button>
                <button wire:click="closeShift" class="bg-red-600 hover:bg-red-700 text-white font-semibold rounded-sm px-6 py-2 text-sm transition-colors">
                    Konfirmasi Tutup
                </button>
            </div>
        </div>
    </x-modal>
</div>