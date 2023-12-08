<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionRole = [
            [
                'id' => 1,
                'role_id' => 1,
                'permission_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 2,
                'role_id' => 1,
                'permission_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'role_id' => 1,
                'permission_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 5,
                'role_id' => 2,
                'permission_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 6,
                'role_id' => 2,
                'permission_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
  [
                'id' => 7,
                'role_id' => 3,
                'permission_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],



        ];

        PermissionRole::insert($permissionRole);
    }
}
