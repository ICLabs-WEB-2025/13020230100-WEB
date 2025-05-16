<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DummyOrdersSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada customer dan service terlebih dahulu
        if (Customer::count() === 0) {
            $this->call(CustomersSeeder::class); // Ganti dengan seeder customer Anda
        }

        if (Service::count() === 0) {
            $this->call(ServicesSeeder::class); // Ganti dengan seeder service Anda
        }

        $faker = Factory::create('id_ID'); // Untuk data lokal Indonesia

        $customers = Customer::pluck('id');
        $services = Service::pluck('id');
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];

        // Buat 500 order dummy
        for ($i = 0; $i < 500; $i++) {
            $createdAt = $faker->dateTimeBetween('-1 year', 'now');
            $pickupDate = clone $createdAt;
            $pickupDate->modify('+' . rand(1, 3) . ' days');
            
            $deliveryDate = clone $pickupDate;
            $deliveryDate->modify('+' . rand(2, 5) . ' days');

            Order::create([
                'customer_id' => $customers->random(),
                'service_id' => $services->random(),
                'weight' => $faker->randomFloat(2, 1, 10), // Berat 1-10 kg
                'total_price' => $faker->numberBetween(20000, 150000),
                'status' => $faker->randomElement($statuses),
                'pickup_date' => $pickupDate,
                'delivery_date' => $deliveryDate,
                'notes' => $faker->optional(0.3)->sentence(), // 30% punya catatan
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ]);
        }

        $this->command->info('500 dummy orders created!');
    }
}