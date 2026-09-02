<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\RecipeIngredient;
use Illuminate\Support\Facades\DB;

class RecipeManager extends Component
{
    public $product;
    public $recipe;
    
    // For adding new ingredients to recipe
    public $selectedIngredient = '';
    public $quantity = '';
    
    public function mount(Product $product)
    {
        $this->product = $product;
        
        // Find or create recipe for this product
        $this->recipe = Recipe::firstOrCreate(
            ['product_id' => $product->id],
            ['name' => 'Resep ' . $product->name]
        );
    }

    public function addIngredient()
    {
        $this->validate([
            'selectedIngredient' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.1',
        ]);

        // Check if ingredient already in recipe
        $existing = RecipeIngredient::where('recipe_id', $this->recipe->id)
            ->where('ingredient_id', $this->selectedIngredient)
            ->first();

        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + $this->quantity]);
        } else {
            RecipeIngredient::create([
                'recipe_id' => $this->recipe->id,
                'ingredient_id' => $this->selectedIngredient,
                'quantity' => $this->quantity,
            ]);
        }

        $this->reset(['selectedIngredient', 'quantity']);
        session()->flash('message', 'Bahan baku berhasil ditambahkan ke resep.');
    }

    public function removeIngredient($id)
    {
        RecipeIngredient::where('id', $id)->delete();
        session()->flash('message', 'Bahan baku dihapus dari resep.');
    }

    public function render()
    {
        $availableIngredients = Ingredient::orderBy('name')->get();
        $recipeIngredients = RecipeIngredient::with('ingredient')
            ->where('recipe_id', $this->recipe->id)
            ->get();

        return view('livewire.admin.products.recipe-manager', [
            'availableIngredients' => $availableIngredients,
            'recipeIngredients' => $recipeIngredients
        ]);
    }
}