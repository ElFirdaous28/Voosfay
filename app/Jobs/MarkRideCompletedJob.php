<?php

namespace App\Jobs;

use App\Models\Ride;
use App\Services\RidePaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MarkRideCompletedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected RidePaymentService $ridePaymentService;
    public $ride;

    public function __construct(Ride $ride, RidePaymentService $ridePaymentService)
    {
        $this->ride = $ride;
        $this->ridePaymentService = $ridePaymentService;
    }

    public function handle(): void
    {
        $ride = Ride::find($this->ride->id);

        if (!$ride) {
            Log::error("MarkRideCompletedJob: Ride not found.");
            return;
        }

        if ($ride->status !== 'completed') {
            $ride->status = 'completed';
            $ride->save();
            Log::info("Ride ID {$ride->id} marked as completed.");

            foreach ($ride->reservations()->where('status', 'confirmed')->get() as $reservation) {
                try {
                    $this->ridePaymentService->transfer(
                        $reservation->user,
                        $ride->user,
                        $ride->price,
                        $ride
                    );
                    Log::info("Payment from passenger {$reservation->user_id} to driver {$ride->user_id} for ride {$ride->id} processed.");
                } catch (\Exception $e) {
                    Log::error("Payment error for ride {$ride->id}: " . $e->getMessage());
                }
            }
        }
    }
}
