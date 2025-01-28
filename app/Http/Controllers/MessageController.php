<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
class MessageController extends Controller
{
    public function getMessage(Request $request)
    {
        try {
            $chatID = $request->query('chatID');
            $page = $request->query('page', 1); 
            $offset = ($page - 1) * 30;

            $messages = Message::where('chat_id', $chatID)
                ->orderBy('created_at', 'desc')
                ->offset($offset)
                ->limit(30)
                ->get();

            $messages = $messages->reverse();

            return response()->json($messages, 200);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
