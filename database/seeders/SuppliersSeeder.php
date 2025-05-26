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
                'name' => $brand . ' Technologies SARL',
                'email' => strtolower($brand) . '@exemple.com',
                'contact' => '+33 ' . rand(1, 9) . rand(10, 99) . rand(10, 99) . rand(10, 99) . rand(10, 99),
                'address' => $this->generateAddress(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 1; $i <= 30; $i++) {
            DB::table('suppliers')->insert([
                'name' => 'Fournisseur IT ' . $i . ' SARL',
                'email' => 'fournisseur' . $i . '@exemple.com',
                'contact' => '+33 ' . rand(1, 9) . rand(10, 99) . rand(10, 99) . rand(10, 99) . rand(10, 99),
                'address' => $this->generateAddress(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('suppliers')->insert([
            'name' => 'Importations Directes SARL',
            'email' => 'imports@exemple.com',
            'contact' => null,
            'address' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function generateAddress()
    {
        $streets = ['Principal', 'Première', 'Deuxième', 'Troisième', 'Quatrième', 'Parc', 'Cinquième', 'Chêne', 'Pin', 'Érable'];
        $cities = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille'];

        return rand(1, 999) . ' ' . $streets[array_rand($streets)] . ', ' .
               $cities[array_rand($cities)] . ', ' .
               rand(10000, 99999) . ' ' . $cities[array_rand($cities)];
    }
}
