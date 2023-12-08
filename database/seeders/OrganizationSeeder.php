<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Company;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations= [
            [
                'id'=>1,
                'name'=>'organization1',
                'created_at' => now(),
                'updated_at' => now(),
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-32-inch-led-tv-hd-built-in-receiver-2-hdmi-2-usb-inputs-32er9500e-insurance.jpg',

            ],
            [
                'id'=>2,
                'name'=>'organization2',
                'created_at' => now(),
                'updated_at' => now(),
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-32-inch-led-tv-hd-built-in-receiver-2-hdmi-2-usb-inputs-32er9500e-insurance.jpg',

            ],
            [
                'id'=>3,
                'name'=>'organization3',
                'created_at' => now(),
                'updated_at' => now(),
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-32-inch-led-tv-hd-built-in-receiver-2-hdmi-2-usb-inputs-32er9500e-insurance.jpg',

            ],



        ];
        foreach ($organizations as $organization) {
            $organization_model = Organization::query()->create(Arr::except($organization, keys: ['image']));
            $organization_model->addMediaFromUrl($organization['image'])->toMediaCollection((new Organization())->getPrimaryMediaCollection());

        }

    }
}
