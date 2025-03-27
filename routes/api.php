<?php

use App\Http\Controllers\Api\V1\Authentication\AuthController;
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

Route::prefix('v1')->group(function () {
    Route::get('/user', function () {
        return 'hi user';
    })->middleware(['auth:sanctum', 'role:user']);

    Route::get('/admin', function () {
        return 'hi admin';
    })->middleware(['auth:sanctum', 'role:admin']);
});
