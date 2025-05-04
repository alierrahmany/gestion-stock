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
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Logitech MX Keys',
                'categorie_id' => 2,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Logitech MX Master 3',
                'categorie_id' => 3,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dell Ultrasharp U2723QE',
                'categorie_id' => 4,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'HP LaserJet Pro M404dn',
                'categorie_id' => 5,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Canon CanoScan LiDE 400',
                'categorie_id' => 6,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'TP-Link Archer C80',
                'categorie_id' => 7,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'HDMI Cable 1.5m',
                'categorie_id' => 8,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Logitech H390 Headset',
                'categorie_id' => 9,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Logitech C920 Webcam',
                'categorie_id' => 10,
                'date' => now(),
                'file_name' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
