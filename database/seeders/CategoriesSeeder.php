<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // Clear tables in correct order
    DB::table('products')->truncate();
    DB::table('categories')->truncate();

    DB::table('categories')->insert([
        ['name' => 'Laptops', 'description' => 'Portable computers', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Keyboards', 'description' => 'Typing devices', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Mice', 'description' => 'Pointing devices', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Monitors', 'description' => 'Display screens', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Printers', 'description' => 'Printing devices', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Scanners', 'description' => 'Scanning devices', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Routers', 'description' => 'Network devices', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Cables', 'description' => 'Connection wires', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Headsets', 'description' => 'Audio devices', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Webcams', 'description' => 'Camera devices', 'created_at' => now(), 'updated_at' => now()],
    ]);

    }
}
