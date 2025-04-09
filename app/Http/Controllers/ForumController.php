<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateForumRequest;
use App\Http\Resources\ForumResource;
use App\Http\Resources\UserResource;
use App\Models\Forum;
use App\Models\Logs;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(Request $request, $theme_id)
    {
        $perPage = $request->query('per_page', 5);
        $orderBy = $request->query('orderBy', 'views');
        $sort = $request->query('sort', 'asc');
        $forums = Forum::where('theme_id', $theme_id)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);
        return ForumResource::collection($forums);
    }

    public function create()
    {
        //
    }

    public function store(CreateForumRequest $request)
    {


        $forum = Forum::create([
            'name' => $request->validated()['name'],
            'description' => $request->validated()['description'],
            'theme_id' => $request->validated()['theme_id'],
            'user_id' => auth()->guard('sanctum')->user()->id,
        ]);

        Logs::create([
            'user_id' => auth()->guard('sanctum')->user()->id,
            'type' => "create_forum",
            'data' => json_encode($forum),
        ]);

        return response()->json(new ForumResource($forum), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ForumResource::collection(Forum::all()->where('theme_id', $id));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
