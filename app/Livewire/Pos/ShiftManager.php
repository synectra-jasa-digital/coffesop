<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Shift;
use App\Models\Order;
use Carbon\Carbon;

class ShiftManager extends Component
{
    public $activeShift = null;
    
    // For opening shift
    public $startingCash = 0;
    
    // For closing shift
    public $actualEndingCash = 0;
    public $closingNotes = '';
    
    // UI state
    public $showOpenModal = false;
    public $showCloseModal = false;

    public function mount()
    {
        $this->checkActiveShift();
    }

    public function checkActiveShift()
    {
        $this->activeShift = Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();
            
        // If there's no active shift on POS page load, trigger modal
        if (!$this->activeShift && request()->routeIs('pos.index')) {
            $this->showOpenModal = true;
        }
    }

    public function openShift()
    {
        $this->validate([
            'startingCash' => 'required|numeric|min:0'
        ]);

        $this->activeShift = Shift::create([
            'user_id' => auth()->id(),
            'start_time' => Carbon::now(),
            'starting_cash' => $this->startingCash,
            'status' => 'open'
        ]);

        $this->showOpenModal = false;
        
        // Notify POS components that shift is open
        $this->dispatch('shiftOpened', shift_id: $this->activeShift->id);
    }

    public function initiateCloseShift()
    {
        // Calculate expected cash
        if ($this->activeShift) {
            $cashSales = Order::where('shift_id', $this->activeShift->id)
                ->where('payment_status', 'paid')
                ->whereHas('payments', function($q) {
                    $q->where('payment_method', 'cash');
                })
                ->sum('total');
                
            $this->activeShift->expected_cash = $this->activeShift->starting_cash + $cashSales;
            $this->actualEndingCash = $this->activeShift->expected_cash; // default suggestion
            $this->showCloseModal = true;
        }
    }

    public function closeShift()
    {
        $this->validate([
            'actualEndingCash' => 'required|numeric|min:0'
        ]);

        if ($this->activeShift) {
            $difference = $this->actualEndingCash - $this->activeShift->expected_cash;

            $this->activeShift->update([
                'end_time' => Carbon::now(),
                'ending_cash' => $this->actualEndingCash,
                'difference' => $difference,
                'notes' => $this->closingNotes,
                'status' => 'closed'
            ]);

            $this->activeShift = null;
            $this->showCloseModal = false;
            
            return redirect()->route('dashboard')->with('message', 'Shift kasir telah ditutup.');
        }
    }

    public function render()
    {
        return view('livewire.pos.shift-manager');
    }
}