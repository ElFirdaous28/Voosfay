<?php

namespace App\Services;

use App\Models\User;
use App\Models\Ride;
use App\Models\Wallet;

class RidePaymentService
{
    public function transfer(User $passenger, User $driver, float $amount, Ride $ride): void
    {
        $commissionRate = config('services.platform_commission_percent', 15) / 100;
        $commission = round($amount * $commissionRate, 2);
        $driverAmount = round($amount - $commission, 2);

        $passengerWallet = $passenger->wallet;
        $driverWallet = $driver->wallet;
        $platformWallet = Wallet::firstOrCreate(['user_id' => 1]);

        $passengerWallet->decrement('balance', $amount);
        $passengerWallet->addTransaction(-$amount, 'ride_payment', "Payment for ride #{$ride->id}");

        $driverWallet->increment('balance', $driverAmount);
        $driverWallet->addTransaction($driverAmount, 'ride_income', "Ride payment from user #{$passenger->id}");

        $platformWallet->increment('balance', $commission);
        $platformWallet->addTransaction($commission, 'platform_commission', "Commission for ride #{$ride->id}");
    }
}
