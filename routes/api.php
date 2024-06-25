<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TreatmentController;
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

Route::prefix('treatments')->group(function () {
    Route::post('', [TreatmentController::class, 'store']);
    Route::get('{treatment:uuid}', [TreatmentController::class, 'show']);
    Route::get('{treatment:uuid}/notifications', [TreatmentController::class, 'getNotifications']);
    Route::post('start', [TreatmentController::class, 'start']);
    Route::post('{notification:uuid}/respond', [TreatmentController::class, 'storeResponse']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('patient', [PatientController::class, 'store']);
