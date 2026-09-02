<?php

namespace App\Livewire\Admin\Master;

use Livewire\Component;
use App\Models\Category;
use App\Models\Table;
use App\Models\Supplier;

class Index extends Component
{
    public $activeTab = 'categories';
    
    // Category Modal
    public $showCategoryModal = false;
    public $categoryId, $categoryName, $categoryDesc;

    // Table Modal
    public $showTableModal = false;
    public $tableId, $tableNumber, $tableStatus = 'available';

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    // --- Category Logic ---
    public function openCategoryModal($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $cat = Category::findOrFail($id);
            $this->categoryId = $cat->id;
            $this->categoryName = $cat->name;
            $this->categoryDesc = $cat->description;
        } else {
            $this->reset(['categoryId', 'categoryName', 'categoryDesc']);
        }
        $this->showCategoryModal = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'categoryName' => 'required|string|max:255',
            'categoryDesc' => 'nullable|string',
        ]);

        Category::updateOrCreate(
            ['id' => $this->categoryId],
            ['name' => $this->categoryName, 'description' => $this->categoryDesc]
        );

        $this->showCategoryModal = false;
        session()->flash('message', 'Kategori berhasil disimpan.');
    }

    // --- Table Logic ---
    public function openTableModal($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $table = Table::findOrFail($id);
            $this->tableId = $table->id;
            $this->tableNumber = $table->number;
            $this->tableStatus = $table->status;
        } else {
            $this->reset(['tableId', 'tableNumber']);
            $this->tableStatus = 'available';
        }
        $this->showTableModal = true;
    }

    public function saveTable()
    {
        $this->validate([
            'tableNumber' => 'required|string|max:255',
            'tableStatus' => 'required|in:available,occupied',
        ]);

        Table::updateOrCreate(
            ['id' => $this->tableId],
            ['number' => $this->tableNumber, 'status' => $this->tableStatus]
        );

        $this->showTableModal = false;
        session()->flash('message', 'Meja berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.master.index', [
            'categories' => Category::all(),
            'tables' => Table::orderBy('number')->get(),
        ]);
    }
}