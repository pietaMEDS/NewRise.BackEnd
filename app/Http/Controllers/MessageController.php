<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Http\Requests\MessagesCreateRequest;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($forum_id)
    {
        return MessageResource::collection(Message::where('forum_id', $forum_id)->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MessagesCreateRequest $request)
    {
        $validated = $request->validated();

        // Check if 'message_id' exists and is not null
        if (array_key_exists('message_id', $validated) && $validated['message_id'] !== null) {
            // Handle the logic for when message_id is present
            // For example, you might want to set visibility or perform some action
            // $visibilityMessage = Message::find($validated['message_id']);
            // Perform actions based on the visibilityMessage if needed
        }

        // Create the new message
        $message = Message::create([
            'forum_id' => $validated['forum_id'],
            'user_id' => auth()->guard('sanctum')->user()->id, // Assuming the user is authenticated
            'text' => $validated['text'],
            'message_id' => $validated['message_id'] ?? null, // Set to null if not provided
        ]);

        return response()->json([
            'message' => new MessageResource($message),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MessagesCreateRequest $request, string $id)
    {
        // \Log::info('Request data:', $request->all());
        $message = Message::find($id); // Find the message or fail if not found
        $validated = $request->validated();

        $message->update([
            'text' => $validated['text'],
        ]);

        return response()->json([
            'message' => new MessageResource($message),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = Message::findOrFail($id); // Find the message or fail if not found
        $message->delete(); // Delete the message

        return response()->json([
            'message' => 'Message deleted successfully.',
        ], 200);
    }
}
