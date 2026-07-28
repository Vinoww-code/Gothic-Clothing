<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'punyadmin@gothicclothing.com'],
            [
                'name'     => 'Admin',
                'role'     => 'admin', // Menandai akun ini sebagai Admin
                'password' => Hash::make('HanyaAdmin321'), // Password-nya adalah "password"
            ]
        );
    }
}