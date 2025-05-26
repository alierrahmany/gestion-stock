<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $keyboardModels = ['MX Keys', 'K120', 'G915', 'K780', 'MK270', 'K350', 'K845', 'Ornata', 'BlackWidow', 'Huntsman'];
        foreach ($keyboardModels as $model) {
            DB::table('products')->insert([
                'name' => 'Clavier ' . $model,
                'categorie_id' => DB::table('categories')->where('name', 'Claviers')->value('id'),
                'date' => now()->subDays(rand(1, 365)),
                'file_name' => 'image-k.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $mouseModels = ['MX Master 3', 'M185', 'G502', 'M720', 'MX Anywhere 3', 'G Pro', 'Viper', 'DeathAdder', 'Basilisk', 'M325'];
        foreach ($mouseModels as $model) {
            DB::table('products')->insert([
                'name' => 'Souris ' . $model,
                'categorie_id' => DB::table('categories')->where('name', 'Souris')->value('id'),
                'date' => now()->subDays(rand(1, 365)),
                'file_name' => 'image-m.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $monitorModels = ['UltraSharp U2720Q', 'P2419H', 'S2721QS', '27GL850', 'VG279Q', 'XG270QG', 'PA278CV', 'ProArt PA32UCX'];
        foreach ($monitorModels as $model) {
            DB::table('products')->insert([
                'name' => 'Écran ' . $model,
                'categorie_id' => DB::table('categories')->where('name', 'Écrans')->value('id'),
                'date' => now()->subDays(rand(1, 365)),
                'file_name' => 'image-e.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $otherProducts = [
            ['Webcam C920', 'Webcams'],
            ['Webcam Brio 4K', 'Webcams'],
            ['Microphone Blue Yeti', 'Microphones'],
            ['Haut-parleurs Z120', 'Haut-parleurs'],
            ['Câble HDMI 2m', 'Câbles'],
            ['Station Thunderbolt 3', 'Stations d\'accueil'],
            ['Adaptateur USB-C vers HDMI', 'Adaptateurs'],
            ['Hub USB 7 ports', 'Hubs USB'],
            ['Disque dur externe WD 2TB', 'Stockage externe'],
            ['Routeur Archer AX50', 'Équipement réseau'],
        ];

        foreach ($otherProducts as $product) {
            DB::table('products')->insert([
                'name' => $product[0],
                'categorie_id' => DB::table('categories')->where('name', $product[1])->value('id'),
                'date' => now()->subDays(rand(1, 365)),
                'file_name' => 'default-product.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
