<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Http\Requests\MessagesCreateRequest;
use App\Http\Resources\MessageStatisticResource;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\MessageSent;
use Pusher\Pusher;

class MessageController extends Controller
{

    public function index($forum_id)
    {
        return MessageResource::collection(Message::where('forum_id', $forum_id)->get());
    }

    public function statisticShow(Request $request)
    {
        return MessageStatisticResource::collection(Message::all());
    }

    // public function store(MessagesCreateRequest $request)
    // {
    //     try {
    //         $options = array(
    //             'cluster' => 'eu',
    //             'useTLS' => true,
    //             'curl_options' => [
    //                 CURLOPT_SSL_VERIFYHOST => 0,
    //                 CURLOPT_SSL_VERIFYPEER => 0
    //             ]
    //         );

    //     $pusher->trigger('my-channel', 'my-event', [

    //         'message' => 'hello world'

    //       ]);
    // }

    public function store(MessagesCreateRequest $request)
    {
        $validated = $request->validated();

        $message = Message::create([
            'forum_id' => $validated['forum_id'],
            'user_id' => auth()->guard('sanctum')->user()->id, // Assuming the user is authenticated
            'text' => $validated['text'],
            'message_id' => $validated['message_id'] ?? null, // Set to null if not provided
        ]);

        Logs::create([
            'user_id' => auth()->guard('sanctum')->user()->id,
            'type' => 'create_message',
            'data' => json_encode($message),
        ]);

            $options = array(
                'cluster' => 'eu',
                'useTLS' => true,
                'curl_options' => [
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0
                ]
            );

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            $pusher->trigger('forum-chat-' . $request->forum_id, 'new-post', [
                'post' => new MessageResource($message)
            ]);

        return response()->json([
            'message' => new MessageResource($message),
        ], 201);

    }
    // }

    public function update(MessagesCreateRequest $request, string $id)
    {
        $message = Message::find($id); // Find the message or fail if not found
        $validated = $request->validated();

        $message->update([
            'text' => $validated['text'],
        ]);

        Logs::create([
            'user_id' => auth()->guard('sanctum')->user()->id,
            'type' => 'update_message',
            'data' => json_encode($message),
        ]);

        $options = array(
            'cluster' => 'eu',
            'useTLS' => true,
            'curl_options' => [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ]
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('forum-chat-' . $message->forum_id, 'updated-post', [
            'post' => new MessageResource($message)
        ]);

        return response()->json([
            'message' => new MessageResource($message),
        ], 200);
    }

    public function destroy(string $id)
    {
        Log::info('Attempting to delete message with ID: ' . $id);

        $message = Message::find($id); // Find the message or fail if not found
        $message->update([
            'status' => 'deleted',
        ]);

        Logs::create([
            'user_id' => auth()->guard('sanctum')->user()->id,
            'type' => 'delete_message',
            'data' => json_encode($message),
        ]);

        Log::info(`Message with ID: ` . $id . ` has been successfully archived.
        data: ` . $message);

        $options = array(
            'cluster' => 'eu',
            'useTLS' => true,
            'curl_options' => [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ]
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('forum-chat-' . $message->forum_id, 'delete-post', [
            'post' => new MessageResource($message)
        ]);

        return response()->json([
            'message' => 'Message deleted successfully.',
        ], 200);
    }
}
