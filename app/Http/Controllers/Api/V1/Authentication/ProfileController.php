<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Helpers\RatingsHelper;
use App\Helpers\RideHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\VehicleRequest;
use App\Jobs\ActivateSuspendedUser;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile(int $user)
    {
        $user = User::select('id', 'name', 'email', 'phone', 'bio', 'picture')->findOrFail($user);

        return response()->json([
            'success' => true,
            'message' => 'User profile retrieved successfully.',
            'data' => [
                'rating_average' => RatingsHelper::userAverageRating($user->id) ?? 0,
                'join_rides_number' => RideHelper::getJoinedRideCount($user),
                'offerd_rides_number' => RideHelper::getOfferedRideCount($user),
                'user' => $user,
                'is_own_profile' => Auth::id() === $user->id
            ],
        ]);
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        // return $request;
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user->update($request->all());

        if ($request->hasFile('picture')) {
            if ($user->picture) {
                Storage::disk('public')->delete($user->picture);
            }
            $path = $request->file('picture')->store('profile_pictures', 'public');
            $user->picture = $path;
        }

        $user->update($request->except('picture'));

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Old password is incorrect'], 400);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully. This is a soft delete.'
        ]);
    }

    public function restoreAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::onlyTrashed()->where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Account not found or not deleted.'], 404);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }
        $user->restore();

        Auth::login($user);
        $token = $user->createToken($user->name);

        return response()->json([
            'message' => 'Account restored successfully',
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function reviews(User $user)
    {
        $reviews = $user->reviewsReceived()->with(['reviewer' => function ($query) {
            $query->select('id', 'name', 'picture');
        }])->get();
        $reviews_number = $reviews->count();

        $rating_distribution = $reviews->groupBy('rating')->map->count();
        $rating_percentage = [];
        if ($reviews_number > 0) {
            for ($i = 1; $i <= 5; $i++) {
                $rating_percentage["{$i}_star"] = ($rating_distribution[$i] ?? 0) / $reviews_number * 100;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'User reviews retrieved successfully.',
            'reviews_number' => $reviews_number,
            'reviews' => $reviews,
            'rating_average' => RatingsHelper::userAverageRating($user->id) ?? 0,
            'rating_average_last_month' => RatingsHelper::userAverageRatingLastMonth($user->id) ?? 0,
            'rating_distribution' => $rating_percentage
        ]);
    }

    public function vehicle()
    {
        $vehicle = Auth::user()->vehicle;
        return response()->json([
            'success' => true,
            'message' => 'User profile retrieved successfully.',
            'vehicle' => $vehicle
        ]);
    }

    public function updateVehicle(VehicleRequest $request)
    {
        $vehicle = Vehicle::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->validated()
        );

        return response()->json([
            'message' => 'Vehicle updated successfully',
            'vehicle' => $vehicle
        ]);
    }
}
