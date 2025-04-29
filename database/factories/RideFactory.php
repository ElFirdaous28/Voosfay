<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ride>
 */
class RideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('now', '+1 month');
        $endingTime = $this->faker->dateTimeBetween('now', '+1 month +2hour');

        return [
            'start_location' => $this->faker->city,
            'ending_location' => $this->faker->city,
            'start_time' => $startTime,
            'ending_time' => $endingTime,
            'available_seats' => $this->faker->numberBetween(1, 4),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'status' => $this->faker->randomElement(['available', 'full', 'in_progress', 'completed', 'cancelled']),
            'luggage_allowed' => $this->faker->boolean(70),
            'pet_allowed' => $this->faker->boolean(30),
            'conversation_allowed' => $this->faker->boolean(80),
            'music_allowed' => $this->faker->boolean(60),
            'food_allowed' => $this->faker->boolean(60),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
