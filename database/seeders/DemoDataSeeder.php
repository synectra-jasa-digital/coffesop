<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Table;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ingredients
        Ingredient::firstOrCreate(['name' => 'Biji Kopi Arabica'], [
            'unit' => 'gram',
            'minimum_stock' => 1000,
            'current_stock' => 5000,
        ]);

        Ingredient::firstOrCreate(['name' => 'Biji Kopi Robusta'], [
            'unit' => 'gram',
            'minimum_stock' => 1000,
            'current_stock' => 3000,
        ]);

        Ingredient::firstOrCreate(['name' => 'Susu Fresh Milk'], [
            'unit' => 'ml',
            'minimum_stock' => 2000,
            'current_stock' => 10000, // 10 liter
        ]);

        Ingredient::firstOrCreate(['name' => 'Saus Karamel'], [
            'unit' => 'ml',
            'minimum_stock' => 500,
            'current_stock' => 2000,
        ]);

        Ingredient::firstOrCreate(['name' => 'Bubuk Matcha'], [
            'unit' => 'gram',
            'minimum_stock' => 500,
            'current_stock' => 1500,
        ]);

        Ingredient::firstOrCreate(['name' => 'Kentang Beku'], [
            'unit' => 'gram',
            'minimum_stock' => 2000,
            'current_stock' => 5000,
        ]);

        Ingredient::firstOrCreate(['name' => 'Sayap Ayam'], [
            'unit' => 'pcs',
            'minimum_stock' => 50,
            'current_stock' => 200,
        ]);

        // 2. Tables
        for ($i = 1; $i <= 10; $i++) {
            $tableNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
            Table::firstOrCreate(['number' => "Meja $tableNumber"], [
                'status' => 'available',
            ]);
        }
    }
}
