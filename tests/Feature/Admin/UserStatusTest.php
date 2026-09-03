<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserStatusTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $other;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        Role::firstOrCreate(['name' => 'Kasir']);
        Role::firstOrCreate(['name' => 'Barista/Gudang']);

        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->assignRole('Owner/Admin');

        $this->other = User::factory()->create(['is_active' => true]);
        $this->other->assignRole('Kasir');
    }

    public function test_admin_can_deactivate_a_user(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\Admin\Users\Index::class)
            ->call('toggleStatus', $this->other->id);

        $this->assertDatabaseHas('users', [
            'id' => $this->other->id,
            'is_active' => false,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'toggle_user_status',
        ]);
    }

    public function test_admin_cannot_deactivate_own_account(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\Admin\Users\Index::class)
            ->call('toggleStatus', $this->admin->id);

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'is_active' => true,
        ]);
    }

    public function test_inactive_user_is_blocked_from_login(): void
    {
        $this->other->update(['is_active' => false]);

        $response = $this->actingAs($this->other)->get('/dashboard');
        // Should be redirected to login because EnsureUserIsActive logs out inactive users.
        $response->assertRedirect('/login');
    }
}