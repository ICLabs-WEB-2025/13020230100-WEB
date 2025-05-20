<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus semua data di tabel users sebelum menambahkan data baru
        User::truncate();

        // User::factory(10)->create();
        $this->call([
        // ... seeders lain
        DummyOrdersSeeder::class,
    ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Tambahkan akun admin secara langsung
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'), // Ganti dengan password yang aman
            'role' => 'admin',
        ]);
    }
}
