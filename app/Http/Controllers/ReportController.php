<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportResource;
use App\Models\report_messages;
use App\Models\reports;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function send(Request $request){
        report_messages::create(['report_id'=>$request->report_id, 'text'=>$request->text, 'user_id'=>$request->user_id]);

        return response()->json(["message"=>"Message sent"]);
    }

    public function show($link){

        return ReportResource::make(reports::firstWhere('link', '=', $link));

    }
    public function showUser()
    {
        try {
            $user_id = auth()->guard('sanctum')->user()->id;
        } catch (\Exception $e) {
            $user_id = null;
        }

        $reports = reports::where('user_id', $user_id);
        return response()->json(['data'=>$reports]);
    }

    public function store(Request $request)
    {
        try {
            $user_id = auth()->guard('sanctum')->user()->id;
        } catch (\Exception $e) {
            $user_id = null;
        }

        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
            $charactersLength = strlen($characters);
            $randomString = '';

            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }

            return $randomString;
        }

        $link = generateRandomString(11);
        $unique = false;

        while (!$unique) {
            $testLinkUnique = reports::where('link', $link)->first();
            if (!$testLinkUnique) {
                $unique = true;
            } else {
                $link = generateRandomString(11);
            }
        }

        reports::create([
            'user_id' => $user_id,
            'admin_id'=> null,
            'theme' => $request->theme,
            'text' => $request->text,
            'status' => 'opened',
            'link' => $link,
        ]);

        return response()->json(['link'=>$link]);
    }
}
