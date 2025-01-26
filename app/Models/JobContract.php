<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobContract extends Model
{
    use HasFactory;

    protected $table = 'job_contracts';

    // Relationship to Job
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    // Relationship to JobCompletionRequest
    public function jobCompletionRequests()
    {
        return $this->hasMany(JobCompletionRequest::class, 'job_id');
    }
}
