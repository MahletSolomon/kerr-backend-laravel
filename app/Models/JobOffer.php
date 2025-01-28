<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    use HasFactory;

    // Optional: If your table name is not the plural of the model name
    protected $table = 'job_offers';

    // Optional: If you want to disable timestamps
    public $timestamps = true;

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'job_id',
    ];

    // Relationship to Job
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    // Relationship to User (Receiver)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
