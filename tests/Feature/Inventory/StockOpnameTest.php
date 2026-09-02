<?php

namespace Tests\Feature\Inventory;

use App\Models\Ingredient;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StockOpnameTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        Role::firstOrCreate(['name' => 'Barista/Gudang']);

        $this->manager = User::factory()->create();
        $this->manager->assignRole('Manager/Supervisor');
    }

    public function test_opname_uses_actual_stock_column_and_adjusts_inventory(): void
    {
        $this->actingAs($this->manager);

        $ingredient = Ingredient::create([
            'name' => 'Biji Kopi',
            'unit' => 'gram',
            'minimum_stock' => 100,
            'current_stock' => 500, // system says 500, physical count says 420
        ]);

        Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
            ->call('openOpname')
            ->assertSet('showOpnameModal', true)
            ->set('opnameData', [$ingredient->id => 420])
            ->set('opnameNotes', 'Selisih stok')
            ->call('processOpname');

        $this->assertDatabaseHas('stock_opnames', [
            'status' => 'approved',
            'notes' => 'Selisih stok',
        ]);

        $opname = StockOpname::first();
        $this->assertNotNull($opname);

        // The detail row must be written using the actual_stock column.
        $this->assertDatabaseHas('stock_opname_details', [
            'stock_opname_id' => $opname->id,
            'ingredient_id' => $ingredient->id,
            'system_stock' => 500,
            'actual_stock' => 420,
            'difference' => -80,
        ]);

        // Inventory is reconciled to the physical count.
        $this->assertSame(420.0, (float) $ingredient->fresh()->current_stock);

        // An adjustment movement is recorded.
        $this->assertDatabaseHas('stock_movements', [
            'ingredient_id' => $ingredient->id,
            'type' => 'adjustment',
            'quantity' => 80,
            'reference_type' => StockOpname::class,
            'reference_id' => $opname->id,
        ]);
    }

    public function test_opname_with_no_difference_creates_no_detail_or_movement(): void
    {
        $this->actingAs($this->manager);

        $ingredient = Ingredient::create([
            'name' => 'Susu',
            'unit' => 'ml',
            'minimum_stock' => 100,
            'current_stock' => 300,
        ]);

        Livewire::test(\App\Livewire\Admin\Inventory\Index::class)
            ->set('opnameData', [$ingredient->id => 300])
            ->call('processOpname');

        $this->assertDatabaseCount('stock_opname_details', 0);
        $this->assertDatabaseCount('stock_movements', 0);
        $this->assertSame(300.0, (float) $ingredient->fresh()->current_stock);
    }
}