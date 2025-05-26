<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $industries = ['Services IT', 'Développement Logiciel', 'Finance', 'Santé', 'Éducation', 'Gouvernement', 'Commerce', 'Industrie'];

        for ($i = 1; $i <= 40; $i++) {
            DB::table('clients')->insert([
                'name' => $this->generateCompanyName($industries[array_rand($industries)]),
                'email' => 'client' . $i . '@exemple.com',
                'contact' => '+33 ' . rand(1, 9) . rand(10, 99) . rand(10, 99) . rand(10, 99) . rand(10, 99),
                'address' => $this->generateAddress(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('clients')->insert([
            'name' => 'Société Client Info Minimale',
            'email' => 'minimal@exemple.com',
            'contact' => null,
            'address' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function generateCompanyName($industry)
    {
        $prefixes = ['Global', 'National', 'Elite', 'Premium', 'Premier', 'Avancé', 'Professionnel'];
        $suffixes = ['Solutions', 'Technologies', 'Systèmes', 'Services', 'Consulting', 'Groupe'];

        return $prefixes[array_rand($prefixes)] . ' ' . $industry . ' ' . $suffixes[array_rand($suffixes)];
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
