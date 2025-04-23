<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Payment;

class WebhookController extends Controller
{
    public function handleStripeWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;

                    $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
                    $payment->status = 'completed';
                    $payment->save();

                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;

                    $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
                    $payment->status = 'failed';
                    $payment->save();
                    break;

                default:
                    return response()->json(['message' => 'Event type not handled'], 400);
            }

            return response()->json(['message' => 'Webhook handled'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Webhook Error: ' . $e->getMessage()], 400);
        }
    }
}
