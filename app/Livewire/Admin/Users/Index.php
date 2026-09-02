<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Index extends Component
{
    public $showModal = false;
    public $isEditing = false;

    // Form fields
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role_id;

    public function create()
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'password', 'password_confirmation', 'role_id']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $user = User::findOrFail($id);
        
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->role_id = $user->roles->first()->id ?? null;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'role_id' => 'required|exists:roles,id',
        ];

        if (!$this->isEditing || !empty($this->password)) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            $user = User::findOrFail($this->userId);
            $user->update($data);
            session()->flash('message', 'Pengguna berhasil diupdate.');
        } else {
            $user = User::create($data);
            session()->flash('message', 'Pengguna berhasil ditambahkan.');
        }

        $role = Role::findById($this->role_id);
        $user->syncRoles([$role]);

        $this->showModal = false;
    }

    public function render()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
}