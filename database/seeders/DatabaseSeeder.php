<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if admin exists
        if (!User::where('email', 'admin@toko.com')->exists()) {
            User::create([
                'name' => 'Admin Toko',
                'email' => 'admin@toko.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
