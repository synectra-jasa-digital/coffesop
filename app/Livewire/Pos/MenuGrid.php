<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

class MenuGrid extends Component
{
    public $activeCategory = 'Semua Menu';

    public function filterCategory($categoryName)
    {
        $this->activeCategory = $categoryName;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->dispatch('productAdded', product: $product->toArray());
        }
    }

    public function render()
    {
        $categoriesQuery = Category::all();
        $categories = collect(['Semua Menu'])->concat($categoriesQuery->pluck('name'));

        $productsQuery = Product::where('is_active', true)->with('category');
        
        if ($this->activeCategory !== 'Semua Menu') {
            $productsQuery->whereHas('category', function ($query) {
                $query->where('name', $this->activeCategory);
            });
        }

        $filteredProducts = $productsQuery->get();

        return view('livewire.pos.menu-grid', [
            'categories' => $categories,
            'filteredProducts' => $filteredProducts
        ]);
    }
}
