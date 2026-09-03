<?php

namespace Tests\Feature\Inventory;

use App\Models\Ingredient;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StockCostAndExpiryTest extends TestCase
{
    use RefreshDatabase;

    protected User $gudang;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        Role::firstOrCreate(['name' => 'Barista/Gudang']);

        $this->gudang = User::factory()->create();
        $this->gudang->assignRole('Barista/Gudang');
    }

    public function test_stock_in_records_unit_cost_and_expiry_date(): void
    {
        $this->actingAs($this->gudang);

        $ingredient = Ingredient::create([
            'name' => 'Biji Kopi',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 0,
        ]);

        Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
            ->call('openStockIn')
            ->set('stockInIngredientId', $ingredient->id)
            ->set('stockInQuantity', 500)
            ->set('stockInUnitCost', 25000)
            ->set('stockInExpiryDate', '2026-12-31')
            ->call('processStockIn');

        $this->assertSame(500.0, (float) $ingredient->fresh()->current_stock);

        $this->assertDatabaseHas('stock_movements', [
            'ingredient_id' => $ingredient->id,
            'type' => 'in',
            'quantity' => 500,
            'unit_cost' => 25000,
            'total_cost' => 12500000,
        ]);

        $movement = StockMovement::where('ingredient_id', $ingredient->id)->first();
        $this->assertNotNull($movement->expiry_date);
        $this->assertSame('2026-12-31', $movement->expiry_date->format('Y-m-d'));
    }

    public function test_stock_in_without_expiry_date_is_allowed(): void
    {
        $this->actingAs($this->gudang);

        $ingredient = Ingredient::create([
            'name' => 'Susu',
            'unit' => 'ml',
            'minimum_stock' => 100,
            'current_stock' => 0,
        ]);

        Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
            ->call('openStockIn')
            ->set('stockInIngredientId', $ingredient->id)
            ->set('stockInQuantity', 100)
            ->set('stockInUnitCost', 5000)
            ->set('stockInExpiryDate', '')
            ->call('processStockIn');

        $movement = StockMovement::where('ingredient_id', $ingredient->id)->first();
        $this->assertNull($movement->expiry_date);
    }
}