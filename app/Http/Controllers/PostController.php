<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function getPost($id)
    {
        try {
            $post = Post::select('posts.*', 'users.first_name', 'users.last_name', 'users.profile_picture')
                ->leftJoin('users', 'posts.user_id', '=', 'users.id')
                ->where('posts.id', $id)
                ->first();
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }
            return response()->json(['data' => $post], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function postPost(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId' => 'required|integer',
                'postTitle' => 'required|string',
                'postCaption' => 'required|string',
                'postThumbnail' => 'required|string',
                'postImage' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $userId = $request->input('userId');
            $postTitle = $request->input('postTitle');
            $postCaption = $request->input('postCaption');
            $postThumbnail = $request->input('postThumbnail');
            $postImage = json_encode($request->input('postImage'));

            $post = Post::create([
                'user_id' => $userId,
                'post_title' => $postTitle,
                'post_caption' => $postCaption,
                'post_thumbnail' => $postThumbnail,
                'post_image' => $postImage,
            ]);

            return response()->json(['postID' => $post->id], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function getAllPost(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'page' => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $page = $request->query('page', 1);

            $offset = ($page - 1) * 40;

            $posts = Post::select(
                'posts.id', 'posts.post_thumbnail',
                'posts.post_title', 'posts.created_at',
                'posts.view', 'users.first_name',
                'users.last_name', 'users.profile_picture'
            )
                ->leftJoin('users', 'posts.user_id', '=', 'users.id')
                ->orderBy('posts.id')
                ->offset($offset)
                ->limit(40)
                ->get();

            return response()->json(['data' => $posts], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function updatePostView($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:posts,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $post = Post::find($id);

            $post->increment('view');

            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function deletePost($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:posts,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $post = Post::find($id);

            $post->delete();

            return response()->json(['message' => 'Post deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
}
