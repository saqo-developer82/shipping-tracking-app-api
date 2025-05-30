<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackingRequest;
use App\Services\TrackingService;
use Illuminate\Http\JsonResponse;

class TrackingController extends Controller
{
    private TrackingService $trackingService;

    public function __construct(TrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function track(TrackingRequest $request): JsonResponse
    {
        $trackingCode = strtoupper(trim($request->input('tracking_code')));

        $trackingInfo = $this->trackingService->getTrackingInfo($trackingCode);

        if (!$trackingInfo) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking code not found.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tracking information retrieved successfully.',
            'data' => $trackingInfo
        ]);
    }

    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'storage_driver' => config('tracking.storage_driver')
        ]);
    }
}
