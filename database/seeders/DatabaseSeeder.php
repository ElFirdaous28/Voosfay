<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Ride;
use App\Models\Stop;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->withRole()->count(1)->create();

        User::factory()->count(5)->create();
        Member::factory()->count(5)->create();
        Ride::factory()->count(10)->create();
        Stop::factory()->count(5)->create();
        Reservation::factory()->count(20)->create();
        Payment::factory()->count(15)->create();
        Review::factory()->count(10)->create();
        Report::factory()->count(3)->create();
    }
}
