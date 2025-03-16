<?php

namespace App\Http\Controllers;

use App\Models\chatuirating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CUXUIController extends Controller
{
    public function stats(){
        $users = DB::select('SELECT DISTINCT email FROM chatuiratings');

        $designes = DB::select('SELECT DISTINCT name FROM chatuiratings');

        $raiting_data = [];

        foreach ($users as $user){

            $innerData = (object) array('email' => $user->email);
            foreach ($designes as $design){
                array_push($raiting_data, chatuirating::where([['email', $user->email], ['name', $design->name]])->orderBy('created_at', 'desc')->first()->rate);
            };

        }

        $data = [];
        array_push($data, $users, $designes, $raiting_data);

        return $data;
    }
    public function index(Request $request)
    {
        $data = json_decode($request->getContent(), true); // Декодируем JSON вручную

        if (!$data) {
            return response()->json(['error' => 'Invalid request data'], 400);
        }

        // Проверяем, что все нужные данные есть
        if (!isset($data['email'], $data['rate'], $data['name'])) {
            return response()->json(['error' => 'Missing fields'], 422);
        }

        // Создаём запись в базе
        $record = chatuirating::create([
            'email' => $data['email'],
            'rate' => $data['rate'],
            'name' => $data['name'],
        ]);

        $rateStatus = chatuirating::where('name', $request->name)->get();

        return response()->json(['message' => 'Оценка сохранена', 'records' => $rateStatus]);
    }

}

