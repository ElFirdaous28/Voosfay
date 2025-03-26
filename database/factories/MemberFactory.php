<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'phone' => $this->faker->unique()->phoneNumber,
            'vehicle_type' => $this->faker->randomElement(['Sedan', 'SUV', 'Hatchback', 'Wagon']),
            'vehicle_plate' => strtoupper(Str::random(3) . $this->faker->numerify('###')),
            'vehicle_color' => $this->faker->colorName,
        ];
    }
}
