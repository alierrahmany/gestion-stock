<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesSeeder extends Seeder
{
    public function run()
    {
        // Get actual product IDs from the database
        $productIds = Product::pluck('id')->toArray();

        if (empty($productIds)) {
            echo "No products found in database. Please run ProductsSeeder first.\n";
            return;
        }

        DB::table('sales')->insert([
            ['product_id' => 1, 'client_id' => 1, 'quantity' => 2, 'price' => 950.00, 'date' => '2025-04-11', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 2, 'client_id' => 2, 'quantity' => 1, 'price' => 120.00, 'date' => '2025-04-11', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 3, 'client_id' => 3, 'quantity' => 2, 'price' => 100.00, 'date' => '2025-04-12', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 4, 'client_id' => 4, 'quantity' => 1, 'price' => 550.00, 'date' => '2025-04-13', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 5, 'client_id' => 5, 'quantity' => 1, 'price' => 280.00, 'date' => '2025-04-13', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 6, 'client_id' => 6, 'quantity' => 2, 'price' => 160.00, 'date' => '2025-04-14', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 7, 'client_id' => 7, 'quantity' => 3, 'price' => 110.00, 'date' => '2025-04-14', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 8, 'client_id' => 8, 'quantity' => 4, 'price' => 12.00, 'date' => '2025-04-15', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 9, 'client_id' => 9, 'quantity' => 1, 'price' => 75.00, 'date' => '2025-04-15', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 10, 'client_id' => 10, 'quantity' => 1, 'price' => 85.00, 'date' => '2025-04-16', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
