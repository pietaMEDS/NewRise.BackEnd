<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResources;
use App\Models\Forum;
use App\Models\Logs;
use App\Models\Message;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        $news = News::where('isPinned', '0')->orderBy('created_at', 'desc')
            ->paginate($perPage);
        return NewsResources::collection($news);
    }

    public function showPinnedNews(Request $request)
    {
        $pinnedNews = News::where('isPinned', '1')->orderBy('created_at', 'desc')->limit(2)->get();
        return NewsResources::collection($pinnedNews);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'text' => 'required|string',
            'image' => 'required|string|max:255',
            'isPinned' => 'required|boolean',
            'needForum' => 'required|boolean',
        ]);

        $forum_id = null;

        if($request['needForum']){
            $forum = Forum::create([
                'name' => $validated['name'],
                'description' => 'Обсуждение новостей',
                'theme_id' => null,
                'user_id' => auth()->guard('sanctum')->user()->id,
            ]);

            $message = Message::create([
                'forum_id' => $forum->id,
                'user_id' => auth()->guard('sanctum')->user()->id,
                'text' => $validated['text'],
            ]);

            $forum_id = $forum->id;
        }

        $news = News::create([
            'user_id' => auth()->guard('sanctum')->user()->id,
            'name' => $validated['name'],
            'text' => $validated['text'],
            'image' => $validated['image'],
            'isPinned' => $validated['isPinned'],
            'forum_id' => $forum_id,
        ]);

        return response()->json(NewsResources::make($news), 201);

    }

    public function pin(string $id){
        $user = auth()->guard('sanctum')->user();

        $news = News::find($id);

        if($news->isPinned){
            $news->isPinned = 0;
        }
        else{
            $news->isPinned = 1;
        }
        $news->save();

        Logs::create([
            'user_id'=> $user->id,
            'type' => "news_pin_change",
            'data' => $news->get(),
        ]);

        return response()->json(NewsResources::make($news), 200);
    }

    public function settings(Request $request){
        $user = auth()->guard('sanctum')->user();

        $validated = $request->validate([
            'id' => 'required|integer|exists:news,id',
            'name' => 'required|string|max:255',
            'image' => 'required|string',
        ]);

        $news = News::find($validated['id']);
        $news->name = $validated['name'];
        $news->image = $validated['image'];
        $news->save();

        Logs::create([
            'user_id'=> $user->id,
            'type' => "news_edit",
            'data' => $news->get(),
        ]);
        return response()->json(NewsResources::make($news), 200);
    }

    public function textEdit(Request $request){
        $user = auth()->guard('sanctum')->user();

        $validated = $request->validate([
            'id' => 'required|integer|exists:news,id',
            'text' => 'required|string',
        ]);

        $news = News::find($validated['id']);
        $news->text = $validated['text'];
        $news->save();

        Logs::create([
            'user_id'=> $user->id,
            'type' => "news_edit",
            'data' => $news->get(),
        ]);
        return response()->json(NewsResources::make($news), 200);
    }
}
