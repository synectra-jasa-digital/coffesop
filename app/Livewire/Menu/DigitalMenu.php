<?php

namespace App\Livewire\Menu;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Category;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\StockMovement;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use App\Events\OrderCreated;

class DigitalMenu extends Component
{
    public $tableNumber;
    public $tableId;
    public $activeCategory = 'Semua';
    public $cart = [];
    public $notes = [];
    public $orderPlaced = false;
    public $orderNumber;

    public function mount($tableNumber)
    {
        $this->tableNumber = $tableNumber;
        $table = Table::where('number', $tableNumber)->first();
        if (! $table) {
            abort(404, 'Meja tidak ditemukan.');
        }
        $this->tableId = $table->id;
    }

    public function addToCart($productId)
    {
        $product = Product::where('id', $productId)->where('is_active', true)->first();
        if (! $product) {
            return;
        }

        foreach ($this->cart as &$item) {
            if ($item['product_id'] == $product->id) {
                $item['quantity']++;
                $item['subtotal'] = $item['quantity'] * $item['price'];
                return;
            }
        }

        $this->cart[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'image' => $product->image,
            'quantity' => 1,
            'subtotal' => (float) $product->price,
            'notes' => '',
        ];
    }

    public function incrementQuantity($index)
    {
        if (! isset($this->cart[$index])) {
            return;
        }
        $this->cart[$index]['quantity']++;
        $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['price'];
    }

    public function decrementQuantity($index)
    {
        if (! isset($this->cart[$index])) {
            return;
        }
        if ($this->cart[$index]['quantity'] > 1) {
            $this->cart[$index]['quantity']--;
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $this->cart[$index]['price'];
        } else {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
        }
    }

    public function updateNotes($index, $value)
    {
        if (isset($this->cart[$index])) {
            $this->cart[$index]['notes'] = $value;
        }
    }

    public function placeOrder()
    {
        if (empty($this->cart)) {
            return;
        }

        $subtotal = array_sum(array_column($this->cart, 'subtotal'));

        $settings = \App\Models\Setting::query()
            ->whereIn('key', ['tax_enabled', 'tax_percentage'])
            ->pluck('value', 'key');

        $taxEnabled = filter_var($settings->get('tax_enabled', false), FILTER_VALIDATE_BOOLEAN);
        $taxRate = max(0, (float) $settings->get('tax_percentage', 0)) / 100;
        $tax = $taxEnabled ? round($subtotal * $taxRate, 2) : 0;
        $total = $subtotal + $tax;

        $order = Order::create([
            'order_number' => 'QR-' . strtoupper(Str::random(8)),
            'user_id' => null,
            'shift_id' => null,
            'table_id' => $this->tableId,
            'type' => 'qr-order',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'service_charge_amount' => 0,
            'discount_amount' => 0,
            'total' => $total,
            'notes' => 'QR order dari meja ' . $this->tableNumber,
        ]);

        foreach ($this->cart as $item) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
                'notes' => $item['notes'] ?: null,
                'status' => 'pending',
            ]);

            // Deduct stock based on recipe (BOM).
            $recipe = Recipe::with('ingredients')->where('product_id', $item['product_id'])->first();

            if ($recipe && $recipe->ingredients) {
                foreach ($recipe->ingredients as $recipeIngredient) {
                    $qtyToDeduct = $recipeIngredient->quantity * $item['quantity'];
                    $ingredient = Ingredient::find($recipeIngredient->ingredient_id);

                    if ($ingredient) {
                        $ingredient->decrement('current_stock', $qtyToDeduct);

                        StockMovement::create([
                            'ingredient_id' => $ingredient->id,
                            'user_id' => null,
                            'type' => 'out',
                            'quantity' => $qtyToDeduct,
                            'reference_type' => OrderItem::class,
                            'reference_id' => $orderItem->id,
                            'notes' => 'QR Order - ' . $order->order_number,
                        ]);
                    }
                }
            }
        }

        broadcast(new OrderCreated($order->load(['items.product', 'table'])))->toOthers();

        $this->orderNumber = $order->order_number;
        $this->orderPlaced = true;
        $this->cart = [];
        $this->notes = [];
    }

    public function getSubtotalProperty(): float
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }

    public function getTotalProperty(): float
    {
        $subtotal = $this->getSubtotalProperty();
        $settings = \App\Models\Setting::query()
            ->whereIn('key', ['tax_enabled', 'tax_percentage'])
            ->pluck('value', 'key');

        $taxEnabled = filter_var($settings->get('tax_enabled', false), FILTER_VALIDATE_BOOLEAN);
        $taxRate = max(0, (float) $settings->get('tax_percentage', 0)) / 100;
        $tax = $taxEnabled ? round($subtotal * $taxRate, 2) : 0;

        return $subtotal + $tax;
    }

    public function getCartCountProperty(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    public function render()
    {
        $categories = Category::has('products')->with('products')->orderBy('name')->get();
        $allProducts = Product::where('is_active', true)->with('category')->get();

        return view('livewire.menu.digital-menu', [
            'categories' => $categories,
            'products' => $allProducts,
        ])->layout('layouts.menu');
    }
}