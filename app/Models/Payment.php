<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'reservation_id', 'method', 'amount', 
        'status', 'transaction_id'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
