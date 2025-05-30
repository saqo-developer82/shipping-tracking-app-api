<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrackingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('trackings')->insert([
            [
                'tracking_code' => 'TRK123456789',
                'estimated_delivery_date' => '2025-06-15',
                'status' => 'In Transit',
                'carrier' => 'Example Carrier',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'tracking_code' => 'TRK456789123',
                'estimated_delivery_date' => '2025-06-15',
                'status' => 'In Transit',
                'carrier' => 'Example Carrier',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'tracking_code' => 'TRK987654321',
                'estimated_delivery_date' => '2025-07-15',
                'status' => 'In Transit',
                'carrier' => 'Example Carrier',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
