<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'HP EliteBook 840',
                'categorie_id' => 1,
                'quantity' => 2,  // Added quantity
                'price' => 999.99, // Added price
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Logitech MX Keys',
                'categorie_id' => 2,
                'quantity' => 3,
                'price' => 119.99,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Logitech MX Master 3',
                'categorie_id' => 3,
                'quantity' => 5,
                'price' => 99.99,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dell Ultrasharp U2723QE',
                'categorie_id' => 4,
                'quantity' => 1,
                'price' => 499.99,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'HP LaserJet Pro M404dn',
                'categorie_id' => 5,
                'quantity' => 4,
                'price' => 299.99,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Canon CanoScan LiDE 400',
                'categorie_id' => 6,
                'quantity' => 2,
                'price' => 89.99,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'TP-Link Archer C80',
                'categorie_id' => 7,
                'quantity' => 3,
                'price' => 59.99,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'HDMI Cable 1.5m',
                'categorie_id' => 8,
                'quantity' => 10,
                'price' => 9.99,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Logitech H390 Headset',
                'categorie_id' => 9,
                'quantity' => 6,
                'price' => 39.99,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Logitech C920 Webcam',
                'categorie_id' => 10,
                'quantity' => 4,
                'price' => 69.99,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
