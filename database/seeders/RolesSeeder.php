<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name'=>'admin' , 'guard_name'=>'api']);
        $roleManager = Role::create(['name'=>'manager' , 'guard_name'=>'api']);
        $roleUser = Role::create(['name'=>'user' , 'guard_name'=>'api']);
    }
}
