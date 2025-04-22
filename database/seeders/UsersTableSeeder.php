<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'image' => 'no_image.jpg',
            'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gestionnaire ',
                'email' => 'gestionnaire@example.com',
                'password' => Hash::make('password'),
                'role' => 'gestionnaire',
                'image' => 'no_image.jpg',
            'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ' Magasin',
                'email' => 'magasin@example.com',
                'password' => Hash::make('password'),
                'role' => 'magasin',
                'image' => 'no_image.jpg',
            'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}