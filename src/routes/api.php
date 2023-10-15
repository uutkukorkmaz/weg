<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// app health check
Route::get('health',function(){
    return [
        'db' => DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME),
        'redis' => \Illuminate\Support\Facades\Redis::connection()->ping(),
    ];
});
