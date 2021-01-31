<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\VerificationController;
use App\Http\Controllers\API\Auth\PhoneVerificationController;
use App\Http\Controllers\API\TransactionController;

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

Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::group(['as' => 'api.'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('/login', [LoginController::class, 'login'])->name('login');
        Route::post('/register', [RegisterController::class, 'register'])->name('register');

        Route::group(['middleware' => 'auth:sanctum', 'as' => 'phone.'], function () {
            Route::get('/phone/verify', [PhoneVerificationController::class, 'getOTP'])->name('request-otp');
            Route::post('/phone/verify', [PhoneVerificationController::class, 'verifyOTP'])->name('verify-otp');
        });
    });

    Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/details', [UserController::class, 'details'])->name('details');

        Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
            Route::get('/', [WalletController::class, 'getWalletdetails'])->name('details');
            Route::post('/transactions', [TransactionController::class, 'storeTransaction'])->name('store-transaction');
        });
    });

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
});
