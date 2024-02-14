<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['name' => 'Role list','guard_name' => 'web', 'created_at' => now()],
            ['name' => 'Role create','guard_name' => 'web', 'created_at' => now()],
            ['name' => 'Role edit','guard_name' => 'web', 'created_at' => now()],
            ['name' => 'Role delete','guard_name' => 'web','created_at' => now()],
            ['name' => 'Product list','guard_name' => 'web','created_at' => now()],
            ['name' => 'Product create','guard_name' => 'web','created_at' => now()],
            ['name' => 'Product edit','guard_name' => 'web','created_at' => now()],                
            ['name' => 'Product delete','guard_name' => 'web','created_at' => now()],
            ['name' => 'User list','guard_name' => 'web','created_at' => now()],
            ['name' => 'User create','guard_name' => 'web','created_at' => now()],
            ['name' => 'User edit','guard_name' => 'web','created_at' => now()],                
            ['name' => 'User delete','guard_name' => 'web','created_at' => now()],
        ]);
    }
}
