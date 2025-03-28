<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class ForceDeleteInactiveUsers extends Command
{
    protected $signature = 'users:force-delete-inactive';
    protected $description = 'Force delete soft-deleted users after a specified period';

    public function handle()
    {
        $deletionThreshold = Carbon::now()->subDays(30);

        $deletedUsers = User::onlyTrashed()
            ->where('deleted_at', '<=', $deletionThreshold)
            ->forceDelete();

        $this->info("Deleted {$deletedUsers} inactive user accounts.");
    }
}