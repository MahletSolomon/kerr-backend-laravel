<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;



class User extends Model
{
    protected $fillable = [
        'username',
        'password',
        'first_name',
        'last_name',
        'profile_picture',
        'phone',
        'email',
        'gender',
        'location',
        'industry',
        'experience',
    ];

    function post(){
        return $this->hasMany(Post::class);
    }
}
