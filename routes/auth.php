<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
});
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->name('register');
