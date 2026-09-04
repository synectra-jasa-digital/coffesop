<?php

namespace Tests\Feature\Menu;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DigitalMenuTest extends TestCase
{
    use RefreshDatabase;

    protected Table $table;
    protected Product $product;
    protected Ingredient $ingredient;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create(['name' => 'Kopi', 'description' => 'Minuman kopi']);
        $this->table = Table::create(['number' => 'Meja 01', 'status' => 'available']);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Es Kopi',
            'description' => 'Kopi dingin',
            'price' => 25000,
            'is_active' => true,
        ]);

        $this->ingredient = Ingredient::create([
            'name' => 'Biji Kopi',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 1000,
        ]);

        $recipe = Recipe::create([
            'product_id' => $this->product->id,
            'name' => 'Resep Es Kopi',
        ]);

        RecipeIngredient::create([
            'recipe_id' => $recipe->id,
            'ingredient_id' => $this->ingredient->id,
            'quantity' => 18,
        ]);

        // Tax settings
        \App\Models\Setting::updateOrCreate(['key' => 'tax_enabled'], ['value' => 'true', 'type' => 'boolean', 'group' => 'tax']);
        \App\Models\Setting::updateOrCreate(['key' => 'tax_percentage'], ['value' => '11', 'type' => 'number', 'group' => 'tax']);
    }

    public function test_public_menu_page_loads_for_valid_table(): void
    {
        $response = $this->get('/menu/' . $this->table->number);
        $response->assertStatus(200);
    }

    public function test_public_menu_returns_404_for_invalid_table(): void
    {
        $response = $this->get('/menu/Meja99');
        $response->assertStatus(404);
    }

    public function test_customer_can_browse_and_add_to_cart(): void
    {
        $component = Livewire::test(\App\Livewire\Menu\DigitalMenu::class, ['tableNumber' => 'Meja 01'])
            ->call('addToCart', $this->product->id);

        $this->assertCount(1, $component->get('cart'));
        $this->assertEquals($this->product->id, $component->get('cart')[0]['product_id']);
        $this->assertEquals(1, $component->get('cart')[0]['quantity']);
    }

    public function test_duplicate_add_increments_quantity(): void
    {
        $component = Livewire::test(\App\Livewire\Menu\DigitalMenu::class, ['tableNumber' => 'Meja 01'])
            ->call('addToCart', $this->product->id)
            ->call('addToCart', $this->product->id);

        $this->assertCount(1, $component->get('cart'));
        $this->assertEquals(2, $component->get('cart')[0]['quantity']);
        $this->assertEquals(50000, $component->get('cart')[0]['subtotal']);
    }

    public function test_place_order_creates_order_and_deducts_stock(): void
    {
        $stockBefore = (float) $this->ingredient->fresh()->current_stock;

        Livewire::test(\App\Livewire\Menu\DigitalMenu::class, ['tableNumber' => 'Meja 01'])
            ->call('addToCart', $this->product->id)
            ->call('placeOrder');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);

        $order = Order::first();
        $this->assertEquals('qr-order', $order->type);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals($this->table->id, $order->table_id);
        $this->assertStringStartsWith('QR-', $order->order_number);

        // Stock deducted by recipe quantity.
        $this->assertEquals($stockBefore - 18, (float) $this->ingredient->fresh()->current_stock);
    }

    public function test_place_empty_cart_is_noop(): void
    {
        Livewire::test(\App\Livewire\Menu\DigitalMenu::class, ['tableNumber' => 'Meja 01'])
            ->call('placeOrder');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_decrement_removes_item_when_quantity_is_one(): void
    {
        $component = Livewire::test(\App\Livewire\Menu\DigitalMenu::class, ['tableNumber' => 'Meja 01'])
            ->call('addToCart', $this->product->id);

        $this->assertCount(1, $component->get('cart'));

        $component->call('decrementQuantity', 0);
        $this->assertCount(0, $component->get('cart'));
    }
}