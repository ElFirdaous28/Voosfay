<?php

namespace App\Helpers;

use App\Models\Review;
use App\Models\User;

abstract class RatingsHelper
{

    public static function user_average_rating(string $userId)
    {
        $reviews = Review::all();
        return $reviews;
    }
}
