<?php

use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::post('/track', [TrackingController::class, 'track'])->middleware('throttle:60,1'); // 60 requests per minute
