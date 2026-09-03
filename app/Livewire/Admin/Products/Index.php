<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\ActivityLog;

class Index extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditing = false;

    // Form fields
    public $productId;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $is_active = true;

    // Variant fields
    public $showVariantModal = false;
    public $variantId;
    public $variantName;
    public $variantValue;
    public $variantPriceAdjustment = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'is_active' => 'boolean',
    ];

    protected $variantRules = [
        'variantName' => 'required|string|max:255',
        'variantValue' => 'required|string|max:255',
        'variantPriceAdjustment' => 'required|numeric',
    ];

    public function create()
    {
        $this->resetValidation();
        $this->reset(['productId', 'name', 'description', 'price', 'category_id', 'is_active']);
        $this->isEditing = false;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $product = Product::findOrFail($id);

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->is_active = $product->is_active;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            $product = Product::where('id', $this->productId)->first();
            $oldData = $product ? $product->only(['name', 'description', 'price', 'category_id', 'is_active']) : null;
            Product::where('id', $this->productId)->update($data);
            session()->flash('message', 'Produk sudah diupdate.');
            ActivityLog::log('update_product', $product, $oldData, $data);
        } else {
            $product = Product::create($data);
            session()->flash('message', 'Produk sudah ditambahkan.');
            ActivityLog::log('create_product', $product, null, $data);
        }

        $this->showModal = false;
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $oldActive = $product->is_active;
        $product->update(['is_active' => !$product->is_active]);
        session()->flash('message', 'Status produk diperbarui.');
        ActivityLog::log('toggle_product_status', $product, ['is_active' => $oldActive], ['is_active' => $product->is_active]);
    }

    // ---------- Variant CRUD ----------

    public function openVariantModal($productId)
    {
        $this->reset(['variantId', 'variantName', 'variantValue', 'variantPriceAdjustment']);
        $this->variantPriceAdjustment = 0;
        $this->productId = $productId;
        $this->showVariantModal = true;
    }

    public function editVariant($variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $this->variantId = $variant->id;
        $this->variantName = $variant->name;
        $this->variantValue = $variant->value;
        $this->variantPriceAdjustment = $variant->price_adjustment;
        $this->productId = $variant->product_id;
        $this->showVariantModal = true;
    }

    public function saveVariant()
    {
        $this->validate($this->variantRules);

        if ($this->variantId) {
            $variant = ProductVariant::find($this->variantId);
            $oldData = $variant->only(['name', 'value', 'price_adjustment']);
            $variant->update([
                'name' => $this->variantName,
                'value' => $this->variantValue,
                'price_adjustment' => $this->variantPriceAdjustment,
            ]);
            session()->flash('message', 'Varian sudah diupdate.');
            ActivityLog::log('update_variant', $variant, $oldData, $variant->only(['name', 'value', 'price_adjustment']));
        } else {
            $variant = ProductVariant::create([
                'product_id' => $this->productId,
                'name' => $this->variantName,
                'value' => $this->variantValue,
                'price_adjustment' => $this->variantPriceAdjustment,
            ]);
            session()->flash('message', 'Varian sudah ditambahkan.');
            ActivityLog::log('create_variant', $variant, null, $variant->only(['product_id', 'name', 'value', 'price_adjustment']));
        }

        $this->showVariantModal = false;
    }

    public function deleteVariant($variantId)
    {
        $variant = ProductVariant::find($variantId);
        if (! $variant) {
            return;
        }

        $variant->delete();
        session()->flash('message', 'Varian sudah dihapus.');
        ActivityLog::log('delete_variant', $variant, $variant->only(['product_id', 'name', 'value', 'price_adjustment']), null);
    }

    public function render()
    {
        $products = Product::with(['category', 'variants'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::all();

        return view('livewire.admin.products.index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}