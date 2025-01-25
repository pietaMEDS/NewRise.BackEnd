<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**********
 *  GET *
 **********/
/* USERS */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/* USERS */
Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{userId}', [UserController::class, "show"]);
Route::get('/users/profile', [UserController::class, "profile"]);
Route::get('/users/profile/{userId}', [UserController::class, "profile"]);

/* THEMES */
Route::get('/themes', [ThemeController::class, "index"]);
Route::get('/themes/{id}', [ThemeController::class, "show"]);

/* OTHER */
Route::get('/messages/{forum_id}', [MessageController::class, "index"]);
Route::get('/roles', [RoleController::class, "index"]);
Route::get('/forums/{theme_id}', [ForumController::class, "index"]);


/********
 * POST *
 ********/
/* USERS */
Route::post('/AvatarUpload', [UserController::class, "uploadAvatar"]);
Route::post('/users/create', [UserController::class, "store"]);
Route::post('/users/login', [UserController::class, "login"]);
Route::post('/admin/check', [UserController::class, 'AdminCheck'])->middleware('auth:sanctum');
/* OTHER */
Route::post('/messages', [MessageController::class, "store"]);
Route::post('/forums/create', [ForumController::class, "store"]);

/* WEBSOCKET TEST */
Route::post('/broadcast-test', function (Request $request) {
    event(new \App\Events\MyEvent($request->message));
    return response()->json(['status' => 'Message sent']);
});

/*******
 * PUT *
 *******/
Route::put('/users/update', [UserController::class, "update"])->middleware('auth:sanctum');
Route::put('/users/update/{id}', [UserController::class, "updateSelected"])->middleware('auth:sanctum');
Route::put('/messages/{id}/edit', [MessageController::class, "update"])->middleware('auth:sanctum');


/**********
 * DELETE *
 **********/
Route::delete('/messages/{id}', [MessageController::class, "destroy"]);
Route::delete('/users/{id}', [UserController::class, "destroy"]);


/*************
 * STATISTIC *
 *************/
Route::get('/statistic/messages', [MessageController::class, "statisticShow"]);
Route::get('/statistic/users', [UserController::class, "statisticShow"]);
Route::get('/logs/{id}', [LogController::class, "show"]);
