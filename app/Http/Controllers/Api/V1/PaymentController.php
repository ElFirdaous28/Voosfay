<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function createPaymentIntent($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $platformCommission = env('PLATFORM_COMMISSION_PERCENT');
        $amount = $reservation->ride->price + ($reservation->ride->price * $platformCommission / 100);

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount * 100, // amount in cents
            'currency' => 'usd', // or your preferred currency
            'metadata' => [
                'reservation_id' => $reservationId,
            ],
        ]);

        // Create a record in the payments table
        $payment = Payment::create([
            'reservation_id' => $reservationId,
            'method' => 'stripe',
            'amount' => $amount,
            'status' => 'pending',
            'transaction_id' => $paymentIntent->id,
            'stripe_payment_intent_id' => $paymentIntent->id,
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
            'paymentId' => $payment->id,
        ]);
    }
}
