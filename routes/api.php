<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VersionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CUXUIController;

/**********
 *  GET *
 **********/

Route::get('/up', function () {
    return response()->json(['response'=>true]);
});

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
Route::post('themes', [ThemeController::class, "store"]);
Route::get('/themes', [ThemeController::class, "index"]);
Route::get('/themes/{id}', [ThemeController::class, "show"]);

/* NEWS */
Route::get('/news', [NewsController::class, "index"]);
Route::get('/newsPinned', [NewsController::class, "showPinnedNews"]);

/* OTHER */
Route::get('/messages/{forum_id}', [MessageController::class, "index"]);
Route::get('/roles', [RoleController::class, "index"]);
Route::get('/forums/{theme_id}', [ForumController::class, "index"]);


/********
 * POST *
 ********/
/* USERS */
Route::post('/AvatarUpload', [UserController::class, "uploadAvatar"]);
Route::post('/BannerUpload', [UserController::class, "uploadBanner"]);
Route::post('/users/create', [UserController::class, "store"]);
Route::post('/users/login', [UserController::class, "login"]);
Route::post('/admin/check', [UserController::class, 'AdminCheck'])->middleware('auth:sanctum');
Route::post('/news', [NewsController::class, "store"])->middleware('auth:sanctum');

/* OTHER */
Route::post('/messages', [MessageController::class, "store"]);
Route::post('/forums/create', [ForumController::class, "store"]);
Route::post('/report/create', [ReportController::class, "store"]);
Route::get('/report/show/{link}', [ReportController::class, "show"]);
Route::get('/reports/user', [ReportController::class, "showUser"]);
Route::post('/report/send', [ReportController::class, "send"]);
Route::post('CF_CUXUI', [CUXUIController::class, "index"]);

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
Route::post('/activitycount/{dates}', [LogController::class, "activitiesCount"]);
Route::get('/statistic/messages', [MessageController::class, "statisticShow"]);
Route::get('/statistic/users', [UserController::class, "statisticShow"]);
Route::get('/logs/{id}', [LogController::class, "show"]);
Route::post('/CFCUXUI/stats', [CUXUIController::class, "stats"]);
Route::get('/version/{version}', [VersionController::class, "show"]);
Route::post('/access', [AccessController::class, "store"]);
Route::post('/page-time', [AccessController::class, 'pageTime']);

Route::post('/isAdmin', [UserController::class, 'AdminCheck'])->middleware('auth:sanctum');
