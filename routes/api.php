<?php

use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\UserAuthController;
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

Route::group(['prefix' => 'V1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'auth:api'], function () {
    Route::get('tasks', [TaskController::class, 'index']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::get('tasks/{id}/edit', [TaskController::class, 'edit']);
    Route::put('tasks/{id}/edit', [TaskController::class, 'update']);
    Route::delete('tasks/{id}/delete', [TaskController::class, 'destroy']);
});

// laravel auth

Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])
  ->middleware('auth:sanctum');

