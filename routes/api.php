<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth', [ \App\Http\Controllers\UserController::class, 'auth' ]);
Route::post('/reg', [ \App\Http\Controllers\UserController::class, 'reg' ]);
Route::post('user/requests', [\App\Http\Controllers\UserController::class, 'getRequests'])->middleware(\App\Http\Middleware\Auth::class);

Route::post('request/create', [ \App\Http\Controllers\RequestController::class, 'create' ])->middleware(\App\Http\Middleware\Auth::class);
Route::post('request/remove', [ \App\Http\Controllers\RequestController::class, 'remove' ])->middleware(\App\Http\Middleware\Auth::class);
Route::post('request/update', [ \App\Http\Controllers\RequestController::class, 'updateStatus' ])->middleware(\App\Http\Middleware\Auth::class);
Route::post('requests', [\App\Http\Controllers\RequestController::class, 'getAll']);
Route::post('request', [\App\Http\Controllers\RequestController::class, 'getOne']);
Route::post('request/by/category', [\App\Http\Controllers\RequestController::class, 'getByCategory']);
Route::post('request/last', [\App\Http\Controllers\RequestController::class, 'getLastDone']);
Route::post('request/counter', [\App\Http\Controllers\RequestController::class, 'getCounter']);

Route::post('category/create', [ \App\Http\Controllers\CategoryController::class, 'create' ])->middleware(\App\Http\Middleware\Auth::class);
Route::post('category/remove', [ \App\Http\Controllers\CategoryController::class, 'remove' ])->middleware(\App\Http\Middleware\Auth::class);
Route::post('categories', [ \App\Http\Controllers\CategoryController::class, 'getAll' ]);
