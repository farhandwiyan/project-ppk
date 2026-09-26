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
            'nama' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('sangat rahasia'),
            'role' => 'admin',
            'status' => 'verified',
        ]);

        User::create([
            'nama' => 'Petugas',
            'email' => 'petugas@example.com',
            'password' => Hash::make('rahasia'),
            'role' => 'petugas',
            'status' => 'verified',
        ]);

        User::create([
            'nama' => 'Joko',
            'email' => 'joko@example.com',
            'password' => Hash::make('joko123'),
            'role' => 'user',
            'status' => 'verified',
        ]);

        User::create([
            'nama' => 'Farhan Muhtaram',
            'email' => 'farhan@example.com',
            'password' => Hash::make('farhan123'),
            'role' => 'user',
            'status' => 'unverified',
        ]);
    }
}
