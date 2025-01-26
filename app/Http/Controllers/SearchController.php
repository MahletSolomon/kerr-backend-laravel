<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
class SearchController extends Controller
{
    function searchUser(Request $request){
        try {
            $search = $request->query('search');

            if (empty($search)) {
                return response()->json(['message' => 'Search query is required'], 400);
            }

            $users = User::where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->select('id', 'first_name', 'last_name', 'profile_picture')
                ->get();

            return response()->json(['data' => $users], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'An error occurred while searching users'], 500);
        }
    }
    public function searchJob(Request $request)
    {
        try {
            $search = $request->query('search');

            if (empty($search)) {
                return response()->json(['message' => 'Search term is required'], 400);
            }

            $jobs = Job::select(
                'users.id as user_id',
                'users.first_name',
                'users.last_name',
                'users.location',
                'users.profile_picture',
                'jobs.id as job_id',
                'jobs.job_title',
                'jobs.job_description',
                'jobs.job_price',
                DB::raw('GROUP_CONCAT(tags.name) as tags')
            )
                ->leftJoin('users', 'jobs.user_id', '=', 'users.id')
                ->leftJoin('tags', 'jobs.id', '=', 'tags.job_id')
                ->where('jobs.job_title', 'LIKE', "%{$search}%")
                ->orWhere('tags.name', 'LIKE', "%{$search}%")
                ->groupBy('jobs.id')
                ->orderBy('jobs.id')
                ->get();

            return response()->json(['data' => $jobs], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
    public function searchPost(Request $request)
    {
        try {
            $search = $request->query('search');

            if (empty($search)) {
                return response()->json(['message' => 'Search term is required'], 400);
            }

            $posts = Post::select(
                'posts.id',
                'posts.post_thumbnail',
                'posts.post_title',
                'posts.view',
                'users.id as user_id',
                'users.profile_picture',
                'users.first_name',
                'users.last_name'
            )
                ->leftJoin('users', 'posts.user_id', '=', 'users.id')
                ->where('posts.post_title', 'LIKE', "%{$search}%")
                ->get();

            return response()->json(['data' => $posts], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
}
