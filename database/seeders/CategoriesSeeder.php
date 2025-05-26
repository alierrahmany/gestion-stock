<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['Keyboards', 'Computer keyboards of all types'],
            ['Mice', 'Computer mice and pointing devices'],
            ['Monitors', 'Computer displays and screens'],
            ['Headsets', 'Audio headsets with microphones'],
            ['Webcams', 'Computer cameras for video conferencing'],
            ['Microphones', 'Standalone microphones for computers'],
            ['Speakers', 'Computer speaker systems'],
            ['Cables', 'Various computer cables and connectors'],
            ['Docking Stations', 'Laptop docking solutions'],
            ['Adapters', 'Various computer adapters'],
            ['USB Hubs', 'USB expansion devices'],
            ['External Storage', 'External HDDs and SSDs'],
            ['Network Equipment', 'Routers, switches, etc.'],
            ['KVM Switches', 'Keyboard-Video-Mouse switches'],
            ['Laptop Stands', 'Ergonomic laptop supports'],
            ['Monitor Arms', 'Adjustable monitor mounts'],
            ['Mouse Pads', 'Desk surfaces for mice'],
            ['Cleaning Kits', 'Equipment cleaning supplies'],
            ['Surge Protectors', 'Power protection devices'],
            ['UPS Systems', 'Uninterruptible power supplies'],
            ['Graphics Tablets', 'Digital drawing devices'],
            ['Barcode Scanners', 'Inventory scanning devices'],
            ['Presentation Remotes', 'Slide advancement tools'],
            ['Server Racks', 'Equipment mounting racks'],
            ['Cable Management', 'Cable organization solutions'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category[0],
                'description' => $category[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}