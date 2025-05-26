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
                'name' => $model . ' Keyboard',
                'categorie_id' => DB::table('categories')->where('name', 'Keyboards')->value('id'),
                'date' => now()->subDays(rand(1, 365)),
                'file_name' => 'keyboard_' . str_replace(' ', '_', strtolower($model)) . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $mouseModels = ['MX Master 3', 'M185', 'G502', 'M720', 'MX Anywhere 3', 'G Pro', 'Viper', 'DeathAdder', 'Basilisk', 'M325'];
        foreach ($mouseModels as $model) {
            DB::table('products')->insert([
                'name' => $model . ' Mouse',
                'categorie_id' => DB::table('categories')->where('name', 'Mice')->value('id'),
                'date' => now()->subDays(rand(1, 365)),
                'file_name' => 'mouse_' . str_replace(' ', '_', strtolower($model)) . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $monitorModels = ['UltraSharp U2720Q', 'P2419H', 'S2721QS', '27GL850', 'VG279Q', 'XG270QG', 'PA278CV', 'ProArt PA32UCX'];
        foreach ($monitorModels as $model) {
            DB::table('products')->insert([
                'name' => $model . ' Monitor',
                'categorie_id' => DB::table('categories')->where('name', 'Monitors')->value('id'),
                'date' => now()->subDays(rand(1, 365)),
                'file_name' => 'monitor_' . str_replace(' ', '_', strtolower($model)) . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $otherProducts = [
            ['C920 Webcam', 'Webcams'],
            ['Brio 4K Webcam', 'Webcams'],
            ['Blue Yeti Microphone', 'Microphones'],
            ['Z120 Speakers', 'Speakers'],
            ['HDMI Cable 2m', 'Cables'],
            ['Thunderbolt 3 Dock', 'Docking Stations'],
            ['USB-C to HDMI Adapter', 'Adapters'],
            ['7-Port USB Hub', 'USB Hubs'],
            ['WD 2TB External HDD', 'External Storage'],
            ['Archer AX50 Router', 'Network Equipment'],
        ];

        foreach ($otherProducts as $product) {
            DB::table('products')->insert([
                'name' => $product[0],
                'categorie_id' => DB::table('categories')->where('name', $product[1])->value('id'),
                'date' => now()->subDays(rand(1, 365)),
                'file_name' => str_replace(' ', '_', strtolower($product[0])) . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}