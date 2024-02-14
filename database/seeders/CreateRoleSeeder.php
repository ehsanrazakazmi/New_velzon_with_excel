<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $created_roles = DB::table('roles')->insert([
        //     ['name' => 'User', 'description' => 'Can handle limited Access in the system' ,'guard_name' => 'web', 'created_at' => now()],
        //     ['name' => 'Mini-Admin', 'description' => 'Has access to those who are defined by the admin' ,'guard_name' => 'web', 'created_at' => now()],
        // ]);
        
        $rolesData = [
            ['name' => 'User', 'description' => 'Can handle limited Access in the system', 'guard_name' => 'web', 'created_at' => now()],
            ['name' => 'Mini-Admin', 'description' => 'Has access to those who are defined by the admin', 'guard_name' => 'web', 'created_at' => now()],
        ];

        $createdRoles = collect();

        foreach ($rolesData as $roleData) {
            $role = Role::create($roleData);
            $createdRoles->push($role);
        }

        // Sync permissions only if roles were created
        if ($createdRoles->count() > 0) {
            $userRole = $createdRoles->firstWhere('name', 'User');
            $miniAdminRole = $createdRoles->firstWhere('name', 'Mini-Admin');

            $userRole->syncPermissions(['Role list', 'Product list']);
            $miniAdminRole->syncPermissions(['Role list', 'Product list']);
        }
    }
}
