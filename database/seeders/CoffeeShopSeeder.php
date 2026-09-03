<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Table;
use App\Models\Setting;
use Spatie\Permission\Models\Role;

class CoffeeShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Permissions (From previous setup)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $roleOwner = Role::firstOrCreate(['name' => 'Owner/Admin']);
        $roleManager = Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        $roleKasir = Role::firstOrCreate(['name' => 'Kasir']);
        $roleBarista = Role::firstOrCreate(['name' => 'Barista/Gudang']);

        // Create initial users for each role
        $users = [
            ['name' => 'Super Admin', 'email' => 'admin@coffeeshop.com', 'role' => $roleOwner],
            ['name' => 'Store Manager', 'email' => 'manager@coffeeshop.com', 'role' => $roleManager],
            ['name' => 'Staff Kasir', 'email' => 'kasir@coffeeshop.com', 'role' => $roleKasir],
            ['name' => 'Staff Barista', 'email' => 'barista@coffeeshop.com', 'role' => $roleBarista],
        ];

        // Determine the seed password. Prefer an explicit SEED_ADMIN_PASSWORD
        // (set in .env); otherwise generate a strong random one and print it.
        // Never fall back to a weak, hard-coded default such as "password123".
        $seedPassword = env('SEED_ADMIN_PASSWORD');

        if (empty($seedPassword)) {
            $seedPassword = \Illuminate\Support\Str::password(18);
            if ($this->command) {
                $this->command->warn('SEED_ADMIN_PASSWORD is not set.');
                $this->command->warn('Generated seed users with password: '.$seedPassword);
            }
        }

        foreach ($users as $u) {
            $user = User::firstOrCreate([
                'email' => $u['email'],
            ], [
                'name' => $u['name'],
                'password' => Hash::make($seedPassword),
            ]);
            $user->assignRole($u['role']);
        }

        // 2. Settings
        $settings = [
            ['key' => 'store_name', 'value' => 'Good Coffee. Premium', 'type' => 'string', 'group' => 'store'],
            ['key' => 'store_address', 'value' => 'Jalan Seduh Kopi No. 99, Jakarta Selatan', 'type' => 'string', 'group' => 'store'],
            ['key' => 'store_phone', 'value' => '0812-3456-7890', 'type' => 'string', 'group' => 'store'],
            ['key' => 'tax_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'tax'],
            ['key' => 'tax_percentage', 'value' => '11', 'type' => 'number', 'group' => 'tax'],
            ['key' => 'service_charge_enabled', 'value' => 'false', 'type' => 'boolean', 'group' => 'tax'],
            ['key' => 'service_charge_percentage', 'value' => '5', 'type' => 'number', 'group' => 'tax'],
            ['key' => 'discount_auto_approval_limit', 'value' => '10000', 'type' => 'number', 'group' => 'discount'],
            ['key' => 'discount_require_manager_code', 'value' => 'true', 'type' => 'boolean', 'group' => 'discount'],
        ];
        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Tables
        for ($i = 1; $i <= 10; $i++) {
            $tableNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
            Table::firstOrCreate(['number' => "Meja $tableNumber"], ['status' => 'available']);
        }

        // 4. Categories
        $catKopi = Category::firstOrCreate(['name' => 'Kopi'], ['description' => 'Minuman olahan kopi asli']);
        $catNonKopi = Category::firstOrCreate(['name' => 'Non-Kopi'], ['description' => 'Minuman tanpa kafein']);
        $catPastry = Category::firstOrCreate(['name' => 'Pastry & Makanan'], ['description' => 'Kue dan makanan ringan pendamping']);

        // 5. Ingredients
        $ingredientsData = [
            ['name' => 'Biji Kopi House Blend', 'unit' => 'gram', 'min' => 1000, 'current' => 5000],
            ['name' => 'Biji Kopi Single Origin', 'unit' => 'gram', 'min' => 500, 'current' => 2000],
            ['name' => 'Susu Fresh Milk', 'unit' => 'ml', 'min' => 2000, 'current' => 15000],
            ['name' => 'Susu Oat (Oat Milk)', 'unit' => 'ml', 'min' => 1000, 'current' => 4000],
            ['name' => 'Sirup Vanilla', 'unit' => 'ml', 'min' => 500, 'current' => 1500],
            ['name' => 'Saus Karamel', 'unit' => 'ml', 'min' => 500, 'current' => 1500],
            ['name' => 'Bubuk Matcha Premium', 'unit' => 'gram', 'min' => 200, 'current' => 800],
            ['name' => 'Bubuk Cokelat', 'unit' => 'gram', 'min' => 300, 'current' => 1000],
            ['name' => 'Butter Croissant', 'unit' => 'pcs', 'min' => 10, 'current' => 30],
            ['name' => 'Chocolate Brownie', 'unit' => 'pcs', 'min' => 5, 'current' => 20],
            ['name' => 'Cup Es Plastik', 'unit' => 'pcs', 'min' => 100, 'current' => 500],
            ['name' => 'Cup Kertas Panas', 'unit' => 'pcs', 'min' => 100, 'current' => 500],
        ];

        $ingredients = [];
        foreach ($ingredientsData as $ing) {
            $ingredients[$ing['name']] = Ingredient::firstOrCreate(
                ['name' => $ing['name']],
                ['unit' => $ing['unit'], 'minimum_stock' => $ing['min'], 'current_stock' => $ing['current']]
            );
        }

        // 6. Products
        $productsData = [
            [
                'name' => 'Cafe Latte (Hot)',
                'category_id' => $catKopi->id,
                'description' => 'Espresso dengan steamed milk dan foam lembut',
                'price' => 28000,
                'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=300&auto=format&fit=crop',
                'recipe' => [
                    ['ingredient' => 'Biji Kopi House Blend', 'qty' => 18],
                    ['ingredient' => 'Susu Fresh Milk', 'qty' => 200],
                    ['ingredient' => 'Cup Kertas Panas', 'qty' => 1],
                ]
            ],
            [
                'name' => 'Iced Americano',
                'category_id' => $catKopi->id,
                'description' => 'Double shot espresso dengan air dan es batu',
                'price' => 25000,
                'image' => 'https://images.unsplash.com/photo-1551030173-122aabc4489c?q=80&w=300&auto=format&fit=crop',
                'recipe' => [
                    ['ingredient' => 'Biji Kopi House Blend', 'qty' => 18],
                    ['ingredient' => 'Cup Es Plastik', 'qty' => 1],
                ]
            ],
            [
                'name' => 'Caramel Macchiato (Iced)',
                'category_id' => $catKopi->id,
                'description' => 'Paduan vanilla, espresso, susu, dan saus karamel',
                'price' => 35000,
                'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=300&auto=format&fit=crop',
                'recipe' => [
                    ['ingredient' => 'Biji Kopi House Blend', 'qty' => 18],
                    ['ingredient' => 'Susu Fresh Milk', 'qty' => 180],
                    ['ingredient' => 'Sirup Vanilla', 'qty' => 15],
                    ['ingredient' => 'Saus Karamel', 'qty' => 15],
                    ['ingredient' => 'Cup Es Plastik', 'qty' => 1],
                ]
            ],
            [
                'name' => 'Manual Brew V60',
                'category_id' => $catKopi->id,
                'description' => 'Kopi seduh manual menggunakan beans Single Origin',
                'price' => 32000,
                'image' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=300&auto=format&fit=crop',
                'recipe' => [
                    ['ingredient' => 'Biji Kopi Single Origin', 'qty' => 15],
                    ['ingredient' => 'Cup Kertas Panas', 'qty' => 1],
                ]
            ],
            [
                'name' => 'Matcha Latte (Iced)',
                'category_id' => $catNonKopi->id,
                'description' => 'Premium matcha Jepang dengan susu segar',
                'price' => 30000,
                'image' => 'https://images.unsplash.com/photo-1515823662972-da6a2e4d3002?q=80&w=300&auto=format&fit=crop',
                'recipe' => [
                    ['ingredient' => 'Bubuk Matcha Premium', 'qty' => 15],
                    ['ingredient' => 'Susu Fresh Milk', 'qty' => 200],
                    ['ingredient' => 'Cup Es Plastik', 'qty' => 1],
                ]
            ],
            [
                'name' => 'Signature Iced Chocolate',
                'category_id' => $catNonKopi->id,
                'description' => 'Cokelat kaya rasa dengan susu segar',
                'price' => 32000,
                'image' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?q=80&w=300&auto=format&fit=crop',
                'recipe' => [
                    ['ingredient' => 'Bubuk Cokelat', 'qty' => 30],
                    ['ingredient' => 'Susu Fresh Milk', 'qty' => 200],
                    ['ingredient' => 'Cup Es Plastik', 'qty' => 1],
                ]
            ],
            [
                'name' => 'Oat Milk Latte',
                'category_id' => $catKopi->id,
                'description' => 'Espresso dipadukan dengan creamy oat milk',
                'price' => 38000,
                'image' => 'https://images.unsplash.com/photo-1595859703065-4d372e9d2906?q=80&w=300&auto=format&fit=crop',
                'recipe' => [
                    ['ingredient' => 'Biji Kopi House Blend', 'qty' => 18],
                    ['ingredient' => 'Susu Oat (Oat Milk)', 'qty' => 200],
                    ['ingredient' => 'Cup Kertas Panas', 'qty' => 1],
                ]
            ],
            [
                'name' => 'Butter Croissant',
                'category_id' => $catPastry->id,
                'description' => 'Croissant renyah dengan butter asli',
                'price' => 22000,
                'image' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?q=80&w=300&auto=format&fit=crop',
                'recipe' => [
                    ['ingredient' => 'Butter Croissant', 'qty' => 1],
                ]
            ],
            [
                'name' => 'Fudgy Brownie',
                'category_id' => $catPastry->id,
                'description' => 'Brownie cokelat pekat dan lembut',
                'price' => 25000,
                'image' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?q=80&w=300&auto=format&fit=crop',
                'recipe' => [
                    ['ingredient' => 'Chocolate Brownie', 'qty' => 1],
                ]
            ],
        ];

        foreach ($productsData as $prodData) {
            $product = Product::firstOrCreate(
                ['name' => $prodData['name']],
                [
                    'category_id' => $prodData['category_id'],
                    'description' => $prodData['description'],
                    'price' => $prodData['price'],
                    'image' => $prodData['image'],
                    'is_active' => true,
                ]
            );

            // Create Recipe for Product
            $recipe = Recipe::firstOrCreate(
                ['product_id' => $product->id],
                ['name' => 'Resep ' . $product->name]
            );

            // Attach ingredients to recipe
            foreach ($prodData['recipe'] as $recipeItem) {
                if (isset($ingredients[$recipeItem['ingredient']])) {
                    RecipeIngredient::firstOrCreate(
                        [
                            'recipe_id' => $recipe->id,
                            'ingredient_id' => $ingredients[$recipeItem['ingredient']]->id,
                        ],
                        ['quantity' => $recipeItem['qty']]
                    );
                }
            }
        }
    }
}