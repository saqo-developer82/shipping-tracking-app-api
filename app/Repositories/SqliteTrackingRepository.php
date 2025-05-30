<?php

namespace App\Repositories;

use App\Models\Tracking;
use App\Services\Contracts\TrackingRepositoryInterface;
use Carbon\Carbon;

class SqliteTrackingRepository implements TrackingRepositoryInterface
{
    public function findByTrackingCode(string $trackingCode): ?array
    {
        $tracking = Tracking::where('tracking_code', $trackingCode)->first();

        if (!$tracking) {
            return null;
        }

        return [
            'tracking_code' => $tracking->tracking_code,
            'estimated_delivery_date' => $tracking->estimated_delivery_date->format('Y-m-d'),
            'status' => $tracking->status,
            'carrier' => $tracking->carrier,
            'origin' => $tracking->origin,
            'destination' => $tracking->destination
        ];
    }

    public function createTrackingEntry(array $data): bool
    {
        return Tracking::create($data) ? true : false;
    }
}
