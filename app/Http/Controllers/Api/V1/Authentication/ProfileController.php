<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Helpers\RatingsHelper;
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
            'average' => RatingsHelper::user_average_rating($userId),

            'data' => [
                'user' => $user,
                'is_own_profile' => Auth::id() === $user->id
            ],
        ]);
    }
}
