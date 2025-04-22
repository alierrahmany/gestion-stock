<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuppliersSeeder extends Seeder
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
        DB::table('suppliers')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('suppliers')->insert([
            ['name' => 'TechSupply', 'email' => 'contact@techsupply.com', 'contact' => '0123456789', 'address' => '123 Tech Street', 'created_at' => now()],
            ['name' => 'CompuWorld', 'email' => 'info@compuworld.com', 'contact' => '0987654321', 'address' => '45 Silicon Blvd', 'created_at' => now()],
            ['name' => 'ElectroZone', 'email' => 'sales@electrozone.com', 'contact' => '0678123456', 'address' => '67 Market Ave', 'created_at' => now()],
            ['name' => 'HardNet', 'email' => 'support@hardnet.com', 'contact' => '0611223344', 'address' => '89 Components Rd', 'created_at' => now()],
            ['name' => 'Digitech', 'email' => 'orders@digitech.com', 'contact' => '0633445566', 'address' => '9G Gadget Zone', 'created_at' => now()],
            ['name' => 'MegaByte', 'email' => 'hello@megabyte.com', 'contact' => '0655778899', 'address' => '1 Byte Park', 'created_at' => now()],
            ['name' => 'PC Express', 'email' => 'contact@pcexpress.com', 'contact' => '0700112233', 'address' => '100 Desktop St', 'created_at' => now()],
            ['name' => 'OfficeTech', 'email' => 'sales@officetech.com', 'contact' => '0778899001', 'address' => '21 Printer Ln', 'created_at' => now()],
            ['name' => 'CableNet', 'email' => 'info@cablenet.com', 'contact' => '0667788990', 'address' => '333 Wire Street', 'created_at' => now()],
            ['name' => 'GlobalIT', 'email' => 'sales@globalit.com', 'contact' => '0556677889', 'address' => '55 Global Park', 'created_at' => now()],
        ]);
    }
}
