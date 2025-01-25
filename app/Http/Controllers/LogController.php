<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;

class LogController extends Controller
{

public function show(Request $request, string $id)
{
    $order = $request->input('order');
    $offset = $request->input('offset');
    $activity = explode(',', $request->input('activity'));
    $type = explode(',', $request->input('type'));
    $logs = Logs::where('user_id', $id);

    foreach ($type as $key => $value) {
        if ($value == '*') {
            $type[$key] = ['type', 'like', '%_%'];
        }
        else{
            $type[$key] = ['type', 'like', '%' . $value . '%'];
        }
    }
    foreach ($activity as $key => $value) {
        if ($value == '*') {
            $activity[$key] = ['type', 'like', '%_%'];
        }
        else{
            $activity[$key] = ['type', 'like', '%' . $value . '%'];
        }
    }

    $logs = $logs->where($activity)->where($type)->limit($order)->get();

    if ($logs->isEmpty()) {
        return response()->json(['error' => 'Logs not found for user'], 404);
    }
    return response()->json(['logs' => $logs, 'count' => $logs->count()], 200);
}
}
