<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //CRUD user (Admin only)
        Permission::firstOrCreate(['name'=>'show all user'    , 'guard_name'=>'api']);
        Permission::firstOrCreate(['name'=>'create user'      , 'guard_name'=>'api']);
        Permission::firstOrCreate(['name'=>'update role user' , 'guard_name'=>'api']);
        Permission::firstOrCreate(['name'=>'delete user'      , 'guard_name'=>'api']);

        //CRUD Task (admin and manager)
        Permission::firstOrCreate(['name'=>'show tasks'  , 'guard_name'=>'api']);
        Permission::firstOrCreate(['name'=>'create task' , 'guard_name'=>'api']);
        Permission::firstOrCreate(['name'=>'update task' , 'guard_name'=>'api']);
        Permission::firstOrCreate(['name'=>'delete task' , 'guard_name'=>'api']);

        // user permission
        Permission::firstOrCreate(['name'=>'update status task'    , 'guard_name'=>'api']);
        Permission::firstOrCreate(['name'=>'show task assigned it' , 'guard_name'=>'api']);

    }
}
