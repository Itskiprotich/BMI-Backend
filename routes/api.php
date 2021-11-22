<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
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
Route::prefix('/user')->group(function () {
    Route::post('/signup', [LoginController::class, 'store']);
    Route::post('/signin', [LoginController::class, 'signin']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::prefix('/patients')->group(function () {
        Route::post('/register', [PatientController::class, 'store']);
        Route::get('/view', [PatientController::class, 'index']);
    });

    Route::prefix('/vital')->group(function () {
        Route::post('/add', [PatientController::class, 'add_vital']);
        
    });
});

// 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
 