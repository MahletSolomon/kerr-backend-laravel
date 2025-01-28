<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBid extends Model
{
    protected $table = 'job_bids';

    protected $fillable = [
        'user_id',
        'job_id',
        'bid_pitch',
        'bid_counter_price',
        'created_at',
    ];
}
