<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,user_id',
            'message' => 'required|string',
        ]);

        $senderId = Auth::id();
        if (!$senderId) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        $recipientId = $request->input('recipient_id');


        $userOneId = min($senderId, $recipientId);
        $userTwoId = max($senderId, $recipientId);


        $conversation = Conversation::firstOrCreate(
            ['user_one_id' => $userOneId, 'user_two_id' => $userTwoId]
        );


        $message = new Message();
        $message->conversation_id = $conversation->id;
        $message->sender_id = $senderId;
        $message->message = $request->input('message');
        $message->save();

        return response()->json([
            'message' => 'Mensaje enviado exitosamente.',
            'data' => $message,
        ], 201);
    }


    public function getMessages($recipientId)
    {
        $senderId = Auth::id();


        $userOneId = min($senderId, $recipientId);
        $userTwoId = max($senderId, $recipientId);


        $conversation = Conversation::where('user_one_id', $userOneId)
            ->where('user_two_id', $userTwoId)
            ->first();

        if (!$conversation) {
            return response()->json([
                'message' => 'No hay conversación entre estos usuarios.',
                'data' => [],
            ], 200);
        }


        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'message' => 'Mensajes recuperados exitosamente.',
            'data' => $messages,
        ], 200);
    }
}