<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\achieve;
use App\Models\Achievement;
use App\Models\Message;
use App\Http\Requests\MessagesCreateRequest;
use App\Http\Resources\MessageStatisticResource;
use App\Models\Logs;
use App\Models\progress_actions;
use App\Models\Rank;
use App\Models\rank_progresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class MessageController extends Controller
{
    public function WS_Send($channel, $event, $data){
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

        $pusher->trigger($channel, $event, $data);
    }

    public function Progress_make($user, $action){
        $progress_data = progress_actions::where("name", $action)->first();
        if($progress_data->method){
            $xp = $progress_data->xp;
        } else {
            $xp = $progress_data->xp * -1;
        }

        $progress = rank_progresses::where('user_id', $user->id)->first();

        if($progress){
            if ($progress->current_xp + $xp > $progress->max_xp) {
                $current_rank = Rank::find($user->rank_id);
                $next_rank = Rank::where('priority', $current_rank->priority + 1)->first();

                if ($next_rank) {
                    $progress->update(['current_xp' => $progress->current_xp + $xp - $progress->max_xp,
                        "max_xp" => $progress->max_xp * 1.5]);
                    $user->update(['rank_id' => $next_rank->id]);
                } else {
                    $progress->update(['current_xp' => $progress->max_xp]);
                }
            } else if ($progress->current_xp + $xp < 0 ) {
                $current_rank = Rank::find($user->rank_id);
                $previous_rank = Rank::where('priority', $current_rank->priority - 1)->first();

                if ($previous_rank) {
                    $progress->update(['current_xp' => 0]);
                    $user->update(['rank_id' => $previous_rank->id]);
                } else {
                    $progress->update(['current_xp' => 0]);
                }
            } else {
                $progress->update(['current_xp' => $progress->current_xp + $xp]);
            }
        } else {
            rank_progresses::create(["user_id"=>$user->id,
                "current_xp"=>0,
                "max_xp"=>10,]);
            $newboy_rank = Rank::where('priority', 1)->first();
            $user->update(['rank_id' => $newboy_rank->id]);

            $newPlayerAchievement = Achievement::where('name', 'First message')->first();

            achieve::create(['user_id'=>$user->id, 'achievement_id'=>$newPlayerAchievement->id]);

            $this->WS_Send('user-Notifier-'.$user->id, 'notify', ["type"=>"Achievement Unlocked", "message"=>$newPlayerAchievement->description]);
        }
    }

    public function index(Request $request, $forum_id)
    {
        $perPage = $request->query('per_page', 10);
        $messages = Message::where([['forum_id', $forum_id],['status', '!=', 'deleted']])
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
        return MessageResource::collection($messages);
    }

    public function statisticShow(Request $request)
    {
        return MessageStatisticResource::collection(Message::orderBy('id', 'desc')->get());
    }

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

        $this->WS_Send('forum-chat-' . $request->forum_id, 'new-post', [
            'post' => new MessageResource($message)
        ]);

        $this->Progress_make(auth()->guard('sanctum')->user(), 'MessageSent');

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

        $this->WS_Send('forum-chat-' . $message->forum_id, 'updated-post', [
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

       $this->WS_Send('forum-chat-' . $message->forum_id, 'delete-post', [
           'post' => new MessageResource($message)
       ]);

        return response()->json([
            'message' => 'Message deleted successfully.',
        ], 200);
    }
}
