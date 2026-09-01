<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $kopi = Category::create([
            'name' => 'Kopi',
            'description' => 'Minuman olahan espresso'
        ]);

        $nonKopi = Category::create([
            'name' => 'Non-Kopi',
            'description' => 'Minuman tanpa kopi'
        ]);

        $makanan = Category::create([
            'name' => 'Makanan',
            'description' => 'Makanan berat'
        ]);

        $snack = Category::create([
            'name' => 'Snack',
            'description' => 'Cemilan'
        ]);

        Product::create([
            'category_id' => $kopi->id,
            'name' => 'Cafe Latte',
            'description' => 'Espresso dengan steamed milk dan foam tipis',
            'price' => 28000,
            'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $kopi->id,
            'name' => 'Americano',
            'description' => 'Espresso dengan air panas/dingin',
            'price' => 20000,
            'image' => 'https://images.unsplash.com/photo-1551030173-122aabc4489c?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $kopi->id,
            'name' => 'Caramel Macchiato',
            'description' => 'Vanilla syrup, steamed milk, espresso dan saus caramel',
            'price' => 35000,
            'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $nonKopi->id,
            'name' => 'Matcha Latte',
            'description' => 'Premium matcha dengan steamed milk',
            'price' => 30000,
            'image' => 'https://images.unsplash.com/photo-1515823662972-da6a2e4d3002?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $snack->id,
            'name' => 'French Fries',
            'description' => 'Kentang goreng gurih renyah',
            'price' => 25000,
            'image' => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $snack->id,
            'name' => 'Chicken Wings',
            'description' => 'Sayap ayam dengan saus pedas manis',
            'price' => 30000,
            'image' => 'https://images.unsplash.com/photo-1569698134101-f16c06a44bf9?q=80&w=300&auto=format&fit=crop',
            'is_active' => true,
        ]);
    }
}
