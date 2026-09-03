<?php

namespace App\Livewire\Admin\Discounts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Discount;
use App\Models\ActivityLog;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditing = false;

    public $discountId;
    public $name;
    public $code;
    public $type = 'percentage';
    public $value = 0;
    public $startDate;
    public $endDate;
    public $isActive = true;
    public $minimumPurchase = 0;

    public function mount()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['discountId', 'name', 'code', 'value', 'endDate']);
        $this->type = 'percentage';
        $this->isActive = true;
        $this->minimumPurchase = 0;
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $discount = Discount::findOrFail($id);

        $this->discountId = $discount->id;
        $this->name = $discount->name;
        $this->code = $discount->code;
        $this->type = $discount->type;
        $this->value = $discount->value;
        $this->startDate = $discount->start_date?->format('Y-m-d');
        $this->endDate = $discount->end_date?->format('Y-m-d');
        $this->isActive = $discount->is_active;
        $this->minimumPurchase = $discount->minimum_purchase;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $codeRule = 'nullable|string|max:50';
        if (!empty($this->code)) {
            $codeRule .= '|unique:discounts,code';
            if ($this->isEditing) {
                $codeRule .= ',' . $this->discountId;
            }
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'code' => $codeRule,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'isActive' => 'boolean',
            'minimumPurchase' => 'nullable|numeric|min:0',
        ]);

        $data = [
            'name' => $this->name,
            'code' => $this->code ? strtoupper($this->code) : null,
            'type' => $this->type,
            'value' => $this->value,
            'start_date' => $this->startDate ?: null,
            'end_date' => $this->endDate ?: null,
            'is_active' => $this->isActive,
            'minimum_purchase' => $this->minimumPurchase ?: 0,
        ];

        if ($this->isEditing) {
            $discount = Discount::find($this->discountId);
            $oldData = $discount->only(['name', 'code', 'type', 'value', 'start_date', 'end_date', 'is_active', 'minimum_purchase']);
            $discount->update($data);
            session()->flash('message', 'Promo sudah diupdate.');
            ActivityLog::log('update_promo', $discount, $oldData, $data);
        } else {
            $discount = Discount::create($data);
            session()->flash('message', 'Promo sudah ditambahkan.');
            ActivityLog::log('create_promo', $discount, null, $data);
        }

        $this->showModal = false;
    }

    public function toggleStatus($id)
    {
        $discount = Discount::findOrFail($id);
        $oldActive = $discount->is_active;
        $discount->update(['is_active' => !$discount->is_active]);
        session()->flash('message', 'Status promo diperbarui.');
        ActivityLog::log('toggle_promo_status', $discount, ['is_active' => $oldActive], ['is_active' => $discount->is_active]);
    }

    public function delete($id)
    {
        $discount = Discount::find($id);
        if (! $discount) {
            return;
        }
        $discountData = $discount->only(['name', 'code', 'type', 'value', 'start_date', 'end_date', 'is_active', 'minimum_purchase']);
        $discount->delete();
        session()->flash('message', 'Promo sudah dihapus.');
        ActivityLog::log('delete_promo', $discount, $discountData, null);
    }

    public function render()
    {
        $discounts = Discount::orderBy('start_date', 'desc')->paginate(10);

        return view('livewire.admin.discounts.index', [
            'discounts' => $discounts,
        ]);
    }
}