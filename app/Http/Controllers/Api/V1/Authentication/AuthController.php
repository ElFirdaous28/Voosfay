<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $role = User::count() === 0 ? "super_admin" : "user";
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        $fields['role'] = $role;

        $user = User::create($fields);
        // Wallet::create([
        //     'user_id' => $user->id,
        //     'balance' => 0,
        // ]);

        $token = $user->createToken($request->name);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($user->name);
            return response()->json([
                'user' => $user,
                'token' => $token->plainTextToken
            ]);
        }

        //check in delete users 
        $trashedUser = User::onlyTrashed()->where('email', $request->email)->first();

        if ($trashedUser && Hash::check($request->password, $trashedUser->password)) {
            $trashedUser->restore();
            Auth::login($trashedUser);

            $token = $trashedUser->createToken($trashedUser->name);
            return response()->json([
                'message' => 'Account restored successfully.',
                'user' => $trashedUser,
                'token' => $token->plainTextToken
            ]);
        }

        return response()->json([
            'errors' => [
                'email' => ['The provided credentials are incorrect.']
            ]
        ], 422);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'You are logged out.'
        ];
    }
}
