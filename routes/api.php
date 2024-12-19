<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{userId}', [UserController::class, "show"]);

Route::get('/users/profile', [UserController::class, "profile"]);
Route::get('/messages/{forum_id}', [MessageController::class, "index"]);
Route::post('/messages', [MessageController::class, "store"]);
Route::get('/themes', [ThemeController::class, "index"]);
Route::get('/roles', [RoleController::class, "index"]);
Route::get('/themes/{id}', [ThemeController::class, "show"]);
Route::get('/forums/{theme_id}', [ForumController::class, "index"]);
Route::post('/forums/create', [ForumController::class, "store"]);
Route::post('/users/create', [UserController::class, "store"]);
Route::put('/users/update', [UserController::class, "update"])->middleware('auth:sanctum');
Route::put('/users/update/{id}', [UserController::class, "updateSelected"])->middleware('auth:sanctum');
Route::post('/users/login', [UserController::class, "login"]);
Route::delete('/messages/{id}', [MessageController::class, "destroy"]);
Route::delete('/users/{id}', [UserController::class, "destroy"]);
Route::put('/messages/{id}/edit', [MessageController::class, "update"])->middleware('auth:sanctum');
Route::post('/admin/check', [UserController::class, 'AdminCheck'])->middleware('auth:sanctum');
// Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

Route::get('/test-log', function () {
    Log::info('Test log entry');
    return 'Log entry created!';
});
