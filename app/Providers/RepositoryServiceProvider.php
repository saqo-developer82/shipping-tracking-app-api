<?php

namespace App\Providers;

use App\Repositories\Contracts\TrackingRepositoryInterface;
use App\Repositories\CsvTrackingRepository;
use App\Repositories\SqliteTrackingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TrackingRepositoryInterface::class, function ($app) {
            $driver = config('tracking.storage_driver', 'sqlite');

            return match ($driver) {
                'csv' => new CsvTrackingRepository(),
                'sqlite' => new SqliteTrackingRepository(),
                default => new SqliteTrackingRepository(),
            };
        });
    }

    public function boot(): void
    {
        //
    }
}
