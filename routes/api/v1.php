<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\OTPController;
use App\Http\Controllers\Api\v1\UserController;
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


Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::post('verify', [OTPController::class, 'verify_otp'])->name('otp_verification');
    Route::post('resend-otp', [OTPController::class, 'resend_otp'])->name('resend_otp');

});


Route::group(['prefix' => 'users', 'middleware' => ['auth:api']], function () {
    Route::patch('', [UserController::class, 'update'])->name('update');
});