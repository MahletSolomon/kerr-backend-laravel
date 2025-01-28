<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'gallerys'; // Ensure the table name is correct

    protected $fillable = ['user_id', 'post_id'];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
