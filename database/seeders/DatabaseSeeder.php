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
use App\Models\Vehicle;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(5)->create();
        Vehicle::factory()->count(5)->create();
        Ride::factory()->count(1)->create();
        Stop::factory()->count(5)->create();
        Reservation::factory()->count(5)->create();
        Review::factory()->count(10)->create();
        Report::factory()->count(3)->create();
        Wallet::factory()->count(10)->create();
        WalletTransaction::factory()->count(5)->create();
        WithdrawalRequest::factory()->count(5)->create();
    }
}
