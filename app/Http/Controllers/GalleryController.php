<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\User;
class GalleryController extends Controller
{
    public function postGallery(Request $request)
    {
        try {
            $userID = $request->input('userID');
            $postID = $request->input('postID');

            $exists = Gallery::where('user_id', $userID)
                ->where('post_id', $postID)
                ->exists();

            if (!$exists) {
                $gallery = Gallery::create([
                    'user_id' => $userID,
                    'post_id' => $postID,
                    'created_at' => now(),
                ]);

                return response()->json(['saveID' => $gallery->id], 201);
            }

            return response()->json(['message' => 'Gallery entry already exists'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function getGallery($id,Request $request)
    {
        try {
            $page = $request->query('page', 1); // Default to page 1 if not provided
            $perPage = 40; // Number of items per page
            $offset = ($page - 1) * $perPage; // Calculate offset

            // Fetch gallery posts using a direct query
            $posts = Post::select(
                'posts.id',
                'posts.post_thumbnail',
                'posts.post_title',
                'posts.created_at',
                'posts.view',
                'users.first_name',
                'users.last_name',
                'users.profile_picture'
            )
                ->leftJoin('users', 'posts.user_id', '=', 'users.id')
                ->leftJoin('gallerys', 'posts.id', '=', 'gallerys.post_id')
                ->where('gallerys.user_id', $id) // Filter by the gallery user ID
                ->orderBy('gallerys.id') // Order by gallery ID (or any other field)
                ->offset($offset)
                ->limit($perPage)
                ->get();

            // Return the data
            return response()->json(['data' => $posts], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }

    }
    public function deleteGallery(Request $request, $id)
    {
        try {
            $postID = $request->query('postID');

            if (!$id || !$postID) {
                return response()->json(['message' => 'Parameter not valid'], 400);
            }

            $deleted = Gallery::where('user_id', $id)
                ->where('post_id', $postID)
                ->delete();

            if ($deleted) {
                return response()->json(['message' => 'Post deleted'], 200);
            } else {
                return response()->json(['message' => 'Post not found or already deleted'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
}
