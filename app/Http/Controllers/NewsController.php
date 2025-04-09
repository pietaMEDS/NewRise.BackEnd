<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResources;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        $news = News::where('isPinned', '0')
            ->paginate($perPage);
        return NewsResources::collection($news);
    }

    public function showPinnedNews(Request $request)
    {
        $pinnedNews = News::where('isPinned', '1')->orderBy('created_at', 'desc')->limit(2)->get();
        return NewsResources::collection($pinnedNews);
    }
}
