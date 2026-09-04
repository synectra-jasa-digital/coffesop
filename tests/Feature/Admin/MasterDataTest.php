<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Category;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'Owner/Admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Owner/Admin');
    }

    public function test_admin_can_create_new_category()
    {
        $this->actingAs($this->admin);

        Livewire::test('admin.master.index')
            ->set('categoryName', 'Minuman Dingin')
            ->set('categoryDesc', 'Es Kopi dkk')
            ->call('saveCategory')
            ->assertHasNoErrors()
            ->assertSee('Kategori berhasil disimpan');

        $this->assertDatabaseHas('categories', [
            'name' => 'Minuman Dingin',
            'description' => 'Es Kopi dkk'
        ]);
    }

    public function test_admin_can_edit_existing_category()
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $this->actingAs($this->admin);

        Livewire::test('admin.master.index')
            ->call('openCategoryModal', $category->id)
            ->set('categoryName', 'New Name')
            ->call('saveCategory')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name'
        ]);
    }

    public function test_admin_can_create_new_table()
    {
        $this->actingAs($this->admin);

        Livewire::test('admin.master.index')
            ->call('switchTab', 'tables')
            ->set('tableNumber', 'Meja VIP')
            ->set('tableStatus', 'available')
            ->call('saveTable')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tables', [
            'number' => 'Meja VIP',
            'status' => 'available'
        ]);
    }
}