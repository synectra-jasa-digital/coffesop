<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductVariantTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('Owner/Admin');
    }

    public function test_variant_can_be_created_and_appears_under_product(): void
    {
        $this->actingAs($this->admin);

        $category = Category::create(['name' => 'Kopi', 'description' => 'Minuman kopi']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cafe Latte',
            'description' => 'Espresso dengan steamed milk',
            'price' => 28000,
            'is_active' => true,
        ]);

        Livewire::test(\App\Livewire\Admin\Products\Index::class)
            ->call('openVariantModal', $product->id)
            ->assertSet('showVariantModal', true)
            ->set('variantName', 'Ukuran')
            ->set('variantValue', 'Large')
            ->set('variantPriceAdjustment', 5000)
            ->call('saveVariant')
            ->assertSet('showVariantModal', false);

        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'name' => 'Ukuran',
            'value' => 'Large',
            'price_adjustment' => 5000,
        ]);

        // Activity log recorded.
        $this->assertDatabaseCount('activity_logs', 1);
        $this->assertSame('create_variant', ActivityLog::first()->action);
    }

    public function test_variant_can_be_edited_and_deleted(): void
    {
        $this->actingAs($this->admin);

        $category = Category::create(['name' => 'Kopi', 'description' => 'Minuman kopi']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Americano',
            'description' => 'Double shot espresso',
            'price' => 25000,
            'is_active' => true,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Suhu',
            'value' => 'Hot',
            'price_adjustment' => 0,
        ]);

        // Edit
        Livewire::test(\App\Livewire\Admin\Products\Index::class)
            ->call('editVariant', $variant->id)
            ->assertSet('variantName', 'Suhu')
            ->set('variantValue', 'Iced')
            ->set('variantPriceAdjustment', 3000)
            ->call('saveVariant')
            ->assertSet('showVariantModal', false);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'value' => 'Iced',
            'price_adjustment' => 3000,
        ]);

        // Delete
        Livewire::test(\App\Livewire\Admin\Products\Index::class)
            ->call('deleteVariant', $variant->id);

        $this->assertDatabaseMissing('product_variants', ['id' => $variant->id]);
    }

    public function test_variant_validation_rejects_missing_fields(): void
    {
        $this->actingAs($this->admin);

        $category = Category::create(['name' => 'Kopi', 'description' => 'Minuman kopi']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Matcha',
            'description' => 'Matcha latte',
            'price' => 30000,
            'is_active' => true,
        ]);

        Livewire::test(\App\Livewire\Admin\Products\Index::class)
            ->call('openVariantModal', $product->id)
            ->set('variantName', '')
            ->set('variantValue', '')
            ->set('variantPriceAdjustment', '')
            ->call('saveVariant')
            ->assertHasErrors(['variantName', 'variantValue', 'variantPriceAdjustment']);

        $this->assertDatabaseCount('product_variants', 0);
    }
}