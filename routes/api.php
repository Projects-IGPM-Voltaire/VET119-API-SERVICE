<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HealthCenterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\EmailVerificationController;

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

Route::prefix('reference')->group(function () {
    Route::get('/barangay', [ReferenceController::class, 'getBarangays']);
});
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::prefix('/health-center')->group(function () {
    Route::middleware(['auth:api'])->post('/member/{id}', [
        HealthCenterController::class,
        'addMember',
    ]);
    Route::middleware(['auth:api'])->post('/', [
        HealthCenterController::class,
        'store',
    ]);
    Route::get('/', [HealthCenterController::class, 'index']);
    Route::get('/{id}', [HealthCenterController::class, 'show']);
    Route::middleware(['auth:api'])->put('/{id}', [
        HealthCenterController::class,
        'update',
    ]);
    Route::middleware(['auth:api'])->delete('/{id}', [
        HealthCenterController::class,
        'destroy',
    ]);
    Route::prefix('operation-hour')->group(function () {
        Route::get('/{id}', [
            HealthCenterController::class,
            'getOperationHours',
        ]);
        Route::put('/{id}', [
            HealthCenterController::class,
            'updateOperationHours',
        ]);
    });
});
Route::middleware(['auth:api'])
    ->prefix('user')
    ->group(function () {
        Route::post('/', [UserController::class, 'store']);
        Route::post('/reset-password/{id}', [
            UserController::class,
            'resetPassword',
        ]);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
Route::middleware(['auth:api'])
    ->prefix('schedule')
    ->group(function () {
        Route::post('/', [ScheduleController::class, 'store']);
        Route::get('/check', [ScheduleController::class, 'hasSchedule']);
        Route::get('/reference-number/{referenceNumber}', [
            ScheduleController::class,
            'getByReferenceNumber',
        ]);
        Route::get('/{id}', [ScheduleController::class, 'show']);
        Route::get('/', [ScheduleController::class, 'index']);
        Route::put('/{id}', [ScheduleController::class, 'update']);
        Route::delete('/{id}', [ScheduleController::class, 'destroy']);
    });

Route::middleware(['auth:api'])
    ->prefix('pet')
    ->group(function () {
        Route::post('/', [PetController::class, 'store']);
    });

Route::middleware(['auth:api'])
    ->prefix('appointment')
    ->group(function () {
        Route::post('/', [AppointmentController::class, 'store']);
        Route::get('/', [AppointmentController::class, 'index']);
        Route::delete('/delete', [AppointmentController::class, 'delete']);
        Route::get('/check/{condition}', [AppointmentController::class, 'check']);
        Route::get('/filter', [AppointmentController::class, 'filter']);
        Route::get('/{id}', [AppointmentController::class, 'show']);
    });

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
