<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'reported_member_id',
        'reason',
        'status'
    ];

    public function reporter()
    {
        return $this->belongsTo(Member::class, 'reporter_id');
    }

    public function reportedMember()
    {
        return $this->belongsTo(Member::class, 'reported_member_id');
    }
}
