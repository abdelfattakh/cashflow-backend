<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            [
                'id' => 1,
                'name' => 'Riyadh bank',
                'account_number' => '159753369147',
                'actual_balance'=>165000,
                'current_balance' => 160000,
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id'=>3,
            ],
            [
                'id' => 2,
                'name' => 'Mekka Bank',
                'account_number' => '951753963741',
                'actual_balance'=>155000,
                'current_balance' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id'=>2,
            ],
            [
                'id' => 3,
                'name' => 'Madina Bank',
                'account_number' => '95175396374',
                'actual_balance'=>150000,
                'current_balance' => 154000,
                'created_at' => now(),
                'updated_at' => now(),
                'organization_id'=>1,
            ],


        ];

        Bank::insert($banks);
    }
}
