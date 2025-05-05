<?php

namespace App\Jobs;

use App\Models\Ride;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MarkRideInProgressJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ride;

    public function __construct(Ride $ride)
    {
        $this->ride = $ride;
    }

    public function handle(): void
    {
        $ride = Ride::find($this->ride->id);

        if (!$ride) {
            Log::error("MarkRideInProgressJob: Ride not found.");
            return;
        }

        if ($ride->status !== 'in_progress') {
            $ride->status = 'in_progress';
            $ride->save();
            Log::info("Ride ID {$ride->id} marked as in_progress.");
        }
    }
}
