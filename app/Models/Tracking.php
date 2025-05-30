<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_code',
        'estimated_delivery_date',
        'status',
        'carrier',
        'origin',
        'destination'
    ];

    protected $casts = [
        'estimated_delivery_date' => 'date'
    ];
}
