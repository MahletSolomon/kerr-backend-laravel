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
    public function chatsAsUser1()
    {
        return $this->hasMany(Chat::class, 'user_1_id');
    }

    // Define the chats where the user is user_2
    public function chatsAsUser2()
    {
        return $this->hasMany(Chat::class, 'user_2_id');
    }

    // Combine both relationships to get all chats for the user
    public function chats()
    {
        return $this->chatsAsUser1->merge($this->chatsAsUser2);
    }
    // Relationship to Job Offers (Received by the user)
    public function jobOffers()
    {
        return $this->hasMany(JobOffer::class);
    }
}
