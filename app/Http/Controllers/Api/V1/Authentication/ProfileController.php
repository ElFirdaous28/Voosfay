<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Helpers\RatingsHelper;
use App\Helpers\RideHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(String $userId)
    {
        $user = User::select('id', 'name', 'email', 'bio', 'picture', 'role')->findOrFail($userId);

        return response()->json([
            'success' => true,
            'message' => 'User profile retrieved successfully.',
            'rating_average' => RatingsHelper::userAverageRating($userId),
            'join_rides_number'=>RideHelper::getJoinedRideCount($user),
            'offerd_rides_number'=>RideHelper::getOfferedRideCount($user),

            'data' => [
                'user' => $user,
                'is_own_profile' => Auth::id() === $user->id
            ],
        ]);
    }
}
