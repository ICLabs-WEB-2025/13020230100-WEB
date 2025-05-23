<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::insert([
            [
                'name' => 'Cuci Kering',
                'price_per_kg' => 10000,
                'description' => 'Layanan cuci kering pakaian.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cuci Setrika',
                'price_per_kg' => 15000,
                'description' => 'Layanan cuci dan setrika pakaian.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Setrika Saja',
                'price_per_kg' => 8000,
                'description' => 'Layanan setrika pakaian saja.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
