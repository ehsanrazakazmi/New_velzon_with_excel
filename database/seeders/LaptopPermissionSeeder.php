<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Adds the Laptop Management permissions to an already-seeded database and
 * grants them to Super Admin. Safe to run repeatedly.
 */
class LaptopPermissionSeeder extends Seeder
{
    public function run()
    {
        $names = ['Laptop list', 'Laptop create', 'Laptop edit', 'Laptop delete'];

        foreach ($names as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $superAdmin = Role::where('name', 'Super Admin')->first();

        if ($superAdmin) {
            $superAdmin->givePermissionTo($names);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
