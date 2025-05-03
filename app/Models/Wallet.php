<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function addTransaction($amount, $type, $description = null)
    {
        $this->transactions()->create([
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
        ]);
    }
}
