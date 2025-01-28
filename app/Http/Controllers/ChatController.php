<?php

namespace App\Http\Controllers;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function postChat(Request $request)
    {
        try {
            $user1ID = $request->input('user1ID');
            $user2ID = $request->input('user2ID');

            // Check if a chat already exists between the two users
            $chatExists = Chat::where(function ($query) use ($user1ID, $user2ID) {
                $query->where('user_1_id', $user1ID)
                    ->where('user_2_id', $user2ID);
            })->orWhere(function ($query) use ($user1ID, $user2ID) {
                $query->where('user_1_id', $user2ID)
                    ->where('user_2_id', $user1ID);
            })->exists();

            // If no chat exists, create a new one
            if (!$chatExists) {
                $chat = Chat::create([
                    'user_1_id' => $user1ID,
                    'user_2_id' => $user2ID,
                    'created_at' => now(),
                    'last_sent_message' => '',
                ]);
            } else {
                // Fetch the existing chat
                $chat = Chat::where(function ($query) use ($user1ID, $user2ID) {
                    $query->where('user_1_id', $user1ID)
                        ->where('user_2_id', $user2ID);
                })->orWhere(function ($query) use ($user1ID, $user2ID) {
                    $query->where('user_1_id', $user2ID)
                        ->where('user_2_id', $user1ID);
                })->first();
            }

            return response()->json(['data' => $chat->id], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
