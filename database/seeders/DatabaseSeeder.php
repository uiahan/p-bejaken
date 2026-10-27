<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('superadmin'),
            'role' => 'superadmin',
            'no_hp' => '6281234567890',
            'alamat' => 'Jl. Contoh Alamat No. 123, Kota Contoh, Negara Contoh',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'role' => 'admin',
            'no_hp' => '6281234567890',
            'alamat' => 'Jl. Contoh Alamat No. 123, Kota Contoh, Negara Contoh',
        ]);

        User::create([
            'name' => 'Pelanggan',
            'email' => 'pelanggan@example.com',
            'password' => bcrypt('pelanggan'),
            'role' => 'pelanggan',
            'no_hp' => '6281234567890',
            'alamat' => 'Jl. Contoh Alamat No. 123, Kota Contoh, Negara Contoh',
        ]);
    }
}
