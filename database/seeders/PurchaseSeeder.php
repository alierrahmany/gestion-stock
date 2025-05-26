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
            case 'Keyboards': return rand(20, 200) / 100;
            case 'Mice': return rand(15, 150) / 100;
            case 'Monitors': return rand(150, 1500) / 100;
            case 'Headsets': return rand(50, 300) / 100;
            case 'Webcams': return rand(40, 200) / 100;
            case 'Cables': return rand(5, 20) / 100;
            case 'Adapters': return rand(10, 50) / 100;
            case 'USB Hubs': return rand(15, 100) / 100;
            case 'External Storage': return rand(60, 500) / 100;
            case 'Network Equipment': return rand(50, 800) / 100;
            default: return rand(10, 500) / 100;
        }
    }
}