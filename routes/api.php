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

use App\Http\Controllers\TaskController;

Route::middleware('auth:api')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->middleware('throttle:100,1'); // 100 طلب في الدقيقة
    Route::post('/tasks', [TaskController::class, 'store'])->middleware('throttle:50,1'); // 50 طلب في الدقيقة
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->middleware('throttle:100,1');
    Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->middleware('throttle:50,1');
    Route::put('/tasks/{id}/reassign', [TaskController::class, 'reassign'])->middleware('throttle:50,1');
    Route::post('/tasks/{id}/comments', [TaskController::class, 'addComment'])->middleware('throttle:50,1');
    Route::post('/tasks/{id}/attachments', [TaskController::class, 'addAttachment'])->middleware('throttle:50,1');
    Route::post('/tasks/{id}/assign', [TaskController::class, 'assign'])->middleware('throttle:50,1');
    Route::get('/reports/daily-tasks', [TaskController::class, 'dailyReport'])->middleware('throttle:20,1'); // 20 طلب في الدقيقة
});