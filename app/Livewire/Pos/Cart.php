<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\StockMovement;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Cart extends Component
{
    public $items = [];
    public $subtotal = 0;
    public $taxAmount = 0;
    public $discountAmount = 0;
    public $total = 0;

    public $orderType = 'dine-in';
    public $tableId = null;

    protected $listeners = ['productAdded' => 'addToCart'];

    public function addToCart($product)
    {
        $found = false;
        foreach ($this->items as &$item) {
            if ($item['product_id'] == $product['id']) {
                $item['quantity']++;
                $item['subtotal'] = $item['quantity'] * $item['price'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->items[] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'subtotal' => $product['price'],
                'notes' => '',
                'image' => $product['image'] ?? 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=100&auto=format&fit=crop'
            ];
        }

        $this->calculateTotals();
    }

    public function incrementQuantity($index)
    {
        $this->items[$index]['quantity']++;
        $this->items[$index]['subtotal'] = $this->items[$index]['quantity'] * $this->items[$index]['price'];
        $this->calculateTotals();
    }

    public function decrementQuantity($index)
    {
        if ($this->items[$index]['quantity'] > 1) {
            $this->items[$index]['quantity']--;
            $this->items[$index]['subtotal'] = $this->items[$index]['quantity'] * $this->items[$index]['price'];
        } else {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
        $this->calculateTotals();
    }

    public function clearCart()
    {
        $this->items = [];
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = array_sum(array_column($this->items, 'subtotal'));

        $settings = Setting::query()
            ->whereIn('key', ['tax_enabled', 'tax_percentage'])
            ->pluck('value', 'key');

        $taxEnabled = filter_var($settings->get('tax_enabled', false), FILTER_VALIDATE_BOOLEAN);
        $taxRate = max(0, (float) $settings->get('tax_percentage', 0)) / 100;

        $this->taxAmount = $taxEnabled ? round($this->subtotal * $taxRate, 2) : 0;
        $this->total = max(0, $this->subtotal + $this->taxAmount - $this->discountAmount);
    }

    public function processCheckout()
    {
        if (empty($this->items)) {
            return;
        }

        if ($this->orderType === 'dine-in' && empty($this->tableId)) {
            // Need to select table
            return;
        }

        $activeShift = \App\Models\Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$activeShift) {
            session()->flash('error', 'Tidak ada shift kasir yang aktif. Buka shift terlebih dahulu.');
            return;
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => auth()->id() ?? 1, // Fallback if not authenticated in dev
                'shift_id' => $activeShift->id,
                'table_id' => $this->orderType === 'dine-in' ? $this->tableId : null,
                'type' => $this->orderType,
                'status' => 'pending',
                'payment_status' => 'paid',
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->taxAmount,
                'discount_amount' => $this->discountAmount,
                'total' => $this->total,
            ]);

            foreach ($this->items as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'notes' => $item['notes'],
                    'status' => 'pending'
                ]);

                // Deduct stock based on recipe (BOM)
                $recipe = Recipe::with('ingredients')->where('product_id', $item['product_id'])->first();

                if ($recipe && $recipe->ingredients) {
                    foreach ($recipe->ingredients as $recipeIngredient) {
                        $qtyToDeduct = $recipeIngredient->quantity * $item['quantity'];

                        $ingredient = Ingredient::query()
                            ->lockForUpdate()
                            ->find($recipeIngredient->ingredient_id);

                        if (! $ingredient) {
                            throw new \RuntimeException('Bahan baku untuk resep tidak ditemukan.');
                        }

                        if ((float) $ingredient->current_stock < $qtyToDeduct) {
                            throw new \RuntimeException('Stok '.$ingredient->name.' tidak mencukupi.');
                        }

                        $ingredient->decrement('current_stock', $qtyToDeduct);

                        StockMovement::create([
                            'ingredient_id' => $ingredient->id,
                            'user_id' => auth()->id(),
                            'type' => 'out',
                            'quantity' => $qtyToDeduct,
                            'reference_type' => OrderItem::class,
                            'reference_id' => $orderItem->id,
                            'notes' => 'Penjualan POS - ' . $order->order_number
                        ]);
                    }
                }
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'cash', // Default to cash for now
                'amount' => $this->total,
                'status' => 'success',
            ]);

            DB::commit();

            // Set data for printing
            $this->dispatch('printReceipt', order_id: $order->id);

            $this->clearCart();
            // Optional: Dispatch event for KDS (Kitchen Display System) update
            $this->dispatch('orderCompleted', order_id: $order->id);

            // Notification or flash message here (e.g. sweetalert)
            session()->flash('message', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pos.cart');
    }
}
