<?php

use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/track', [TrackingController::class, 'track'])
        ->middleware('throttle:60,1'); // 60 requests per minute
    Route::get('/health', [TrackingController::class, 'health']);
});
