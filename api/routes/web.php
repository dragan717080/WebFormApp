<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AccountController,
    AuthController,
    DealController
};

Route::prefix('accounts')->group(function() {
    Route::get('/', [AccountController::class, 'getAll'])->middleware(['client', 'isZohoAccessTokenValid']);
    Route::post('/', [AccountController::class, 'create'])->middleware(['client', 'validatePhoneNumber', 'validateWebsite']);
});

Route::prefix('deals')->group(function() {
    Route::get('/', [DealController::class, 'getAll'])->middleware(['client', 'isZohoAccessTokenValid']);
    Route::post('/', [DealController::class, 'create'])->middleware(['client', 'isZohoAccessTokenValid']);
});

Route::prefix('auth')->group(function() {
    Route::get('/', [AuthController::class, 'getAuthorizationCode']);
    Route::get('/access-token', [AuthController::class, 'getAccessToken']);
    Route::post('/access-token', [AuthController::class, 'storeAccessToken']);
});

Route::get('/', function () {
    return view('welcome');
});
