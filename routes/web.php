<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/websocket-test', function () {
    return view('websocket-test');
});

Route::get('/websocket-test', function () {
    return view('websocket-test');
});
