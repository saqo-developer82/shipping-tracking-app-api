<?php

namespace App\Services;

use App\Repositories\Contracts\TrackingRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TrackingService
{
    private TrackingRepositoryInterface $repository;

    public function __construct(TrackingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieves tracking information for the given tracking code.
     *
     * This method attempts to fetch tracking details using a cache mechanism.
     * If the data is not found in the cache, it queries the repository
     * and stores the result in the cache for future lookups.
     *
     * Logs are generated to indicate the success or failure of the lookup operation.
     *
     * @param string $trackingCode The unique code used to identify the tracking information.
     * @return array|null The tracking information, or null if not found.
     */
    public function getTrackingInfo(string $trackingCode): ?array
    {
        $cacheKey = "tracking_{$trackingCode}";
        $cacheTtl = config('tracking.cache_ttl', 300); // 5 minutes

        return Cache::remember($cacheKey, $cacheTtl, function () use ($trackingCode) {
            Log::info("Fetching tracking info for: {$trackingCode}");

            $result = $this->repository->findByTrackingCode($trackingCode);

            if ($result) {
                Log::info("Tracking info found for: {$trackingCode}");
                return $result;
            }

            Log::warning("No tracking info found for: {$trackingCode}");
            return null;
        });
    }

    public function createTracking(array $data): bool
    {
        try {
            $result = $this->repository->createTrackingEntry($data);

            if ($result) {
                // Clear cache for this tracking code
                Cache::forget("tracking_{$data['tracking_code']}");
                Log::info("Created tracking entry: {$data['tracking_code']}");
            }

            return $result;
        } catch (\Exception $e) {
            Log::error("Failed to create tracking entry: " . $e->getMessage());
            return false;
        }
    }
}
