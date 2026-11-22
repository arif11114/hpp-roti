<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@pabrikroti.com',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Staff Gudang',
                'email' => 'gudang@pabrikroti.com',
                'role' => 'gudang',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Staff Produksi',
                'email' => 'produksi@pabrikroti.com',
                'role' => 'produksi',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}