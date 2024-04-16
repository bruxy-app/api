<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TreatmentController;
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

// Route::middleware(['auth:sanctum'])->group(function () {
Route::prefix('treatments')->group(function () {
    Route::get('', [TreatmentController::class, 'index']);
    ROute::get('{treatment:uuid}', [TreatmentController::class, 'show']);
    Route::get('{treatment:uuid}/notifications', [TreatmentController::class, 'getNotifications']);
    Route::post('start', [TreatmentController::class, 'start']);
});
// });

Route::post('login', [AuthController::class, 'login']);
