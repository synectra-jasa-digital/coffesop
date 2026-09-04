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
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Events\OrderCreated;
use App\Services\PaymentMethodService;

class Cart extends Component
{
    public $items = [];
    public $subtotal = 0;
    public $taxAmount = 0;
    public $serviceChargeAmount = 0;
    public $discountAmount = 0;
    public $total = 0;

    public $orderType = 'dine-in';
    public $tableId = null;

    // Discount flow
    public $discountValue = 0;
    public $discountNote = '';
    public $showDiscountModal = false;
    public $managerCode = '';

    // Payment / split-payment flow
    public $payments = [];
    public $showPaymentModal = false;
    public $paymentMethod = 'cash';
    public $paymentAmount;

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
        $this->discountAmount = 0;
        $this->payments = [];
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = array_sum(array_column($this->items, 'subtotal'));

        $settings = Setting::query()
            ->whereIn('key', ['tax_enabled', 'tax_percentage', 'service_charge_enabled', 'service_charge_percentage'])
            ->pluck('value', 'key');

        $taxEnabled = filter_var($settings->get('tax_enabled', false), FILTER_VALIDATE_BOOLEAN);
        $taxRate = max(0, (float) $settings->get('tax_percentage', 0)) / 100;

        $serviceEnabled = filter_var($settings->get('service_charge_enabled', false), FILTER_VALIDATE_BOOLEAN);
        $serviceRate = max(0, (float) $settings->get('service_charge_percentage', 0)) / 100;

        $this->taxAmount = $taxEnabled ? round($this->subtotal * $taxRate, 2) : 0;
        $this->serviceChargeAmount = $serviceEnabled ? round($this->subtotal * $serviceRate, 2) : 0;
        $this->total = max(0, $this->subtotal + $this->taxAmount + $this->serviceChargeAmount - $this->discountAmount);
    }

    /**
     * Open the discount modal. Cashiers can only apply discounts that are
     * within the auto-approval limit; anything above it requires a manager code.
     */
    public function openDiscountModal()
    {
        $this->reset(['discountValue', 'discountNote', 'managerCode']);
        $this->showDiscountModal = true;
    }

    /**
     * Apply a manual discount to the current cart.
     */
    public function applyDiscount()
    {
        $this->validate([
            'discountValue' => 'required|numeric|min:0.01',
            'discountNote' => 'nullable|string|max:255',
        ]);

        $settings = Setting::query()
            ->whereIn('key', ['discount_auto_approval_limit', 'discount_require_manager_code'])
            ->pluck('value', 'key');

        $limit = max(0, (float) $settings->get('discount_auto_approval_limit', 10000));
        $requireCode = filter_var($settings->get('discount_require_manager_code', true), FILTER_VALIDATE_BOOLEAN);

        $user = auth()->user();
        $isManager = $user && ($user->hasRole('Owner/Admin') || $user->hasRole('Manager/Supervisor'));

        $needsApproval = $this->discountValue > $limit;

        if ($needsApproval && ! $isManager) {
            if (! $requireCode) {
                // Code not required by configuration — allow.
            } elseif (empty($this->managerCode)) {
                $this->dispatch('notify', message: 'Diskon melebihi batas. Minta approval Manager terlebih dahulu.');
                $this->dispatch('focus-input', id: 'managerCode');
                return;
            } else {
                $expectedCode = config('app.discount_manager_code', 'MANAGER123');
                if (! hash_equals((string) $expectedCode, (string) $this->managerCode)) {
                    session()->flash('error', 'Kode Manager tidak valid.');
                    $this->dispatch('focus-input', id: 'managerCode');
                    return;
                }
            }
        }

        $this->discountAmount = min($this->discountValue, $this->subtotal);
        $this->calculateTotals();

        session()->flash('message', 'Diskon Rp '.number_format($this->discountAmount, 0, ',', '.').' diterapkan.');
        ActivityLog::log('apply_discount', null, null, [
            'amount' => $this->discountAmount,
            'note' => $this->discountNote,
            'requires_approval' => $needsApproval,
            'approved_by' => $isManager ? 'self' : 'manager_code',
        ]);

        $this->showDiscountModal = false;
    }

    public function removeDiscount()
    {
        $this->discountAmount = 0;
        $this->calculateTotals();
        session()->flash('message', 'Diskon dibatalkan.');
    }

    /**
     * Open the split-payment modal with the remaining balance as default amount.
     */
    public function openPaymentModal()
    {
        $this->reset(['paymentMethod', 'paymentAmount']);
        $this->paymentMethod = 'cash';
        $this->paymentAmount = $this->getRemainingPayment();
        $this->showPaymentModal = true;
    }

    /**
     * Add a payment line to the cart.
     */
    public function addPayment()
    {
        $enabledMethods = app(PaymentMethodService::class)->enabledMethods();

        $this->validate([
            'paymentMethod' => 'required|in:' . implode(',', $enabledMethods),
            'paymentAmount' => 'required|numeric|min:0.01',
        ]);

        $remaining = $this->getRemainingPayment();
        if ((float) $this->paymentAmount > $remaining + 0.01) {
            session()->flash('error', 'Jumlah pembayaran melebihi sisa tagihan.');
            return;
        }

        $this->payments[] = [
            'method' => $this->paymentMethod,
            'amount' => (float) $this->paymentAmount,
        ];

        $this->showPaymentModal = false;
        session()->flash('message', 'Pembayaran ' . $this->paymentMethodLabel() . ' ditambahkan.');
    }

    public function removePayment($index)
    {
        unset($this->payments[$index]);
        $this->payments = array_values($this->payments);
    }

    public function getPaidTotalProperty(): float
    {
        return array_sum(array_column($this->payments, 'amount'));
    }

    public function getRemainingPayment(): float
    {
        return max(0, $this->total - $this->getPaidTotalProperty());
    }

    public function getRemainingProperty(): float
    {
        return $this->getRemainingPayment();
    }

    protected function paymentMethodLabel(): string
    {
        return match ($this->paymentMethod) {
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'ewallet' => 'E-Wallet',
            'bank_transfer' => 'Transfer Bank',
            'card' => 'Kartu',
            default => ucfirst($this->paymentMethod),
        };
    }

    public function processCheckout()
    {
        if (empty($this->items)) {
            return;
        }

        if ($this->orderType === 'dine-in' && empty($this->tableId)) {
            return;
        }

        $activeShift = \App\Models\Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$activeShift) {
            session()->flash('error', 'Tidak ada shift kasir yang aktif. Buka shift terlebih dahulu.');
            return;
        }

        // Validate split payment: must total the order amount.
        $paid = $this->getPaidTotalProperty();
        if (empty($this->payments) || $paid + 0.01 < $this->total) {
            session()->flash('error', 'Pembayaran belum lengkap. Tambah metode pembayaran hingga menutup total.');
            return;
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => auth()->id() ?? 1,
                'shift_id' => $activeShift->id,
                'table_id' => $this->orderType === 'dine-in' ? $this->tableId : null,
                'type' => $this->orderType,
                'status' => 'pending',
                'payment_status' => 'paid',
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->taxAmount,
                'service_charge_amount' => $this->serviceChargeAmount,
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

            // Save each payment line.
            foreach ($this->payments as $line) {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $line['method'],
                    'amount' => $line['amount'],
                    'status' => 'success',
                ]);
            }

            DB::commit();

            $this->dispatch('print-receipt', order_id: $order->id);

            $this->clearCart();
            $this->dispatch('orderCompleted', order_id: $order->id);

            broadcast(new OrderCreated($order))->toOthers();

            ActivityLog::log('checkout', $order, null, [
                'order_number' => $order->order_number,
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->taxAmount,
                'service_charge_amount' => $this->serviceChargeAmount,
                'discount_amount' => $this->discountAmount,
                'total' => $this->total,
                'payment_methods' => array_column($this->payments, 'method'),
            ]);

            session()->flash('message', 'Transaksi sudah disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $enabledMethods = app(PaymentMethodService::class)->enabledMethods();
        $paymentMethods = array_map(
            fn ($m) => ['value' => $m, 'label' => app(PaymentMethodService::class)->label($m)],
            $enabledMethods
        );

        return view('livewire.pos.cart', [
            'paymentMethods' => $paymentMethods,
        ]);
    }
}