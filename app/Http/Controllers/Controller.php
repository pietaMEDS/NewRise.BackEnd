<?php

namespace App\Http\Controllers;

use App\Models\Logs;

abstract class Controller
{
    public function LogData(int $user_id, string $type, object $data)
    {
        Logs::create([
            'user_id' => $user_id,
            'type' => $type,
            'data' => json_encode($data),
        ]);
    }
}
