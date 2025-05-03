<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function transferRidePayment(User $passenger, User $driver, float $amount): bool
    {
        $commissionPercent = config('services.platform_commission_percent');
        $commission = round($amount * ($commissionPercent / 100), 2);
        $driverAmount = $amount - $commission;

        return DB::transaction(function () use ($passenger, $driver, $amount, $commission, $driverAmount) {
            $passengerWallet = $passenger->wallet;
            $driverWallet = $driver->wallet;

            $passengerWallet->decrement('balance', $amount);
            $passengerWallet->addTransaction(-$amount, 'ride_payment', 'Payment for ride');

            $driverWallet->increment('balance', $driverAmount);
            $driverWallet->addTransaction($driverAmount, 'transfer', 'Ride earnings after commission');

            return true;
        });
    }
}
