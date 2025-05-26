<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $industries = ['IT Services', 'Software Development', 'Finance', 'Healthcare', 'Education', 'Government', 'Retail', 'Manufacturing'];

        for ($i = 1; $i <= 40; $i++) {
            DB::table('clients')->insert([
                'name' => $this->generateCompanyName($industries[array_rand($industries)]),
                'email' => 'client' . $i . '@example.com',
                'contact' => '+1 ' . rand(200, 999) . '-' . rand(200, 999) . '-' . rand(1000, 9999),
                'address' => $this->generateAddress(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('clients')->insert([
            'name' => 'Minimal Info Client Corp',
            'email' => 'minimal@example.com',
            'contact' => null,
            'address' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function generateCompanyName($industry)
    {
        $prefixes = ['Global', 'National', 'Elite', 'Premium', 'First', 'Advanced', 'Professional'];
        $suffixes = ['Solutions', 'Technologies', 'Systems', 'Services', 'Consulting', 'Group'];
        
        return $prefixes[array_rand($prefixes)] . ' ' . $industry . ' ' . $suffixes[array_rand($suffixes)];
    }

    private function generateAddress()
    {
        $streets = ['Main', 'First', 'Second', 'Third', 'Fourth', 'Park', 'Fifth', 'Oak', 'Pine', 'Maple'];
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'];
        
        return rand(100, 999) . ' ' . $streets[array_rand($streets)] . ' St, ' . 
               $cities[array_rand($cities)] . ', ' . 
               strtoupper(fake()->lexify('??')) . ' ' . rand(10000, 99999);
    }
}