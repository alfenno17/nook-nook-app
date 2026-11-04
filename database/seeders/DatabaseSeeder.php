<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===== BUAT ROLE =====
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // ===== BUAT ADMIN =====
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // ===== BUAT USER BIASA =====
        $user = User::create([
            'name'     => 'User Biasa',
            'email'    => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole($userRole);

        // ===== PANGGIL SEEDER LAINNYA =====
        $this->call([
            KategoriSeeder::class,   // <-- TAMBAHKAN INI
            ruanganeeder::class,    // <-- SUDAH ADA
        ]);
    }
}