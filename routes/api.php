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

    Route::group(['prefix' => 'customers', 'middleware' => ['role:sales', 'auth:sanctum']], function () {
        Route::get('/', [\App\Http\Controllers\Api\CustomerController::class, 'index']);
        Route::post('/',[\App\Http\Controllers\Api\CustomerController::class, 'store']);
        Route::post('/{id}', [\App\Http\Controllers\Api\CustomerController::class, 'update']);
        Route::delete('{id}', [\App\Http\Controllers\Api\CustomerController::class, 'destroy']);
    });

    Route::group(['prefix' => 'packages', 'middleware' => ['role:admin', 'auth:sanctum']], function () {
        Route::get('/', [\App\Http\Controllers\Api\SalesPackageController::class, 'index']);
        Route::post('/',[\App\Http\Controllers\Api\SalesPackageController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\SalesPackageController::class, 'update']);
        Route::delete('{id}', [\App\Http\Controllers\Api\SalesPackageController::class, 'destroy']);
        Route::get('/customers', [\App\Http\Controllers\Api\SalesPackageController::class, 'getCustomer']);
        Route::post('/customers/status/{id}', [\App\Http\Controllers\Api\SalesPackageController::class, 'updateStatus']);
    });
});

include __DIR__ . '/auth.php';
