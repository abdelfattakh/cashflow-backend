<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Cash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cash = [
            [
                'id' => 1,
                'name' => 'Riyadh cash',
                'current_balance' => 160000,
                'actual_balance' => 160000,
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id'=>3,
            ],
            [
                'id' => 2,
                'name' => 'Mekka cash',
                'current_balance' => 150000,
                'actual_balance' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id'=>2,
            ],
            [
                'id' => 3,
                'name' => 'Madina cash',
                'current_balance' => 154000,
                'actual_balance' => 154000,
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id'=>1,
            ],


        ];

        Cash::insert($cash);
    }
}
