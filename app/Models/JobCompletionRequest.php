<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCompletionRequest extends Model
{
    use HasFactory;

    protected $table = 'job_completion_requests';

    // Relationship to JobContract
    public function jobContract()
    {
        return $this->belongsTo(JobContract::class, 'job_id');
    }

    // Relationship to User (if applicable)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
