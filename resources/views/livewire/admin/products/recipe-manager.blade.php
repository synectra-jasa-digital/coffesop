<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-[#398263]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <x-ui.heading level="2" class="text-xl">
                {{ __('Manajemen Resep (BOM): ') }} <span class="text-gray-500 font-normal">{{ $product->name }}</span>
            </x-ui.heading>
        </div>
    </x-slot>

    <x-ui.section spacing="py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Panel: Add Ingredient Form -->
            <div class="lg:col-span-1">
                <x-ui.card>
                    <h3 class="font-bold text-lg border-b border-gray-100 pb-4 mb-4">Tambah Bahan Baku</h3>
                    
                    @if (session()->has('message'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-sm shadow-sm text-sm font-semibold">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit="addIngredient" class="space-y-4">
                        <div>
                            <x-input-label for="selectedIngredient" value="Pilih Bahan Baku" />
                            <select id="selectedIngredient" wire:model="selectedIngredient" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm">
                                <option value="">Pilih...</option>
                                @foreach($availableIngredients as $ingredient)
                                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }} ({{ $ingredient->unit }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('selectedIngredient')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="quantity" value="Takaran (Sesuai Satuan Baku)" />
                            <x-text-input id="quantity" type="number" step="0.01" class="mt-1 block w-full" wire:model="quantity" placeholder="Misal: 15 (untuk 15 gram)" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <div class="pt-4">
                            <x-ui.button type="submit" class="w-full">Tambahkan ke Resep</x-ui.button>
                        </div>
                    </form>
                </x-ui.card>
            </div>

            <!-- Right Panel: Recipe List -->
            <div class="lg:col-span-2">
                <x-ui.card padding="p-0" class="overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-lg">Komposisi Resep (BOM)</h3>
                    </div>
                    
                    <x-ui.table :headers="['Bahan Baku', 'Takaran', 'Aksi']">
                        @forelse($recipeIngredients as $item)
                            <x-ui.table-row>
                                <x-ui.table-cell class="font-medium">{{ $item->ingredient->name }}</x-ui.table-cell>
                                <x-ui.table-cell>{{ $item->quantity }} {{ $item->ingredient->unit }}</x-ui.table-cell>
                                <x-ui.table-cell>
                                    <button wire:click="removeIngredient({{ $item->id }})" class="text-red-500 hover:text-red-700 text-sm font-semibold flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        Hapus
                                    </button>
                                </x-ui.table-cell>
                            </x-ui.table-row>
                        @empty
                            <x-ui.table-row>
                                <x-ui.table-cell colspan="3" class="text-center text-gray-500 py-8">
                                    Resep belum dikonfigurasi. Produk ini tidak akan mengurangi stok bahan baku.
                                </x-ui.table-cell>
                            </x-ui.table-row>
                        @endforelse
                    </x-ui.table>
                </x-ui.card>
            </div>
        </div>
    </x-ui.section>
</div>
