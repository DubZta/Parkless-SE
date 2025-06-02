<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'username' => 'Group 7',
            'email' => 'hanzsgoo@gmail.com',
            'password' => Hash::make('hans1234'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Akun Fake 1',
            'username' => 'Fake 1',
            'email' => 'fake1@gmail.com',
            'password' => Hash::make('fake1'),
            'is_admin' => false,
        ]);
    }
}
