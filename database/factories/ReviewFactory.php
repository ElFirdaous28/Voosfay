<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'reservation_id' =>Reservation::inRandomOrder()->first()->id,
            'reviewer_id' =>User::inRandomOrder()->first()->id,
            'reviewed_id' =>User::inRandomOrder()->first()->id,
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'comment' => $this->faker->optional()->paragraph,
        ];
    }
}
