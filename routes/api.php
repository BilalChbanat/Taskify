<?php
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'V1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => ['auth:sanctum']], function () {
    Route::get('tasks', [TaskController::class, 'index']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::put('tasks/{id}', [TaskController::class, 'update']); // Use 'put' instead of 'edit'
    Route::delete('tasks/{id}', [TaskController::class, 'destroy']); // Use 'delete' instead of 'delete'
});


Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');
