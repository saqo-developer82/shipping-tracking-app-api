<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\SqliteTrackingRepository;
use App\Models\Tracking;
use App\DTOs\TrackingInfoDto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SqliteTrackingRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private SqliteTrackingRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SqliteTrackingRepository();
    }

    public function test_find_by_tracking_code_returns_correct_data(): void
    {
        // Arrange
        $trackingCode = Tracking::factory()->create([
            'tracking_code' => 'TRK123456789',
            'status' => 'in_transit',
            'carrier' => 'DHL'
        ]);

        // Act
        $result = $this->repository->findByTrackingCode('TRK123456789');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('TRK123456789', $result->trackingCode);
        $this->assertEquals('in_transit', $result->status);
        $this->assertEquals('DHL', $result->carrier);
    }

    public function test_find_by_tracking_code_returns_null_for_nonexistent(): void
    {
        // Act
        $result = $this->repository->estimateDelivery('NONEXISTENT');

        // Assert
        $this->assertNull($result);
    }

    public function test_store_creates_new_tracking_code(): void
    {
        // Arrange
        $dto = new TrackingInfoDto(
            trackingCode: 'NEW123456789',
            estimatedDeliveryDate: Carbon::now()->addDays(5),
            status: 'processing',
            carrier: 'UPS',
            destination: 'Los Angeles, CA'
        );

        // Act
        $result = $this->repository->store($dto);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('tracking_codes', [
            'tracking_code' => 'NEW123456789',
            'status' => 'processing',
            'carrier' => 'UPS'
        ]);
    }

    public function test_store_updates_existing_tracking_code(): void
    {
        // Arrange
        Tracking::factory()->create([
            'tracking_code' => 'EXISTING123',
            'status' => 'processing'
        ]);

        $dto = new TrackingInfoDto(
            trackingCode: 'EXISTING123',
            estimatedDeliveryDate: Carbon::now()->addDays(2),
            status: 'shipped',
            carrier: 'FedEx',
            destination: 'Chicago, IL'
        );

        // Act
        $result = $this->repository->store($dto);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('tracking_codes', [
            'tracking_code' => 'EXISTING123',
            'status' => 'shipped',
            'carrier' => 'FedEx'
        ]);
    }
}
