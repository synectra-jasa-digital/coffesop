<?php

namespace Tests\Feature\Discounts;

use App\Models\Discount;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PromoTest extends TestCase
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

    public function test_promo_can_be_created_edited_and_deleted(): void
    {
        $this->actingAs($this->manager);

        Livewire::test(\App\Livewire\Admin\Discounts\Index::class)
            ->call('create')
            ->set('name', 'Promo Akhir Tahun')
            ->set('code', 'promo10')
            ->set('type', 'percentage')
            ->set('value', 10)
            ->set('startDate', Carbon::today()->format('Y-m-d'))
            ->set('endDate', Carbon::now()->addDays(10)->format('Y-m-d'))
            ->set('minimumPurchase', 50000)
            ->call('save')
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('discounts', [
            'name' => 'Promo Akhir Tahun',
            'code' => 'PROMO10',
            'type' => 'percentage',
            'value' => 10,
            'minimum_purchase' => 50000,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'create_promo',
        ]);

        $discount = Discount::where('code', 'PROMO10')->first();

        // Edit
        Livewire::test(\App\Livewire\Admin\Discounts\Index::class)
            ->call('edit', $discount->id)
            ->set('value', 15)
            ->call('save');

        $this->assertDatabaseHas('discounts', [
            'id' => $discount->id,
            'value' => 15,
        ]);

        // Delete
        Livewire::test(\App\Livewire\Admin\Discounts\Index::class)
            ->call('delete', $discount->id);

        $this->assertDatabaseMissing('discounts', ['id' => $discount->id]);
    }

    public function test_promo_name_is_required(): void
    {
        $this->actingAs($this->manager);

        Livewire::test(\App\Livewire\Admin\Discounts\Index::class)
            ->call('create')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name']);
    }

    public function test_promo_end_date_must_be_after_start_date(): void
    {
        $this->actingAs($this->manager);

        Livewire::test(\App\Livewire\Admin\Discounts\Index::class)
            ->call('create')
            ->set('name', 'Promo Test')
            ->set('type', 'percentage')
            ->set('value', 5)
            ->set('startDate', Carbon::now()->addDays(5)->format('Y-m-d'))
            ->set('endDate', Carbon::today()->format('Y-m-d'))
            ->call('save')
            ->assertHasErrors(['endDate']);
    }

    public function test_percentage_promo_calculates_correctly(): void
    {
        $discount = Discount::create([
            'name' => 'Promo 10%',
            'code' => 'PROMO10',
            'type' => 'percentage',
            'value' => 10,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::now()->addDays(10),
            'is_active' => true,
            'minimum_purchase' => 0,
        ]);

        // 100000 subtotal, 10% = 10000
        $this->assertSame(10000.0, $discount->calculate(100000));
    }

    public function test_fixed_promo_calculates_correctly(): void
    {
        $discount = Discount::create([
            'name' => 'Potong 20rb',
            'code' => 'POTONG20',
            'type' => 'fixed',
            'value' => 20000,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::now()->addDays(10),
            'is_active' => true,
            'minimum_purchase' => 0,
        ]);

        $this->assertSame(20000.0, $discount->calculate(100000));
    }

    public function test_promo_respects_minimum_purchase(): void
    {
        $discount = Discount::create([
            'name' => 'Promo Min 100rb',
            'code' => 'MIN100',
            'type' => 'percentage',
            'value' => 10,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::now()->addDays(10),
            'is_active' => true,
            'minimum_purchase' => 100000,
        ]);

        // Below minimum -> 0
        $this->assertSame(0.0, $discount->calculate(50000));
        // Above minimum -> 10% of 150000 = 15000
        $this->assertSame(15000.0, $discount->calculate(150000));
    }

    public function test_inactive_promo_is_not_active(): void
    {
        $discount = Discount::create([
            'name' => 'Promo Nonaktif',
            'code' => 'NONAKTIF',
            'type' => 'percentage',
            'value' => 10,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::now()->addDays(10),
            'is_active' => false,
            'minimum_purchase' => 0,
        ]);

        $this->assertFalse($discount->isActive());
    }

    public function test_promo_outside_date_window_is_not_active(): void
    {
        $discount = Discount::create([
            'name' => 'Promo Expired',
            'code' => 'EXPIRED',
            'type' => 'percentage',
            'value' => 10,
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => Carbon::now()->subDay(),
            'is_active' => true,
            'minimum_purchase' => 0,
        ]);

        $this->assertFalse($discount->isActive());
    }
}