<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'id'=>1,
                'name'=>'company1',
                'bank_id'=>1,
                'cash_id'=>1,
                'organization_id'=>1,
                'created_at' => now(),
                'updated_at' => now(),
            ],  [
                'id'=>2,
                'name'=>'company2',
                'bank_id'=>2,
                'cash_id'=>2,
                'organization_id'=>2,
                'created_at' => now(),
                'updated_at' => now(),
            ],  [
                'id'=>3,
                'name'=>'company3',
                'bank_id'=>3,
                'cash_id'=>3,
                'organization_id'=>3,
                'created_at' => now(),
                'updated_at' => now(),
            ],



        ];

        Company::insert($companies);
    }
}
