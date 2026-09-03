<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\ActivityLog;
use Carbon\Carbon;

class History extends Component
{
    use WithPagination;

    public $dateFilter;
    public $search = '';

    // Modal Details
    public $showDetailModal = false;
    public $selectedOrder = null;

    // Void Modal
    public $showVoidModal = false;
    public $voidNotes = '';
    public $voidManagerCode = '';

    public function mount()
    {
        $this->dateFilter = Carbon::today()->format('Y-m-d');
    }

    public function openDetail($id)
    {
        $this->selectedOrder = Order::with(['items.product', 'user', 'table'])->find($id);
        $this->showDetailModal = true;
    }

    public function openVoid($id)
    {
        $this->selectedOrder = Order::find($id);
        $this->voidNotes = '';
        $this->voidManagerCode = '';
        $this->showDetailModal = false;
        $this->showVoidModal = true;
    }

    /**
     * Void a transaction.
     *
     * Rules (PRD 7.1):
     * - A reason is mandatory (min 5 chars).
     * - Only Manager/Supervisor or Owner/Admin may void directly.
     * - Cashiers may only void with a valid manager approval code.
     */
    public function processVoid()
    {
        $this->validate([
            'voidNotes' => 'required|string|min:5',
        ]);

        if (! $this->selectedOrder || $this->selectedOrder->status === 'cancelled') {
            return;
        }

        $user = auth()->user();
        $isManager = $user && ($user->hasRole('Owner/Admin') || $user->hasRole('Manager/Supervisor'));

        if (! $isManager) {
            $expectedCode = (string) config('app.discount_manager_code', 'MANAGER123');
            if (! hash_equals($expectedCode, (string) $this->voidManagerCode)) {
                session()->flash('error', 'Kode Manager tidak valid. Void tidak dapat diproses.');
                return;
            }
        }

        $order = $this->selectedOrder;
        $oldStatus = $order->status;
        $order->update([
            'status' => 'cancelled',
            'notes' => 'VOID: ' . $this->voidNotes . ' | by: ' . ($user->name ?? 'system'),
        ]);

        // Restore stock for each item based on its recipe (BOM).
        foreach ($order->items as $item) {
            $recipe = \App\Models\Recipe::with('ingredients')
                ->where('product_id', $item->product_id)
                ->first();

            if ($recipe && $recipe->ingredients) {
                foreach ($recipe->ingredients as $recipeIngredient) {
                    $qtyToRestore = $recipeIngredient->quantity * $item->quantity;
                    $ingredient = \App\Models\Ingredient::find($recipeIngredient->ingredient_id);
                    if ($ingredient) {
                        $ingredient->increment('current_stock', $qtyToRestore);

                        \App\Models\StockMovement::create([
                            'ingredient_id' => $ingredient->id,
                            'user_id' => auth()->id(),
                            'type' => 'in',
                            'quantity' => $qtyToRestore,
                            'reference_type' => \App\Models\OrderItem::class,
                            'reference_id' => $item->id,
                            'notes' => 'Pengembalian stok dari void: ' . $order->order_number,
                        ]);
                    }
                }
            }
        }

        session()->flash('message', 'Transaksi sudah dibatalkan (void). Stok sudah dikembalikan.');
        ActivityLog::log('void_transaction', $order, ['status' => $oldStatus], [
            'status' => 'cancelled',
            'reason' => $this->voidNotes,
            'approved_by' => $isManager ? 'self' : 'manager_code',
        ]);

        $this->showVoidModal = false;
    }

    public function render()
    {
        $query = Order::with('user', 'table')
            ->whereDate('created_at', $this->dateFilter)
            ->orderBy('created_at', 'desc');

        if (!empty($this->search)) {
            $query->where('order_number', 'like', '%' . $this->search . '%');
        }

        return view('livewire.pos.history', [
            'orders' => $query->paginate(15)
        ]);
    }
}