<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin table 1',
            'email' => 'admintable1@gmail.com',
            'role' => 'Admin Table 1',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Admin table 2',
            'email' => 'admintable2@gmail.com',
            'role' => 'Admin Table 2',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Admin table 3',
            'email' => 'admintable3@gmail.com',
            'role' => 'Admin Table 3',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Admin table 4',
            'email' => 'admintable4@gmail.com',
            'role' => 'Admin Table 4',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Admin monitoring all',
            'email' => 'adminmonitoringall@gmail.com',
            'role' => 'Admin Monitoring All',
            'password' => Hash::make('password123'),
        ]);
    }
}
