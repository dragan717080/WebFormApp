<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AccountController,
    DealController
};

// To do: make Accounts child route of Deals/{id} since it's dependant on it like 'Deals/{id}/Accounts'
Route::prefix('accounts')->group(function() {
    Route::get('/', [AccountController::class, 'getAll']);
    Route::get('/{id}', [AccountController::class, 'getById']);
    Route::post('/', [AccountController::class, 'create']);
    Route::patch('/{id}', [AccountController::class, 'update']);
    Route::delete('/{id}', [AccountController::class, 'delete']);
});

Route::prefix('deals')->group(function() {
    Route::get('/', [DealController::class, 'getAll']);
    Route::get('/{id}', [DealController::class, 'getById']);
    Route::post('/', [DealController::class, 'create']);
    Route::post('/sign-out', [DealController::class, 'signOut']);
    Route::patch('/{id}', [DealController::class, 'update']);
    Route::delete('/{id}', [DealController::class, 'delete']);
});

Route::get('/', function () {
    return view('welcome');
});
