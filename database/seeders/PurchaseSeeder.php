<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseSeeder extends Seeder
{
    public function run()
    {
        $products = DB::table('products')->get();
        $suppliers = DB::table('suppliers')->pluck('id')->toArray();

        foreach ($products as $product) {
            $category = DB::table('categories')->where('id', $product->categorie_id)->first();

            DB::table('purchases')->insert([
                'product_id' => $product->id,
                'supplier_id' => $suppliers[array_rand($suppliers)],
                'quantity' => 1000,
                'price' => $this->getBulkUnitPrice($category->name) * 1000,
                'date' => now()->subDays(rand(1, 365)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getBulkUnitPrice($categoryName)
    {
        switch($categoryName) {
            case 'Claviers': return rand(20, 200) / 100;
            case 'Souris': return rand(15, 150) / 100;
            case 'Écrans': return rand(150, 1500) / 100;
            case 'Casques': return rand(50, 300) / 100;
            case 'Webcams': return rand(40, 200) / 100;
            case 'Câbles': return rand(5, 20) / 100;
            case 'Adaptateurs': return rand(10, 50) / 100;
            case 'Hubs USB': return rand(15, 100) / 100;
            case 'Stockage externe': return rand(60, 500) / 100;
            case 'Équipement réseau': return rand(50, 800) / 100;
            default: return rand(10, 500) / 100;
        }
    }
}
