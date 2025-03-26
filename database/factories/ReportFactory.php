<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        return [
            'reporter_id' => Member::inRandomOrder()->first()->id,
            'reported_member_id' => Member::inRandomOrder()->first()->id,
            'reason' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['pending', 'reviewed', 'resolved']),
        ];
    }
}
