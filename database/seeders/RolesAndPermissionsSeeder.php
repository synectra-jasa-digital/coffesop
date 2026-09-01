<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Roles
        $roleOwner = Role::firstOrCreate(['name' => 'Owner/Admin']);
        $roleManager = Role::firstOrCreate(['name' => 'Manager/Supervisor']);
        $roleKasir = Role::firstOrCreate(['name' => 'Kasir']);
        $roleBarista = Role::firstOrCreate(['name' => 'Barista/Gudang']);

        // Create initial users for each role
        $admin = User::firstOrCreate([
            'email' => 'admin@coffeeshop.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole($roleOwner);

        $manager = User::firstOrCreate([
            'email' => 'manager@coffeeshop.com',
        ], [
            'name' => 'Store Manager',
            'password' => Hash::make('password123'),
        ]);
        $manager->assignRole($roleManager);

        $kasir = User::firstOrCreate([
            'email' => 'kasir@coffeeshop.com',
        ], [
            'name' => 'Staff Kasir',
            'password' => Hash::make('password123'),
        ]);
        $kasir->assignRole($roleKasir);

        $barista = User::firstOrCreate([
            'email' => 'barista@coffeeshop.com',
        ], [
            'name' => 'Staff Barista',
            'password' => Hash::make('password123'),
        ]);
        $barista->assignRole($roleBarista);
    }
}
