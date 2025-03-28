<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = ['Mercedes', 'Audi', 'Toyota', 'Ford', 'BMW'];
        $models = ['A-Class', 'Q5', 'Corolla', 'Focus', 'X5'];

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'vehicle_info' => $this->faker->randomElement($brands) . ' ' . $this->faker->randomElement($models),
            'vehicle_plate' => strtoupper(Str::random(3) . $this->faker->numerify('###')),
            'vehicle_color' => $this->faker->safeColorName(),
        ];
    }
}
