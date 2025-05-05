<?php

namespace App\Jobs;

use App\Models\Ride;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\RidePaymentService;

class UpdateRideStatusJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected RidePaymentService $ridePaymentService;

    public $ride;

    public function __construct(Ride $ride, RidePaymentService $ridePaymentService)
    {
        $this->ridePaymentService = $ridePaymentService;
        $this->ride = $ride;
    }

    public function handle()
    {
        if (!$this->ride) {
            Log::error("Ride not found.");
            return;
        }

        // Ensure start_time and ending_time are Carbon instances
        $startTime = Carbon::parse($this->ride->start_time);
        $endTime = Carbon::parse($this->ride->ending_time);

        // Check if the ride should be marked as in_progress
        if (Carbon::now()->gte($startTime) && $this->ride->status !== 'in_progress') {
            $this->ride->status = 'in_progress';
            $this->ride->save();
            Log::info("Ride status updated to 'in_progress' for ride ID: {$this->ride->id}");
        }

        // Check if the ride should be marked as completed
        if (Carbon::now()->gte($endTime) && $this->ride->status !== 'completed') {
            $this->ride->status = 'completed';
            $this->ride->save();
            $this->completeRide($this->ride);
            Log::info("Ride status updated to 'completed' for ride ID: {$this->ride->id}");
        }
    }

    private function completeRide(Ride $ride)
    {
        foreach ($ride->reservations()->where('status', 'confirmed')->get() as $reservation) {
            $passenger = $reservation->user;
            $driver = $ride->user;
            $price = $ride->price;

            try {
                $this->ridePaymentService->transfer($passenger, $driver, $price, $ride);
                Log::info("Payment processed for ride ID: {$ride->id}, from passenger {$passenger->id} to driver {$driver->id}");
            } catch (\Exception $e) {
                Log::error("Error processing payment for ride ID: {$ride->id}: " . $e->getMessage());
            }
        }

        // Optionally log that the ride is completed
        Log::info("Ride completed and payments processed for ride ID: {$ride->id}");
    }
}
