<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id'=>1,
                'name'=>'super_admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],  [
                'id'=>2,
                'name'=>'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],  [
                'id'=>3,
                'name'=>'employee',
                'created_at' => now(),
                'updated_at' => now(),
            ],



        ];

        Role::insert($roles);
    }
}
