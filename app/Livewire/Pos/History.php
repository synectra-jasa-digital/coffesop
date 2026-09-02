<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
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
        $this->showDetailModal = false;
        $this->showVoidModal = true;
    }

    public function processVoid()
    {
        $this->validate([
            'voidNotes' => 'required|string|min:5'
        ]);

        if ($this->selectedOrder && $this->selectedOrder->status !== 'void') {
            // Revert stock (simple implementation, real app needs robust stock reversion)
            // For MVP we just mark as void
            $this->selectedOrder->update([
                'status' => 'void',
                'notes' => 'VOID: ' . $this->voidNotes
            ]);

            session()->flash('message', 'Transaksi berhasil dibatalkan (void).');
            $this->showVoidModal = false;
        }
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