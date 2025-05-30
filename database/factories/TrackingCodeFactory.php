<?php

namespace Database\Factories;

use App\Models\Tracking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class TrackingCodeFactory extends Factory
{
    protected $model = Tracking::class;

    public function definition(): array
    {
        return [
            'tracking_code' => strtoupper($this->faker->lexify('???') . $this->faker->numerify('#########')),
            'estimated_delivery_date' => Carbon::now()->addDays($this->faker->numberBetween(1, 10)),
            'status' => $this->faker->randomElement(['processing', 'in_transit', 'out_for_delivery', 'delivered']),
            'carrier' => $this->faker->randomElement(['DHL', 'UPS', 'FedEx', 'USPS']),
            'destination' => $this->faker->city() . ', ' . $this->faker->stateAbbr(),
        ];
    }
}
