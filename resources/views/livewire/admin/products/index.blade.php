<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-ui.heading level="2" class="text-xl">
                {{ __('Manajemen Produk & Menu') }}
            </x-ui.heading>
            <x-ui.button wire:click="create">Tambah Produk</x-ui.button>
        </div>
    </x-slot>

    <x-ui.section spacing="py-12">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-sm shadow-sm flex justify-between items-center">
                <span class="text-sm font-semibold">{{ session('message') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-green-800 hover:text-green-900">&times;</button>
            </div>
        @endif

        <x-ui.card padding="p-0" class="overflow-hidden">
<x-ui.table :headers="['Produk', 'Kategori', 'Harga Dasar', 'Status', 'Aksi']">
                @forelse($products as $product)
                <x-ui.table-row>
                    <x-ui.table-cell>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-200 rounded-sm overflow-hidden flex-shrink-0">
                                @if($product->image)
                                    <img src="{{ $product->image }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1.75 0Z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <span class="font-medium">{{ $product->name }}</span>
                        </div>
                    </x-ui.table-cell>
                    <x-ui.table-cell>{{ $product->category->name ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell>Rp {{ number_format($product->price, 0, ',', '.') }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($product->is_active)
                            <button wire:click="toggleStatus({{ $product->id }})" class="focus:outline-none">
                                <x-ui.badge variant="success">Aktif</x-ui.badge>
                            </button>
                        @else
                            <button wire:click="toggleStatus({{ $product->id }})" class="focus:outline-none">
                                <x-ui.badge variant="default">Nonaktif</x-ui.badge>
                            </button>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex flex-col gap-1">
                            <div class="flex gap-3">
                                <button wire:click="edit({{ $product->id }})" class="text-primary hover:underline text-sm font-semibold">Edit</button>
                                <button wire:click="openVariantModal({{ $product->id }})" class="text-blue-600 hover:underline text-sm font-semibold">Varian ({{ $product->variants->count() }})</button>
                                <a href="{{ route('admin.products.recipe', $product->id) }}" class="text-gray-600 hover:underline text-sm font-semibold">Resep (BOM)</a>
                            </div>
                            @forelse($product->variants as $variant)
                                <div class="flex gap-2 text-xs text-gray-500">
                                    <span>{{ $variant->name }}: {{ $variant->value }}</span>
                                    <span class="text-gray-400">Rp {{ number_format($variant->price_adjustment, 0, ',', '.') }}</span>
                                    <button wire:click="editVariant({{ $variant->id }})" class="text-primary hover:underline">Edit</button>
                                    <button wire:click="deleteVariant({{ $variant->id }})" class="text-red-600 hover:underline" onclick="return confirm('Hapus varian ini?')">Hapus</button>
                                </div>
                            @empty
                                <span class="text-xs text-gray-400">Belum ada varian</span>
                            @endforelse
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="5" class="text-center text-gray-500">Tidak ada data produk.</x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table>

            <div class="p-4 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        </x-ui.card>
    </x-ui.section>

    <!-- Product Modal -->
    <x-modal wire:model.live="showModal" maxWidth="md" :show="$showModal">
        <div class="p-6">
            <h2 class="text-lg font-bold font-serif text-gray-900 mb-6">
                {{ $isEditing ? 'Edit Produk' : 'Tambah Produk Baru' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <x-input-label for="name" value="Nama Produk" />
                    <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="category_id" value="Kategori" />
                    <select id="category_id" wire:model="category_id" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm">
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="price" value="Harga (Rp)" />
                    <x-text-input id="price" type="number" class="mt-1 block w-full" wire:model="price" />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Deskripsi (Opsional)" />
                    <textarea id="description" wire:model="description" class="mt-1 block w-full border-gray-300 focus:border-[#398263] focus:ring-[#398263] rounded-sm shadow-sm text-sm" rows="3"></textarea>
                </div>

                <div class="flex items-center mt-4">
                    <input id="is_active" type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-[#398263] shadow-sm focus:ring-[#398263]">
                    <label for="is_active" class="ml-2 text-sm text-gray-600">Produk Aktif (Tampil di POS)</label>
                </div>

                <div class="flex justify-end mt-6 gap-3">
                    <button type="button" wire:click="$set('showModal', false)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        Batal
                    </button>
                    <x-ui.button type="submit">
                        Simpan
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Variant Modal -->
    <x-modal wire:model.live="showVariantModal" maxWidth="md" :show="$showVariantModal">
        <div class="p-6">
            <h2 class="text-lg font-bold font-serif text-gray-900 mb-6">
                {{ $variantId ? 'Edit Varian' : 'Curian Varian' }}
            </h2>

            <form wire:submit="saveVariant" class="space-y-4">
                <div>
                    <x-input-label for="variantName" value="Nama Varian (contoh: Ukuran)" />
                    <x-text-input id="variantName" type="text" class="mt-1 block w-full" wire:model="variantName" />
                    <x-input-error :messages="$errors->get('variantName')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="variantValue" value="Nilai Varian (contoh: Large / 300ml)" />
                    <x-text-input id="variantValue" type="text" class="mt-1 block w-full" wire:model="variantValue" />
                    <x-input-error :messages="$errors->get('variantValue')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="variantPriceAdjustment" value="Penyesuaian Harga (Rp, negatif = lebih murah)" />
                    <x-text-input id="variantPriceAdjustment" type="number" class="mt-1 block w-full" wire:model="variantPriceAdjustment" />
                    <x-input-error :messages="$errors->get('variantPriceAdjustment')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-6 gap-3">
                    <button type="button" wire:click="$set('showVariantModal', false)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        Batal
                    </button>
                    <x-ui.button type="submit">
                        Simpan
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
