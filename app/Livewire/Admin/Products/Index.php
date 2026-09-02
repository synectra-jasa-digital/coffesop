<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

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

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'is_active' => 'boolean',
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
            Product::where('id', $this->productId)->update($data);
            session()->flash('message', 'Produk berhasil diupdate.');
        } else {
            Product::create($data);
            session()->flash('message', 'Produk berhasil ditambahkan.');
        }

        $this->showModal = false;
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        session()->flash('message', 'Status produk diperbarui.');
    }

    public function render()
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::all();

        return view('livewire.admin.products.index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
