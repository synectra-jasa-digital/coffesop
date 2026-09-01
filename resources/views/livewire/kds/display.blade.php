<div class="h-full flex flex-col bg-gray-100">
    <div class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div class="font-serif font-bold text-xl text-primary">Kitchen Display</div>
        </div>
        <div class="flex gap-4">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <span class="text-sm font-medium">Baru (1)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <span class="text-sm font-medium">Diproses (1)</span>
            </div>
        </div>
    </div>

    <div class="flex-1 p-6 overflow-x-auto overflow-y-hidden">
        <div class="flex gap-6 h-full items-start">
            
            <!-- Ticket: New -->
            <div class="w-80 flex-shrink-0 flex flex-col max-h-full bg-white rounded-sm border-t-4 border-t-red-500 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <div class="font-bold text-lg">#0042</div>
                        <div class="text-sm text-gray-500">Meja 04 • Dine In</div>
                    </div>
                    <div class="text-right">
                        <div class="text-red-500 font-bold">03:45</div>
                        <div class="text-xs text-gray-400">14:32</div>
                    </div>
                </div>
                <div class="p-4 flex-1 overflow-y-auto space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold">2x Cafe Latte</div>
                            <div class="text-sm text-gray-500 ml-4 border-l-2 border-gray-200 pl-2 mt-1">Ice, Less Sugar</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold">1x French Fries</div>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-sm transition-colors">
                        Mulai Proses
                    </button>
                </div>
            </div>

            <!-- Ticket: Processing -->
            <div class="w-80 flex-shrink-0 flex flex-col max-h-full bg-white rounded-sm border-t-4 border-t-yellow-500 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <div class="font-bold text-lg">#0041</div>
                        <div class="text-sm text-gray-500">Take Away</div>
                    </div>
                    <div class="text-right">
                        <div class="text-yellow-600 font-bold">08:12</div>
                        <div class="text-xs text-gray-400">14:28</div>
                    </div>
                </div>
                <div class="p-4 flex-1 overflow-y-auto space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold line-through text-gray-400">1x Americano</div>
                            <div class="text-sm text-gray-400 ml-4 border-l-2 border-gray-200 pl-2 mt-1 line-through">Hot</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold">1x Caramel Macchiato</div>
                            <div class="text-sm text-gray-500 ml-4 border-l-2 border-gray-200 pl-2 mt-1">Ice</div>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t border-gray-100 bg-gray-50 flex gap-2">
                    <button class="flex-1 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 rounded-sm transition-colors text-sm">
                        Batal
                    </button>
                    <button class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-sm transition-colors">
                        Selesai
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
