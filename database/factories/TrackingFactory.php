<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class TrackingFactory extends Factory
{
    public function definition(): array
    {
        $carriers = ['DHL', 'FedEx', 'UPS', 'USPS', 'Amazon Logistics', 'FedEx Express'];
        $statuses = ['Processing', 'In Transit', 'Out for Delivery', 'Delivered', 'Delayed'];

        $cities = [
            'New York, NY', 'Los Angeles, CA', 'Chicago, IL', 'Houston, TX',
            'Phoenix, AZ', 'Philadelphia, PA', 'San Antonio, TX', 'San Diego, CA',
            'Dallas, TX', 'San Jose, CA', 'Austin, TX', 'Jacksonville, FL',
            'Fort Worth, TX', 'Columbus, OH', 'Charlotte, NC', 'San Francisco, CA'
        ];

        return [
            'tracking_code' => 'TRK' . $this->faker->unique()->numerify('#########'),
            'estimated_delivery_date' => $this->faker->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
            'status' => $this->faker->randomElement($statuses),
            'carrier' => $this->faker->randomElement($carriers),
            'origin' => $this->faker->randomElement($cities),
            'destination' => $this->faker->randomElement($cities),
        ];
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Delivered',
            'estimated_delivery_date' => $this->faker->dateTimeBetween('-1 week', 'now')->format('Y-m-d'),
        ]);
    }

    public function inTransit(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'In Transit',
            'estimated_delivery_date' => $this->faker->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
        ]);
    }
}
