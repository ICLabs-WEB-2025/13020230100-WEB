<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        Service::create(['name' => 'Wash & Dry', 'price_per_kg' => 10000]);
        Service::create(['name' => 'Wash & Iron', 'price_per_kg' => 15000]);
        Service::create(['name' => 'Dry Clean', 'price_per_kg' => 20000]);
    }
}