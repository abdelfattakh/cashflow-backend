<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id'=>1,
                'name'=>'gehad',
                'password'=>Hash::make('123456789'),
                'email'=>'gehad.ehab.mosaad@gmail.com',
                'email_verified_at'=>now(),
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id'=>1,
            ],  [
                'id'=>2,
                'name'=>'gehad1',
                'email'=>'gehab@joe13th.com',
                'password'=>Hash::make('123456789'),
                'email_verified_at'=>now(),
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id'=>2
            ],  [
                'id'=>3,
                'name'=>'gehad2',
                'email'=>'joe@joe13th.com',
                'password'=>Hash::make('123456789'),
                'email_verified_at'=>now(),
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id'=>3,
            ],



        ];

        User::insert($users);
    }
}
