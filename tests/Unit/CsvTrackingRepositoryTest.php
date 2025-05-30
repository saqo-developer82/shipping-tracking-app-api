<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\CsvTrackingRepository;
use App\DTOs\TrackingInfoDto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CsvTrackingRepositoryTest extends TestCase
{
    private CsvTrackingRepository $repository;
    private string $testCsvPath;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a temporary CSV file for testing
        $this->testCsvPath = storage_path('app/test_tracking.csv');
        config(['tracking.csv_file_path' => $this->testCsvPath]);

        $this->repository = new CsvTrackingRepository();
    }

    protected function tearDown(): void
    {
        // Clean up test file
        if (file_exists($this->testCsvPath)) {
            unlink($this->testCsvPath);
        }
        parent::tearDown();
    }
}
