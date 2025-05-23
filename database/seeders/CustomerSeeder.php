<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        DB::table('customers')->insert([
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 1',
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'phone' => '081298765432',
                'address' => 'Jl. Sudirman No. 2',
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi@example.com',
                'phone' => '081377788899',
                'address' => 'Jl. Diponegoro No. 3',
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'phone' => '081355566677',
                'address' => 'Jl. Gajah Mada No. 4',
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
