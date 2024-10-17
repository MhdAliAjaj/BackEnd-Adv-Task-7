<?php

use App\Http\Controllers\AuthController;
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


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});


Route::middleware('auth:api')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->middleware('permission:view-tasks','throttle:100,1'); // 100 طلب في الدقيقة
    Route::post('/tasks', [TaskController::class, 'store'])->middleware('permission:create-tasks','throttle:50,1'); // 50 طلب في الدقيقة
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->middleware('permission:view-tasks','throttle:100,1');
    Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->middleware('permission:update-tasks','throttle:50,1');
    Route::put('/tasks/{id}/reassign', [TaskController::class, 'reassign'])->middleware('permission:reassign-tasks','throttle:50,1');
    Route::post('/tasks/{id}/comments', [TaskController::class, 'addComment'])->middleware('permission:add-comments','throttle:50,1');
    Route::post('/tasks/{id}/attachments', [TaskController::class, 'addAttachment'])->middleware('permission:add-attachments','throttle:50,1');
    Route::post('/tasks/{id}/assign', [TaskController::class, 'assign'])->middleware('permission:assign-tasks','throttle:50,1');
    Route::get('/reports/daily-tasks', [TaskController::class, 'dailyReport'])->middleware('permission:view-reports','throttle:20,1'); // 20 طلب في الدقيقة
});

