<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\JobCompletionRequest;
class UserController extends Controller
{
    function postUser(Request $request){
       try{
           $validator = Validator::make($request->all(), [
               'username' => 'required|string|unique:users',
               'password' => 'required|string|min:6',
               'firstName' => 'required|string',
               'lastName' => 'required|string',
               'profile_picture' => 'nullable|string',
               'phone' => 'nullable|string',
               'email' => 'required|string|email|unique:users',
               'gender' => 'nullable|string',
               'location' => 'nullable|string',
               'industry' => 'nullable|string',
               'experience' => 'nullable|string',
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 400);
           }

           $encryptPass = Hash::make($request->password);

           $user = new User();

           // Fill the user attributes
           $user->fill([
               'username' => $request->username,
               'password' => Hash::make($request->password),
               'first_name' => $request->firstName,
               'last_name' => $request->lastName,
               'profile_picture' => $request->profile_picture,
               'phone' => $request->phone,
               'email' => $request->email,
               'gender' => $request->gender,
               'location' => $request->location,
               'industry' => $request->industry,
               'experience' => $request->experience,
           ]);

           // Save the user to the database
           $user->save();

           return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
       } catch (\Exception $error) {
            return response()->json([
                'error' => 'Issue on the server side',
                'message' => $error->getMessage(),
            ], 500);
        }
    }
    function getUser($id){
        return response()->json(User::find($id));
    }
    public function getUserJob($id, Request $request)
    {
        try {
            $type = $request->query('type', 0);

            $results = DB::select('CALL sp_GetUserJobs(?, ?)', [$id, $type]);

            return response()->json(['data' => $results], 200);
        } catch (\Exception $error) {
            return response()->json(['message' => 'Error from the server'], 500);
        }
    }

    function getUserPost($id){

        $user = User::find($id)->with(['post' => function($query) {$query->select('id', 'post_thumbnail', 'post_title', 'created_at', 'view', 'user_id');}])
            ->select('id', 'first_name', 'last_name', 'profile_picture')
            ->get();

        return response()->json(['data'=>$user]);
    }
    public function getAllUserCompleteRequest($id)
    {
        try {
            $requests = JobCompletionRequest::with(['jobContract.job'])
                ->where('user_id', $id)
                ->get();

            return response()->json(['data' => $requests], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an issue with the server'], 500);
        }
    }
}
