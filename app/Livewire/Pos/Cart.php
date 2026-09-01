<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Str;

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
        $this->taxAmount = $this->subtotal * 0.11; // 11% PPN
        $this->total = $this->subtotal + $this->taxAmount - $this->discountAmount;
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

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
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
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
                'notes' => $item['notes'],
                'status' => 'pending'
            ]);
            
            // Note: In real app, we would also trigger stock deduction based on Recipe/BOM here
        }

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'cash', // Default to cash for now
            'amount' => $this->total,
            'status' => 'success',
        ]);

        $this->clearCart();
        // Disini bisa tambahkan trigger print struk
    }

    public function render()
    {
        return view('livewire.pos.cart');
    }
}
