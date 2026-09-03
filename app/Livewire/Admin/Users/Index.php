<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\ActivityLog;
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
    public $is_active = true;

    public function create()
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'password', 'password_confirmation', 'role_id']);
        $this->is_active = true;
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
        $this->is_active = $user->is_active;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ];

        if (!$this->isEditing || !empty($this->password)) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            $user = User::findOrFail($this->userId);
            $user->update($data);
            session()->flash('message', 'Pengguna sudah diupdate.');
            ActivityLog::log('update_user', $user, null, $data);
        } else {
            $user = User::create($data);
            session()->flash('message', 'Pengguna sudah ditambahkan.');
            ActivityLog::log('create_user', $user, null, $data);
        }

        $role = Role::findById($this->role_id);
        $user->syncRoles([$role]);

        $this->showModal = false;
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Prevent disabling your own account.
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Tidak bisa menonaktifkan akun sendiri.');
            return;
        }

        $oldActive = $user->is_active;
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('message', 'Status pengguna diperbarui.');
        ActivityLog::log('toggle_user_status', $user, ['is_active' => $oldActive], ['is_active' => $user->is_active]);
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