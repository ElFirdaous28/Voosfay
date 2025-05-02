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
    public function users(Request $request)
    {
        $query = User::orderBy('id', 'desc')->withCount('reportsAgainst');
        $query->where('role', '!=', 'super_admin');

        if ($request->has('role') && in_array($request->role, ['admin', 'user'])) {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        $users = $query->get();

        return response()->json([
            'users' => $users,
        ]);
    }

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

    public function forceDeleteUser($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user) {
            $user->forceDelete();
            return response()->json(['message' => 'User permanently deleted.'], 200);
        }
    }

    public function changeStatus(Request $request, User $user)
    {
       $val= $request->validate([
            'status' => 'required|in:active,suspended,banned',
            'suspend_duration' => 'required_if:status,suspended|nullable',
        ]);
        return $val;

        if ($request->status === 'suspended') {
            $user->suspended_until = now()->addDays((int) $request->suspend_duration);
        } else {
            $user->suspended_until = null;
        }

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'message' => "User status successfully changed to '{$user->status}'.",
            'user' => $user
        ]);
    }
}
