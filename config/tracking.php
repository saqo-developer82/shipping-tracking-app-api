<?php

return [
    'storage_driver' => env('TRACKING_STORAGE_DRIVER', 'sqlite'),
    'csv_file' => env('CSV_TRACKING_FILE', 'tracking_data.csv'),
    'cache_ttl' => env('API_CACHE_TTL', 300),
];
