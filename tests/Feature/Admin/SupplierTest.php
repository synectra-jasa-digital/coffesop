<?php

namespace Tests\Feature\Admin;

use App\Models\Supplier;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Owner/Admin']);
        Role::firstOrCreate(['name' => 'Manager/Supervisor']);

        $this->manager = User::factory()->create();
        $this->manager->assignRole('Manager/Supervisor');
    }

    public function test_supplier_can_be_created_edited_and_deleted(): void
    {
        $this->actingAs($this->manager);

        // Create
        Livewire::test(\App\Livewire\Admin\Suppliers\Index::class)
            ->call('create')
            ->set('name', 'PT Kopi Nusantara')
            ->set('contactName', 'Budi')
            ->set('phone', '08123456789')
            ->set('email', 'budi@kopi.com')
            ->set('address', 'Jalan Kopi No. 1')
            ->call('save')
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'PT Kopi Nusantara',
            'contact_name' => 'Budi',
            'phone' => '08123456789',
            'email' => 'budi@kopi.com',
            'address' => 'Jalan Kopi No. 1',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'create_supplier',
        ]);

        $supplier = Supplier::where('name', 'PT Kopi Nusantara')->first();

        // Edit
        Livewire::test(\App\Livewire\Admin\Suppliers\Index::class)
            ->call('edit', $supplier->id)
            ->assertSet('name', 'PT Kopi Nusantara')
            ->set('phone', '0899999999')
            ->call('save')
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'phone' => '0899999999',
        ]);

        // Delete
        Livewire::test(\App\Livewire\Admin\Suppliers\Index::class)
            ->call('delete', $supplier->id);

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    public function test_supplier_name_is_required(): void
    {
        $this->actingAs($this->manager);

        Livewire::test(\App\Livewire\Admin\Suppliers\Index::class)
            ->call('create')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name']);
    }

    public function test_supplier_email_must_be_valid(): void
    {
        $this->actingAs($this->manager);

        Livewire::test(\App\Livewire\Admin\Suppliers\Index::class)
            ->call('create')
            ->set('name', 'Supplier Test')
            ->set('email', 'not-an-email')
            ->call('save')
            ->assertHasErrors(['email']);
    }
}