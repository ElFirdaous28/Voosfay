<?php

namespace App\Helpers;

use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;

abstract class RatingsHelper
{

    public static function userAverageRating(string $userId)
    {
        return Review::where('reviewed_id', $userId)->avg('rating');
    }

    public static function userAverageRatingLastMonth(string $userId)
    {
        $lastMonth = Carbon::now()->subMonth();

        return Review::where('reviewed_id', $userId)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->avg('rating');
    }
}
