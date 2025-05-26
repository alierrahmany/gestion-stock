<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersSeeder extends Seeder
{
    public function run()
    {
        $majorBrands = [
            'Logitech', 'Microsoft', 'Dell', 'HP', 'Lenovo', 
            'Samsung', 'LG', 'Asus', 'Acer', 'Razer', 
            'Corsair', 'SteelSeries', 'Anker', 'Belkin',
            'TP-Link', 'Ubiquiti', 'Western Digital', 'Seagate', 'APC', 'Tripp Lite'
        ];

        foreach ($majorBrands as $brand) {
            DB::table('suppliers')->insert([
                'name' => $brand . ' Technologies Inc.',
                'email' => strtolower($brand) . '@example.com',
                'contact' => '+1 ' . rand(200, 999) . '-' . rand(200, 999) . '-' . rand(1000, 9999),
                'address' => $this->generateAddress(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 1; $i <= 30; $i++) {
            DB::table('suppliers')->insert([
                'name' => 'IT Supplier ' . $i . ' LLC',
                'email' => 'supplier' . $i . '@example.com',
                'contact' => '+1 ' . rand(200, 999) . '-' . rand(200, 999) . '-' . rand(1000, 9999),
                'address' => $this->generateAddress(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('suppliers')->insert([
            'name' => 'Direct Imports Ltd',
            'email' => 'imports@example.com',
            'contact' => null,
            'address' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
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