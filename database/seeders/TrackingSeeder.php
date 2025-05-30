<?php

namespace Database\Seeders;

use App\Models\Tracking;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Tracking::truncate();

        // Create specific test data
        Tracking::factory()->create([
             'tracking_code' => 'TRK123456789',
             'estimated_delivery_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
             'status' => 'In Transit',
             'carrier' => 'Test Carrier',
             'origin' => 'New York, NY',
             'destination' => 'Los Angeles, CA'
        ]);

        // Create random data
        Tracking::factory(20)->create();
        Tracking::factory(5)->delivered()->create();
        Tracking::factory(3)->inTransit()->create();
    }
}
