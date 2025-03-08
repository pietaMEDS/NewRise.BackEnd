<?php

namespace App\Http\Controllers;

use App\Models\chatuirating;
use Illuminate\Http\Request;

class CUXUIController extends Controller
{
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

