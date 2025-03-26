<?php

namespace Database\Factories;

use App\Models\Ride;
use App\Models\Stop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stop>
 */
class StopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Stop::class;

    public function definition()
    {
        return [
            'ride_id' => Ride::factory(),
            'place_name' => $this->faker->city,
            'time' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
