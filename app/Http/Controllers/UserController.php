<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;


class UserController extends Controller
{
    function getUserPost(){
         $posts = Post::with(['user' => function($query) {
                $query->select('id', 'first_name', 'last_name', 'profile_picture'); 
            }])
            ->select('id', 'post_thumbnail', 'post_title', 'created_at', 'view', 'user_id')
            ->get();

        return response()->json(['data' => $posts]);
    }
}
