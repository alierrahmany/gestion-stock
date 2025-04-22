<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clients')->insert([
            ['name' => 'John Doe', 'email' => 'john@example.com', 'contact' => '0612345678', 'address' => '123 Elm St', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'contact' => '0623456789', 'address' => '456 Maple Ave', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alice Johnson', 'email' => 'alice@example.com', 'contact' => '0634567890', 'address' => '789 Oak Dr', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bob Brown', 'email' => 'bob@example.com', 'contact' => '0645678901', 'address' => '321 Pine Rd', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Charlie Green', 'email' => 'charlie@example.com', 'contact' => '0656789012', 'address' => '654 Cedar Ln', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Diana Blue', 'email' => 'diana@example.com', 'contact' => '0667890123', 'address' => '987 Birch Blvd', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Evan White', 'email' => 'evan@example.com', 'contact' => '0678901234', 'address' => '258 Willow Way', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fiona Black', 'email' => 'fiona@example.com', 'contact' => '0689012345', 'address' => '147 Aspen Cir', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'George Gray', 'email' => 'george@example.com', 'contact' => '0690123456', 'address' => '369 Chestnut St', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Helen Red', 'email' => 'helen@example.com', 'contact' => '0701234567', 'address' => '753 Poplar Pl', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
