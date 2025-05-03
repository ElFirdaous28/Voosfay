<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\Ride;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'users' => $this->getUserStats(),
                'rides' => $this->getRideStats(),
                'reports' => $this->getReportStats(),
                'payments' => $this->getPaymentStats(),
            ]
        ]);
    }

    private function getUserStats()
    {
        return [
            'total' => User::count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'top_rated' => User::select('id', 'name', 'picture')
                ->withAvg('reviewsGiven', 'rating')
                ->orderByDesc('reviews_given_avg_rating')
                ->take(5)
                ->get(),
        ];
    }

    private function getRideStats()
    {
        return [
            'total' => Ride::count(),
            'this_month' => Ride::whereMonth('created_at', now()->month)->count(),
            'total_reservations' => Reservation::count(),
            'pending' => Ride::where('status', 'pending')->count(),
            'completed' => Ride::where('status', 'completed')->count(),
            'canceled' => Ride::where('status', 'canceled')->count(),
        ];
    }

    private function getReportStats()
    {
        return [
            'total' => Report::count(),
            'resolved' => Report::where('status', 'resolved')->count(),
        ];
    }

    private function getPaymentStats()
    {
        return [
            'total_received' => WalletTransaction::where('wallet_id', 1)->sum('amount'),
            'this_month' => WalletTransaction::where('wallet_id', 1)
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];
    }
}
