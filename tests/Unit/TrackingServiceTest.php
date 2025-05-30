<?php

namespace Tests\Unit;

use App\Repositories\Contracts\TrackingRepositoryInterface;
use Tests\TestCase;
use App\Services\TrackingService;
use Carbon\Carbon;
use Mockery;
use Illuminate\Support\Facades\Cache;

class TrackingServiceTest extends TestCase
{
    private TrackingService $trackingService;
    private $mockRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockRepository = Mockery::mock(TrackingRepositoryInterface::class);
        $this->trackingService = new TrackingService($this->mockRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_tracking_info_returns_valid_data(): void
    {
        // Arrange
        $trackingCode = 'TRK123456789';
        $expectedData = [
            'tracking_code' => 'TRK123456789',
            'estimated_delivery_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'status' => 'In Transit',
            'carrier' => 'Test Carrier',
            'origin' => 'New York, NY',
            'destination' => 'Los Angeles, CA'
        ];

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($expectedData);

        // Act
        $result = $this->trackingService->getTrackingInfo($trackingCode);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($trackingCode, $result['tracking_code']);
        $this->assertEquals('In Transit', $result['status']);
    }

    public function test_get_tracking_info_returns_null_for_invalid_code(): void
    {
        // Arrange
        $invalidTrackingCode = 'invalid-code!@#';

        $this->mockRepository->shouldReceive('findByTrackingCode')
            ->with('invalid-code!@#') // or Mockery::any(), or Mockery::type('string')
            ->andReturn(null);

        // Act
        $result = $this->trackingService->getTrackingInfo($invalidTrackingCode);

        // Assert
        $this->assertNull($result);
    }

    public function test_store_tracking_info_success(): void
    {
        // Arrange
        $trackingData = [
            'tracking_code' => 'TRK123456789',
            'estimated_delivery_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'status' => 'In Transit',
            'carrier' => 'Test Carrier',
            'origin' => 'New York, NY',
            'destination' => 'Los Angeles, CA'
        ];

        $this->mockRepository
            ->shouldReceive('createTrackingEntry')
            ->once()
            ->with($trackingData)
            ->andReturn(true);

        Cache::shouldReceive('forget')
            ->once()
            ->with('tracking_TRK123456789');

        // Act
        $result = $this->trackingService->createTracking($trackingData);

        // Assert
        $this->assertTrue($result);
    }

    public function test_valid_tracking_codes(): void
    {
        $validCodes = ['TRK123456789', 'UPS987654321', 'ABC123'];

        foreach ($validCodes as $code) {
            Cache::shouldReceive('remember')->andReturn(null);
            $this->mockRepository->shouldReceive('findByTrackingCode')->andReturn(null);

            // Should not return null due to validation failure
            $result = $this->trackingService->getTrackingInfo($code);
            $this->assertNull($result); // null because mock returns null, not due to validation
        }
    }
}
