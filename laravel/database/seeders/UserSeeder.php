<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'jfaner7@gmail.com'],
            [
                'name' => 'JB Customer',
                'password' => Hash::make('kyusified123'),
                'role' => 'customer',
                'status' => 'active',
            ]
        );
    }
}
