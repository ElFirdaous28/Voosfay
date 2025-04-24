<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActivateSuspendedUsers extends Command
{
    protected $signature = 'users:activate-user';
    protected $description = 'Activate users whose suspension has expired';

    public function handle()
    {
        $now = Carbon::now();

        $resumed = User::where('status', 'suspended')
            ->whereNotNull('suspended_until')
            ->where('suspended_until', '<=', $now)
            ->update([
                'status' => 'active',
                'suspended_until' => null
            ]);

        $this->info("Resumed {$resumed} user(s).");
    }
}
