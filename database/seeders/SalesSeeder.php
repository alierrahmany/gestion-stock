<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesSeeder extends Seeder
{
    public function run()
    {
        $products = DB::table('products')->get();
        $clients = DB::table('clients')->pluck('id')->toArray();

        foreach ($products as $product) {
            $purchase = DB::table('purchases')
                         ->where('product_id', $product->id)
                         ->first();

            $unitCost = $purchase ? ($purchase->price / $purchase->quantity) : $this->estimateUnitCost($product->categorie_id);

            for ($i = 0; $i < rand(1, 3); $i++) {
                $quantity = rand(10, 500);
                $margin = rand(15, 30) / 100;
                $discount = $quantity > 100 ? 0.95 : 1.0;
                $discount = $quantity > 300 ? 0.90 : $discount;

                DB::table('sales')->insert([
                    'product_id' => $product->id,
                    'client_id' => $clients[array_rand($clients)],
                    'quantity' => $quantity,
                    'price' => ($unitCost * (1 + $margin) * $discount) * $quantity,
                    'date' => now()->subDays(rand(1, 365)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function estimateUnitCost($categoryId)
    {
        $category = DB::table('categories')->where('id', $categoryId)->first()->name;

        switch($category) {
            case 'Claviers': return rand(25, 250) / 100;
            case 'Souris': return rand(20, 180) / 100;
            case 'Écrans': return rand(200, 2000) / 100;
            case 'Casques': return rand(60, 350) / 100;
            case 'Webcams': return rand(50, 250) / 100;
            default: return rand(15, 600) / 100;
        }
    }
}
