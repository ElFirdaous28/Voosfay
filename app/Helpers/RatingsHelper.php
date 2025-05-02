<?php

namespace App\Helpers;

use App\Models\Review;
use Carbon\Carbon;

abstract class RatingsHelper
{
    public static function userAverageRating(string $userId)
    {
        $avg = Review::where('reviewed_id', $userId)->avg('rating');
        return $avg !== null ? round($avg, 2) : 0;
    }

    public static function userAverageRatingLastMonth(string $userId)
    {
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $avg = Review::where('reviewed_id', $userId)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->avg('rating');

        return $avg !== null ? round($avg, 2) : null;
    }
}
