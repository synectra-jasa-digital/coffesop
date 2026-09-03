<?php

namespace App\Livewire\Admin\Suppliers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier;
use App\Models\ActivityLog;

class Index extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditing = false;

    // Form fields
    public $supplierId;
    public $name;
    public $contactName;
    public $phone;
    public $email;
    public $address;

    protected $rules = [
        'name' => 'required|string|max:255',
        'contactName' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string|max:500',
    ];

    public function create()
    {
        $this->resetValidation();
        $this->reset(['supplierId', 'name', 'contactName', 'phone', 'email', 'address']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $supplier = Supplier::findOrFail($id);

        $this->supplierId = $supplier->id;
        $this->name = $supplier->name;
        $this->contactName = $supplier->contact_name;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->address = $supplier->address;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'contact_name' => $this->contactName,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ];

        if ($this->isEditing) {
            $supplier = Supplier::find($this->supplierId);
            $oldData = $supplier->only(['name', 'contact_name', 'phone', 'email', 'address']);
            $supplier->update($data);
            session()->flash('message', 'Supplier sudah diupdate.');
            ActivityLog::log('update_supplier', $supplier, $oldData, $data);
        } else {
            $supplier = Supplier::create($data);
            session()->flash('message', 'Supplier sudah ditambahkan.');
            ActivityLog::log('create_supplier', $supplier, null, $data);
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        $supplier = Supplier::find($id);
        if (! $supplier) {
            return;
        }

        $supplierData = $supplier->only(['name', 'contact_name', 'phone', 'email', 'address']);
        $supplier->delete();
        session()->flash('message', 'Supplier sudah dihapus.');
        ActivityLog::log('delete_supplier', $supplier, $supplierData, null);
    }

    public function render()
    {
        $suppliers = Supplier::orderBy('name')->paginate(10);

        return view('livewire.admin.suppliers.index', [
            'suppliers' => $suppliers,
        ]);
    }
}