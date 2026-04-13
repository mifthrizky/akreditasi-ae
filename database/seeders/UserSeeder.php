<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the users table.
     */
    public function run(): void
    {
        // Admin users
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@acreditasi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Dosen users
        User::create([
            'nama' => 'Dosen 1',
            'email' => 'dosen1@acreditasi.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        User::create([
            'nama' => 'Dosen 2',
            'email' => 'dosen2@acreditasi.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        User::create([
            'nama' => 'Dosen 3',
            'email' => 'dosen3@acreditasi.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        // Validator users
        User::create([
            'nama' => 'Validator Utama',
            'email' => 'validator@acreditasi.com',
            'password' => Hash::make('password'),
            'role' => 'validator',
        ]);
    }
}
