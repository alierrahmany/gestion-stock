<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('purchases')->insert([
            ['product_id' => 1, 'supplier_id' => 1, 'quantity' => 10, 'price' => 800.00, 'date' => '2025-04-01', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 2, 'supplier_id' => 2, 'quantity' => 15, 'price' => 90.50, 'date' => '2025-04-02', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 3, 'supplier_id' => 3, 'quantity' => 20, 'price' => 75.90, 'date' => '2025-04-03', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 4, 'supplier_id' => 4, 'quantity' => 8, 'price' => 400.00, 'date' => '2025-04-04', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 5, 'supplier_id' => 5, 'quantity' => 5, 'price' => 200.00, 'date' => '2025-04-05', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 6, 'supplier_id' => 6, 'quantity' => 12, 'price' => 120.00, 'date' => '2025-04-06', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 7, 'supplier_id' => 7, 'quantity' => 10, 'price' => 95.00, 'date' => '2025-04-07', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 8, 'supplier_id' => 8, 'quantity' => 50, 'price' => 7.99, 'date' => '2025-04-08', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 9, 'supplier_id' => 9, 'quantity' => 25, 'price' => 55.00, 'date' => '2025-04-09', 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 10, 'supplier_id' => 10, 'quantity' => 10, 'price' => 70.00, 'date' => '2025-04-10', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
