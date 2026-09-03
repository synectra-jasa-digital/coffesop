<?php

namespace App\Livewire\Admin\Inventory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ingredient;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\ActivityLog;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    // Stock In
    public $showStockInModal = false;
    public $stockInIngredientId;
    public $stockInQuantity;
    public $stockInNotes;

    // Stock Opname
    public $showOpnameModal = false;
    public $opnameData = []; // ['ingredient_id' => actual_stock]
    public $opnameNotes;

    // Add New Ingredient
    public $showAddModal = false;
    public $newIngName, $newIngUnit, $newIngMinStock;

    protected $rules = [
        'stockInIngredientId' => 'required|exists:ingredients,id',
        'stockInQuantity' => 'required|numeric|min:0.1',
        'stockInNotes' => 'nullable|string',
    ];

    public function openAddIngredient()
    {
        $this->reset(['newIngName', 'newIngUnit', 'newIngMinStock']);
        $this->resetValidation();
        $this->showAddModal = true;
    }

    public function saveNewIngredient()
    {
        $this->validate([
            'newIngName' => 'required|string|max:255|unique:ingredients,name',
            'newIngUnit' => 'required|string|max:50',
            'newIngMinStock' => 'required|numeric|min:0',
        ]);

        $ingredient = Ingredient::create([
            'name' => $this->newIngName,
            'unit' => $this->newIngUnit,
            'minimum_stock' => $this->newIngMinStock,
            'current_stock' => 0,
        ]);

        session()->flash('message', 'Bahan baku baru sudah ditambahkan.');
        ActivityLog::log('create_ingredient', $ingredient, null, $ingredient->only(['name', 'unit', 'minimum_stock', 'current_stock']));
        $this->showAddModal = false;
    }

    public function openStockIn()
    {
        $this->reset(['stockInIngredientId', 'stockInQuantity', 'stockInNotes']);
        $this->resetValidation();
        $this->showStockInModal = true;
    }

    public function processStockIn()
    {
        $this->validate([
            'stockInIngredientId' => 'required|exists:ingredients,id',
            'stockInQuantity' => 'required|numeric|min:0.1',
            'stockInNotes' => 'nullable|string',
        ]);

        $ingredient = Ingredient::find($this->stockInIngredientId);
        $before = (float) $ingredient->current_stock;
        $ingredient->increment('current_stock', $this->stockInQuantity);

        StockMovement::create([
            'ingredient_id' => $this->stockInIngredientId,
            'user_id' => auth()->id(),
            'type' => 'in',
            'quantity' => $this->stockInQuantity,
            'notes' => $this->stockInNotes ?: 'Penerimaan barang'
        ]);

        session()->flash('message', 'Stok masuk sudah dicatat.');
        ActivityLog::log('stock_in', $ingredient, ['current_stock' => $before], ['current_stock' => (float) $ingredient->current_stock]);
        $this->showStockInModal = false;
    }

    public function openOpname()
    {
        $ingredients = Ingredient::all();
        $this->opnameData = [];
        foreach ($ingredients as $ing) {
            $this->opnameData[$ing->id] = $ing->current_stock; // prefill physical stock same as current
        }
        $this->opnameNotes = '';
        $this->showOpnameModal = true;
    }

    public function processOpname()
    {
        $opname = StockOpname::create([
            'user_id' => auth()->id(),
            'opname_date' => Carbon::now(),
            'status' => 'approved', // Auto approved for MVP
            'notes' => $this->opnameNotes
        ]);

        foreach ($this->opnameData as $ingId => $physicalQty) {
            $ingredient = Ingredient::find($ingId);
            $systemQty = $ingredient->current_stock;
            $difference = $physicalQty - $systemQty;

            if ($difference != 0) {
                StockOpnameDetail::create([
                    'stock_opname_id' => $opname->id,
                    'ingredient_id' => $ingId,
                    'system_stock' => $systemQty,
                    'actual_stock' => $physicalQty,
                    'difference' => $difference,
                ]);

                // Update physical stock
                $ingredient->update(['current_stock' => $physicalQty]);

                // Record movement
                StockMovement::create([
                    'ingredient_id' => $ingId,
                    'user_id' => auth()->id(),
                    'type' => 'adjustment',
                    'quantity' => abs($difference),
                    'reference_type' => StockOpname::class,
                    'reference_id' => $opname->id,
                    'notes' => 'Penyesuaian stok opname: ' . ($difference > 0 ? '+' : '') . $difference
                ]);

                ActivityLog::log('stock_opname', $ingredient, ['current_stock' => $systemQty], ['current_stock' => $physicalQty]);
            }
        }

        session()->flash('message', 'Proses Stok Opname sudah disimpan.');
        $this->showOpnameModal = false;
    }

    public function render()
    {
        $ingredients = Ingredient::orderBy('name')->paginate(10);

        return view('livewire.admin.inventory.index', [
            'ingredients' => $ingredients
        ]);
    }
}