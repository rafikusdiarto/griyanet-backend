<?php

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

Route::prefix('/v1')->group(function () {

    Route::prefix('/customers')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\CustomerController::class, 'index']);
        Route::post('/',[\App\Http\Controllers\Api\CustomerController::class, 'store']);
        Route::post('/{id}', [\App\Http\Controllers\Api\CustomerController::class, 'update']);
        Route::delete('{id}', [\App\Http\Controllers\Api\CustomerController::class, 'destroy']);
    });

    Route::prefix('/sales-packages')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\SalesPackageController::class, 'index']);
        Route::post('/',[\App\Http\Controllers\Api\SalesPackageController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\SalesPackageController::class, 'update']);
        Route::delete('{id}', [\App\Http\Controllers\Api\SalesPackageController::class, 'destroy']);
    });
});

include __DIR__ . '/auth.php';
