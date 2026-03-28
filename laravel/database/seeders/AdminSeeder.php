<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kyusifyadmin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('kyusified123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );
    }
}

