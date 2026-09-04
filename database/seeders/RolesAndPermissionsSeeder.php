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

        // Determine the seed password. Prefer an explicit SEED_ADMIN_PASSWORD
        // (set in .env) so development/demo credentials stay usable; otherwise
        // generate a strong random one and print it. Never fall back to a weak,
        // hard-coded default such as "password123".
        $seedPassword = env('SEED_ADMIN_PASSWORD') ?? $_ENV['SEED_ADMIN_PASSWORD'] ?? null;

        if (empty($seedPassword)) {
            $seedPassword = \Illuminate\Support\Str::password(18);
            $this->command->warn('SEED_ADMIN_PASSWORD is not set.');
            $this->command->warn('Generated seed users with password: '.$seedPassword);
            $this->command->warn('Simpan password ini dan set SEED_ADMIN_PASSWORD di .env untuk hasil yang konsisten.');
        }

        // Create initial users for each role
        $admin = User::firstOrCreate([
            'email' => 'admin@coffeeshop.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make($seedPassword),
        ]);
        $admin->assignRole($roleOwner);

        $manager = User::firstOrCreate([
            'email' => 'manager@coffeeshop.com',
        ], [
            'name' => 'Store Manager',
            'password' => Hash::make($seedPassword),
        ]);
        $manager->assignRole($roleManager);

        $kasir = User::firstOrCreate([
            'email' => 'kasir@coffeeshop.com',
        ], [
            'name' => 'Staff Kasir',
            'password' => Hash::make($seedPassword),
        ]);
        $kasir->assignRole($roleKasir);

        $barista = User::firstOrCreate([
            'email' => 'barista@coffeeshop.com',
        ], [
            'name' => 'Staff Barista',
            'password' => Hash::make($seedPassword),
        ]);
        $barista->assignRole($roleBarista);
    }
}
