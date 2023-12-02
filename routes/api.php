<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HealthCenterController;
use App\Http\Controllers\UserController;

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

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::middleware(['auth:api'])
    ->prefix('/health-center')
    ->group(function () {
        Route::post('/member/{id}', [
            HealthCenterController::class,
            'addMember',
        ]);
        Route::post('/', [HealthCenterController::class, 'store']);
        Route::get('/', [HealthCenterController::class, 'index']);
        Route::get('/{id}', [HealthCenterController::class, 'show']);
        Route::put('/{id}', [HealthCenterController::class, 'update']);
        Route::delete('/{id}', [HealthCenterController::class, 'destroy']);
    });
Route::middleware(['auth:api'])
    ->prefix('/user')
    ->group(function () {
        Route::post('/', [UserController::class, 'store']);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
