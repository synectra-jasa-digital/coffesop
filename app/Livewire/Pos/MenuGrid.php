<?php

namespace App\Livewire\Pos;

use Livewire\Component;

class MenuGrid extends Component
{
    public $products = [
        ['id' => 1, 'name' => 'Cafe Latte', 'price' => 28000, 'category' => 'Kopi', 'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=300&auto=format&fit=crop'],
        ['id' => 2, 'name' => 'Americano', 'price' => 20000, 'category' => 'Kopi', 'image' => 'https://images.unsplash.com/photo-1551030173-122aabc4489c?q=80&w=300&auto=format&fit=crop'],
        ['id' => 3, 'name' => 'Caramel Macchiato', 'price' => 35000, 'category' => 'Kopi', 'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=300&auto=format&fit=crop'],
        ['id' => 4, 'name' => 'Matcha Latte', 'price' => 30000, 'category' => 'Non-Kopi', 'image' => 'https://images.unsplash.com/photo-1515823662972-da6a2e4d3002?q=80&w=300&auto=format&fit=crop'],
        ['id' => 5, 'name' => 'French Fries', 'price' => 25000, 'category' => 'Snack', 'image' => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?q=80&w=300&auto=format&fit=crop'],
        ['id' => 6, 'name' => 'Chicken Wings', 'price' => 30000, 'category' => 'Snack', 'image' => 'https://images.unsplash.com/photo-1569698134101-f16c06a44bf9?q=80&w=300&auto=format&fit=crop'],
    ];

    public $categories = ['Semua Menu', 'Kopi', 'Non-Kopi', 'Makanan', 'Snack'];
    public $activeCategory = 'Semua Menu';

    public function filterCategory($category)
    {
        $this->activeCategory = $category;
    }

    public function addToCart($productId)
    {
        $product = collect($this->products)->firstWhere('id', $productId);
        if ($product) {
            $this->dispatch('productAdded', product: $product);
        }
    }

    public function render()
    {
        $filteredProducts = collect($this->products);
        if ($this->activeCategory !== 'Semua Menu') {
            $filteredProducts = $filteredProducts->where('category', $this->activeCategory);
        }

        return view('livewire.pos.menu-grid', [
            'filteredProducts' => $filteredProducts
        ]);
    }
}
