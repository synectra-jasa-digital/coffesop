<?php

namespace Tests\Feature\Pos;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DiscountTest extends TestCase
{
    use RefreshDatabase;

    protected User $kasir;
    protected User $manager;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        Role::firstOrCreate(['name' => 'Kasir']);

        $this->kasir = User::factory()->create();
        $this->kasir->assignRole('Kasir');

        $this->manager = User::factory()->create();
        $this->manager->assignRole('Manager/Supervisor');

        $category = Category::create(['name' => 'Kopi', 'description' => 'Minuman kopi']);
        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cafe Latte',
            'description' => 'Espresso dengan steamed milk',
            'price' => 28000,
            'is_active' => true,
        ]);

        $ingredient = Ingredient::create([
            'name' => 'Biji Kopi',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 10000,
        ]);

        $recipe = Recipe::create([
            'product_id' => $this->product->id,
            'name' => 'Resep '.$this->product->name,
        ]);

        RecipeIngredient::create([
            'recipe_id' => $recipe->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 18,
        ]);

        // Auto-approval limit: Rp 10.000.
        Setting::updateOrCreate(['key' => 'discount_auto_approval_limit'], ['value' => '10000', 'type' => 'number', 'group' => 'discount']);
        Setting::updateOrCreate(['key' => 'discount_require_manager_code'], ['value' => 'true', 'type' => 'boolean', 'group' => 'discount']);
    }

    public function test_small_discount_under_limit_is_allowed_for_cashier(): void
    {
        $this->actingAs($this->kasir);

        Shift::create([
            'user_id' => $this->kasir->id,
            'start_time' => Carbon::now(),
            'starting_cash' => 0,
            'status' => 'open',
        ]);

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        // Discount of 5000 is under the 10000 limit — should be accepted.
        $component->set('discountValue', 5000)
            ->call('applyDiscount')
            ->assertSet('discountAmount', 5000)
            ->assertSet('showDiscountModal', false);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'apply_discount',
        ]);
    }

    public function test_large_discount_above_limit_requires_manager_code(): void
    {
        $this->actingAs($this->kasir);

        Shift::create([
            'user_id' => $this->kasir->id,
            'start_time' => Carbon::now(),
            'starting_cash' => 0,
            'status' => 'open',
        ]);

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        // Discount of 20000 is above the 10000 limit — should be rejected without code.
        $component->set('discountValue', 20000)
            ->call('applyDiscount')
            ->assertSet('discountAmount', 0);

        // Now provide a valid manager code.
        config()->set('app.discount_manager_code', 'MANAGER123');
        $component->set('discountValue', 20000)
            ->set('managerCode', 'MANAGER123')
            ->call('applyDiscount')
            ->assertSet('discountAmount', 20000)
            ->assertSet('showDiscountModal', false);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'apply_discount',
        ]);
    }

    public function test_manager_can_apply_discount_above_limit_without_code(): void
    {
        $this->actingAs($this->manager);

        Shift::create([
            'user_id' => $this->manager->id,
            'start_time' => Carbon::now(),
            'starting_cash' => 0,
            'status' => 'open',
        ]);

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        // Manager role bypasses the code requirement.
        $component->set('discountValue', 20000)
            ->call('applyDiscount')
            ->assertSet('discountAmount', 20000)
            ->assertSet('showDiscountModal', false);
    }

    public function test_invalid_manager_code_is_rejected(): void
    {
        $this->actingAs($this->kasir);

        Shift::create([
            'user_id' => $this->kasir->id,
            'start_time' => Carbon::now(),
            'starting_cash' => 0,
            'status' => 'open',
        ]);

        config()->set('app.discount_manager_code', 'MANAGER123');

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        $component->set('discountValue', 20000)
            ->set('managerCode', 'WRONG-CODE')
            ->call('applyDiscount')
            ->assertSet('discountAmount', 0);
    }

    public function test_discount_cannot_exceed_subtotal(): void
    {
        $this->actingAs($this->kasir);

        Shift::create([
            'user_id' => $this->kasir->id,
            'start_time' => Carbon::now(),
            'starting_cash' => 0,
            'status' => 'open',
        ]);

        config()->set('app.discount_manager_code', 'MANAGER123');

        $component = Livewire::test(\App\Livewire\Pos\Cart::class)
            ->set('orderType', 'take-away')
            ->call('addToCart', [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => (float) $this->product->price,
                'image' => null,
            ]);

        // Discount larger than subtotal should be capped to the subtotal (28000).
        $component->set('discountValue', 999999)
            ->set('managerCode', 'MANAGER123')
            ->call('applyDiscount')
            ->assertSet('discountAmount', 28000);
    }
}