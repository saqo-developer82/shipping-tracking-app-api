<?php

namespace App\Repositories;

use App\Services\Contracts\TrackingRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CsvTrackingRepository implements TrackingRepositoryInterface
{
    private string $csvFile;

    public function __construct()
    {
        $this->csvFile = config('tracking.csv_file', 'tracking_data.csv');
        $this->ensureCsvExists();
    }

    public function findByTrackingCode(string $trackingCode): ?array
    {
        if (!Storage::exists($this->csvFile)) {
            return null;
        }

        $csvContent = Storage::get($this->csvFile);
        $lines = explode("\n", $csvContent);

        // Skip header row
        for ($i = 1; $i < count($lines); $i++) {
            $data = str_getcsv($lines[$i]);
            if (count($data) >= 6 && $data[0] === $trackingCode) {
                return [
                    'tracking_code' => $data[0],
                    'estimated_delivery_date' => $data[1],
                    'status' => $data[2],
                    'carrier' => $data[3],
                    'origin' => $data[4],
                    'destination' => $data[5]
                ];
            }
        }

        return null;
    }

    public function createTrackingEntry(array $data): bool
    {
        $csvLine = implode(',', [
            $data['tracking_code'],
            $data['estimated_delivery_date'],
            $data['status'],
            $data['carrier'],
            $data['origin'] ?? '',
            $data['destination'] ?? ''
        ]);

        return Storage::append($this->csvFile, $csvLine);
    }

    private function ensureCsvExists(): void
    {
        if (!Storage::exists($this->csvFile)) {
            $header = "tracking_code,estimated_delivery_date,status,carrier,origin,destination";
            Storage::put($this->csvFile, $header);

            // Add some sample data
            $sampleData = [
                "TRK123456789,2024-06-15,In Transit,DHL,New York,Los Angeles",
                "TRK987654321,2024-06-12,Delivered,FedEx,Chicago,Miami",
                "TRK456789123,2024-06-18,Processing,UPS,Seattle,Denver"
            ];

            foreach ($sampleData as $data) {
                Storage::append($this->csvFile, $data);
            }
        }
    }
}
