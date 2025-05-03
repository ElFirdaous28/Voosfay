<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function addToWallet(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;

        $wallet->increment('balance', $request->amount);

        return response()->json([
            'message' => 'Funds added successfully.',
            'balance' => $wallet->balance,
        ]);
    }
    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        if ($user->wallet->balance < $request->amount) {
            return response()->json(['error' => 'Insufficient wallet balance.'], 400);
        }

        WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        $user->wallet->decrement('balance', $request->amount);

        return response()->json(['message' => 'Withdrawal request submitted.']);
    }
    public function listTransactions()
    {
        $user = Auth::user();
        $transactions = $user->wallet->transactions;

        return response()->json($transactions);
    }
}
