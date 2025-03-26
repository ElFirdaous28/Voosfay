<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    /** @use HasFactory<\Database\Factories\StopFactory> */
    use HasFactory;

    protected $fillable = [
        'ride_id', 'place_name', 'time'
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
