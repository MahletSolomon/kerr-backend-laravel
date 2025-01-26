<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    // Relationship to JobContract
    public function jobContracts()
    {
        return $this->hasMany(JobContract::class, 'job_id');
    }
}
