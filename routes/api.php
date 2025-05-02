<?php

use App\Http\Controllers\Api\v1\AdminController;
use App\Http\Controllers\Api\v1\AdminManagementController;
use App\Http\Controllers\Api\V1\Authentication\AuthController;
use App\Http\Controllers\Api\V1\Authentication\ProfileController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\ReservationController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\RideController;
use App\Http\Controllers\Api\V1\StatisticsController;
use App\Http\Controllers\Api\V1\StopController;
use App\Http\Controllers\Api\V1\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', 'active'])->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware(['auth:sanctum', 'active']);
});


Route::prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum', 'active'])->group(function () {
        // profile routes
        Route::get('profile/{user}', [ProfileController::class, 'profile']);
        Route::put('profile', [ProfileController::class, 'updateProfile']);
        Route::patch('profile', [ProfileController::class, 'changePassword']);
        Route::delete('profile', [ProfileController::class, 'deleteAccount']);

        Route::get('user/reviews/{user}', [ProfileController::class, 'reviews']);
        Route::get('vehicle', [ProfileController::class, 'vehicle']);
        Route::put('vehicle', [ProfileController::class, 'updateVehicle']);
        // admin
        Route::post('/admin/users/add', [AdminController::class, 'addAdmin']);
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::delete('/admin/users/{id}/force-delete', [AdminController::class, 'forceDeleteUser']);
        Route::patch('admin/users/{user}/status', [AdminController::class, 'changeStatus']);
        Route::post('admin/users/{user}/warn', [ReportController::class, 'sendWarning']);

        // rides routes
        Route::apiResource('rides', RideController::class);
        Route::patch('/rides/{ride}/status', [RideController::class, 'updateStatus']);
        Route::get('search/rides', [RideController::class, 'search']);
        Route::get('user/rides/offered', [RideController::class, 'offeredRides']);
        Route::get('user/rides/joined', [RideController::class, 'joinedRides']);

        // stops routes
        Route::apiResource('stops', StopController::class);
        Route::get('rides/{ride}/stops', [StopController::class, 'rideStops']);

        // reservations routes
        Route::apiResource('reservation', ReservationController::class)->except(['destroy', 'update']);
        Route::get('/ride/reservations/{ride}', [ReservationController::class, 'rideReservations']);
        Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus']);

        // Reviews routes
        Route::apiResource('reviews', ReviewController::class);

        // report routes
        Route::apiResource('reports', ReportController::class)->except('update');
        Route::patch('reports/{report}/status', [ReportController::class, 'updateStatus']);

        // paymetn routes
        Route::post('payment/{reservation}', [PaymentController::class, 'createPaymentIntent']);
        Route::post('/stripe/webhook', [WebhookController::class, 'handleStripeWebhook']);

        // statistics
        Route::get('statistics', [StatisticsController::class, 'index']);
    });
    Route::put('profile/restore-account', [ProfileController::class, 'restoreAccount']);
});
