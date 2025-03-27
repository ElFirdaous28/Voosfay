<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    /** @use HasFactory<\Database\Factories\RideFactory> */
    use HasFactory;

    protected $fillable = [
        'start_location',
        'ending_location',
        'start_time',
        'available_seats',
        'price',
        'status',
        'luggage_allowed',
        'pet_allowed',
        'conversation_allowed',
        'music_allowed'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
