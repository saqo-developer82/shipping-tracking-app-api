<?php

namespace Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tracking;

class TrackingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_track_existing_package()
    {
        Tracking::create([
            'tracking_code' => 'TEST123456789',
            'estimated_delivery_date' => '2024-06-15',
            'status' => 'In Transit',
            'carrier' => 'Test Carrier'
        ]);

        $response = $this->postJson('/api/v1/track', [
            'tracking_code' => 'TEST123456789'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'tracking_code' => 'TEST123456789',
                    'status' => 'In Transit'
                ]
            ]);
    }

    public function test_returns_404_for_non_existent_tracking_code()
    {
        $response = $this->postJson('/api/v1/track', [
            'tracking_code' => 'NONEXISTENT'
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Tracking code not found.'
            ]);
    }

    public function test_validates_tracking_code_format()
    {
        $response = $this->postJson('/api/v1/track', [
            'tracking_code' => 'short'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tracking_code']);
    }
}
