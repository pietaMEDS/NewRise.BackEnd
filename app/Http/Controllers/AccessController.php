<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\PageTime;
use App\Models\User;
use App\Models\user_links;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fingerprint_m'   => 'required|string|max:255',
            'fingerprint_c'   => 'required|string|max:255',
            'useragent'       => 'required|string|max:512',
            'path_to'         => 'required|string|max:512',
            'path_from'       => 'nullable|string|max:512',
            'primary_account' => 'nullable|integer',
            'visitor_id'      => 'required|string|max:255',
            'visitor_score'   => 'required',
        ]);

        $access = Access::create($validated);

        if (!$request['primary_account']) {
            $mayAccount_finger_c = user_links::where('fingerprint_c', $request['fingerprint_c'])->first();
            $mayAccount_visitor_id = user_links::where('visitor_id', $request['visitor_id'])->orderBy('updated_at', 'DESC')->first();

            if ($mayAccount_finger_c && $mayAccount_visitor_id) {
                if ($mayAccount_finger_c->user_id == $mayAccount_visitor_id->user_id) {
                    return response()->json(['status'=>'ask_verify', 'user_id' => $mayAccount_finger_c->user_id, 'data' => User::find($mayAccount_finger_c->user_id)]);
                }else {
                    $mayAccount_finger_c->update(['fingerprint_c' => null]);
                    $mayAccount_visitor_id->update(['visitor_id' => null]);
                }
            } elseif ($mayAccount_finger_c) {
//                return response()->json(['status'=>'ask_verify', 'user_id' => $mayAccount_finger_c->user_id]);
                return response()->json(['status'=>'success']);
            }elseif ($mayAccount_visitor_id) {
                return response()->json(['status'=>'ask_verify', 'user_id' => $mayAccount_visitor_id->user_id]);
            }else{
                return response()->json(['status'=>'success']);
            }
        }else{
            $accountLink = user_links::where('user_id', $request['primary_account'])->first();
            if ($accountLink) {
                $isUpdated = false;
                if ($accountLink->fingerprint_c != $request['fingerprint_c']) {
                    $accountLink->update(['fingerprint_c' => $request['fingerprint_c']]);
                    $isUpdated = true;
                }
                if ($accountLink->visitor_id != $request['visitor_id']) {
                    $accountLink->update(['visitor_id' => $request['visitor_id']]);
                    $isUpdated = true;
                }
                if ($isUpdated) {
                    return response()->json(['status'=>'link_updated']);
                }
                return response()->json(['status'=>'success']);
            }else{
                user_links::create(['user_id' => $request['primary_account'], 'fingerprint_c' => $request['fingerprint_c'], 'visitor_id' => $request['visitor_id']]);
                return response()->json(['status'=>'link_created']);
            }
        }

        return response()->json(['status'=>"success"]);
    }

    public function pageTime(Request $request)
    {
        $validated = $request->validate([
            'fingerprint_c' => 'required|string|max:255',
            'useragent'     => 'required|string|max:512',
            'path'          => 'required|string|max:512',
            'time_spent'    => 'required|integer',
        ]);

        PageTime::create($validated);

        return response()->json(['status' => 'success']);
    }
}
