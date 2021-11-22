<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
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
Route::prefix('/patient')->group(function () {
    Route::post('/signup', [LoginController::class, 'store']);
    Route::post('/signin', [LoginController::class, 'signin']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::prefix('/patient')->group(function () {
        Route::post('/loantype/add', [PatientController::class, 'addLoantype']);
        Route::get('/loantype/view', [PatientController::class, 'viewLoantype']);
    });
});

// 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
 