<?php

use App\Http\Controllers\Api\V1\Authentication\AuthController;
use App\Http\Controllers\Api\V1\Authentication\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});


// profile routes
Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile/{user}', [ProfileController::class, 'profile']);
        Route::put('profile', [ProfileController::class, 'updateProfile']);
        Route::patch('profile', [ProfileController::class, 'changePassword']);
        Route::delete('profile', [ProfileController::class, 'deleteAccount']);
        
        Route::get('reviews/{user}', [ProfileController::class, 'reviews']);
        Route::get('vehicle', [ProfileController::class, 'vehicle']);
        Route::put('vehicle', [ProfileController::class, 'updateVehicle']);
    });
    Route::put('profile/restore-account', [ProfileController::class, 'restoreAccount']);
});
