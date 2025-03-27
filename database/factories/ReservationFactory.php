<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        return [
            'ride_id' => Ride::factory(),
            'user_id' => User::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['confirmed', 'pending', 'cancelled']),
        ];
    }
}
