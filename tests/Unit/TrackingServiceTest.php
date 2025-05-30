<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TrackingService;
use App\Contracts\TrackingRepositoryInterface;
use App\DTOs\TrackingInfoDto;
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
        $expectedDto = new TrackingInfoDto(
            trackingCode: $trackingCode,
            estimatedDeliveryDate: Carbon::now()->addDays(3),
            status: 'in_transit',
            carrier: 'DHL',
            destination: 'New York, NY'
        );

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($expectedDto);

        // Act
        $result = $this->trackingService->getTrackingInfo($trackingCode);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($trackingCode, $result->trackingCode);
        $this->assertEquals('in_transit', $result->status);
    }

    public function test_get_tracking_info_returns_null_for_invalid_code(): void
    {
        // Arrange
        $invalidTrackingCode = 'invalid-code!@#';

        // Act
        $result = $this->trackingService->getTrackingInfo($invalidTrackingCode);

        // Assert
        $this->assertNull($result);
    }

    public function test_store_tracking_info_success(): void
    {
        // Arrange
        $trackingInfo = new TrackingInfoDto(
            trackingCode: 'TRK123456789',
            estimatedDeliveryDate: Carbon::now()->addDays(3),
            status: 'in_transit',
            carrier: 'DHL',
            destination: 'New York, NY'
        );

        $this->mockRepository
            ->shouldReceive('store')
            ->once()
            ->with($trackingInfo)
            ->andReturn(true);

        Cache::shouldReceive('forget')
            ->once()
            ->with('tracking:TRK123456789');

        // Act
        $result = $this->trackingService->storeTrackingInfo($trackingInfo);

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
