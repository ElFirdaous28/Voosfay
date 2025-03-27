<?php

namespace App\Helpers;

use App\Models\User;

class RideHelper
{
    public static function getJoinedRideCount(User $user)
    {
        return $user->joinedRides()->whereIn('status', ['confirmed', 'completed'])->count();
    }

    public static function getOfferedRideCount(User $user)
    {
        return $user->offeredRides()->count();
    }

    public static function getTotalRidesCount(User $user)
    {
        $joinedRides = self::getJoinedRideCount($user);
        $offeredRides = self::getOfferedRideCount($user);

        return $joinedRides + $offeredRides;
    }
}
