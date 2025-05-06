<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        $services = [
            ['name' => 'Cuci Reguler', 'price_per_kg' => 5000],
            ['name' => 'Cuci Express', 'price_per_kg' => 8000],
            ['name' => 'Setrika Saja', 'price_per_kg' => 4000],
            ['name' => 'Cuci + Setrika', 'price_per_kg' => 7000],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}