<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        $reporter = Member::factory()->create();
        $reportedMember = Member::factory()->create();

        return [
            'reporter_id' => $reporter->id,
            'reported_member_id' => $reportedMember->id,
            'reason' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['pending', 'reviewed', 'resolved']),
        ];
    }
}
