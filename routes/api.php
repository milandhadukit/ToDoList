<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskComplateController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'api',
    // 'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);   
    
    Route::post('/addtask', [TaskController::class, 'addTask']);
    Route::delete('/delete-task/{id}', [TaskController::class, 'deleteTask']);
    Route::post('/update-task/{id}', [TaskController::class, 'updateTask']);
    Route::post('/search-task-admin', [TaskController::class, 'searchToDoListAdmin']); //admin
    Route::post('/task-list', [TaskController::class, 'viewTask']);
   

    Route::post('/submit-task', [TaskComplateController::class, 'complateTask']);
    Route::post('/search-task', [TaskComplateController::class, 'searchTaskDate']);
    Route::post('/search-todo', [TaskComplateController::class, 'searchToDo']); // total todo list search 
    Route::post('/update-complated/{id}', [TaskComplateController::class, 'updateCompalateTask']);

});