<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PlatformWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Wallet::where('user_id', null)->exists()) {
            Wallet::create([
                'user_id' => 1, 
                'balance' => 0,
            ]);
            Log::info("Platform wallet created successfully.");
        } else {
            
            Log::info("Platform wallet already exists.");
        }
    }
}
