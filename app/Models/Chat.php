<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function user1()
    {
        return $this->belongsTo(User::class, 'user_1_id');
    }

    // Define the relationship to the second user (user_2)
    public function user2()
    {
        return $this->belongsTo(User::class, 'user_2_id');
    }
}
