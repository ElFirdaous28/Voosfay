<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminCreatedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function addAdmin(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],
            'phone' => 'nullable|unique:users,phone',
        ]);
        // generate password
        $password = Str::random(12);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'gender' => $validated['gender'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'role' => 'admin',
            'status' => 'active',
        ]);
        $admin->notify(new AdminCreatedNotification($admin, $password));

        return response()->json([
            'message' => 'Admin user created successfully.',
            'admin' => $admin->only(['id', 'name', 'email']),
        ], 201);
    }

    public function changeStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,banned',
            'suspend_duration' => 'nullable|in:1,10,15,30',
            'suspend_until' => 'nullable|date|after:now',
        ]);

        if ($request->status === 'suspended') {
            if ($request->suspend_until) {
                $user->suspended_until = Carbon::parse($request->suspend_until);
            } elseif ($request->suspend_duration) {
                $user->suspended_until = now()->addDays((int) $request->suspend_duration);
            } else {
                return response()->json(['message' => 'Suspension requires a duration or end date.'], 422);
            }
        } else {
            $user->suspended_until = null;
        }

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'message' => "User status changed to '{$user->status}'.",
            'user' => $user
        ]);
    }
}
