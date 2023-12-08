<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = [
            [
                'id'=>1,
                'name'=>'project1',
                'company_id'=>1,
                'created_at' => now(),
                'updated_at' => now(),
            ],  [
                'id'=>2,
                'name'=>'project2',
                'company_id'=>2,
                'created_at' => now(),
                'updated_at' => now(),
            ],  [
                'id'=>3,
                'name'=>'project3',
                'company_id'=>3,
                'created_at' => now(),
                'updated_at' => now(),
            ],



        ];

        Project::insert($projects);
    }
}
