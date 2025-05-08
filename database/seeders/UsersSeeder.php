<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Clear existing users
        User::query()->delete();


        // Create admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 1,
            'image' => 'no_image.jpg',
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create other users
        $users = [
            [
                'name' => 'Gestionnaire',
                'email' => 'gestionnaire@example.com',
                'password' => Hash::make('password'),
                'role' => 'gestionnaire',
                'status' => 1,
                'image' => 'no_image.jpg',
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Magasin',
                'email' => 'magasin@example.com',
                'password' => Hash::make('password'),
                'role' => 'magasin',
                'status' => 1,
                'image' => 'no_image.jpg',
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
