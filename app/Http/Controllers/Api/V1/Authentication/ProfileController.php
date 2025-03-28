<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Helpers\RatingsHelper;
use App\Helpers\RideHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\VehicleRequest;
use App\Models\User;
use App\Models\Vehicle;
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
            'rating_average' => RatingsHelper::userAverageRating($user->id),
            'join_rides_number' => RideHelper::getJoinedRideCount($user),
            'offerd_rides_number' => RideHelper::getOfferedRideCount($user),

            'data' => [
                'user' => $user,
                'is_own_profile' => Auth::id() === $user->id
            ],
        ]);
    }

    public function updateProfile(UpdateUserRequest $request)
    {
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

    public function reviews(User $user)
    {
        $reviews = $user->reviewsReceived()->get();

        return response()->json([
            'success' => true,
            'message' => 'User reviews retrieved successfully.',
            'reviews' => $reviews,
            'rating_average' => RatingsHelper::userAverageRating($user->id),
            'rating_average_last_month' => RatingsHelper::userAverageRatingLastMonth($user->id),

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
