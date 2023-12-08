<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'id' => 1,
                'name' => 'view',
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'id' => 2,
                'name' => 'create',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'update',
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'id' => 4,
                'name' => 'delete',
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ];

        Permission::insert($permissions);
    }
}
