<?php

namespace App\Helpers;

use App\Models\Review;
use App\Models\User;

abstract class RatingsHelper
{

    public static function userAverageRating(string $userId)
    {
        return Review::where('reviewed_id', $userId)->avg('rating');
    }
}
