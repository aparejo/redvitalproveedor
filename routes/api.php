<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Store\StoreController;
use App\Http\Controllers\Api\Provider\ProviderController;
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
Route::get('/category', [CategoryController::class, 'index']);
Route::post('/category', [CategoryController::class, 'store']);
Route::get('/category/{category}', [CategoryController::class, 'show']);
Route::post('/category/{category}', [CategoryController::class, 'update']);
Route::delete('/category/{category}', [CategoryController::class, 'destroy']);

// Providers
Route::get('/provider', [ProviderController::class, 'index']);
Route::post('/provider', [ProviderController::class, 'store']);
Route::get('/provider/{provider}', [ProviderController::class, 'show']);
Route::post('/provider/{provider}', [ProviderController::class, 'update']);
Route::delete('/provider/{provider}', [ProviderController::class, 'destroy']);
//End Providers

// store
Route::get('/store', [StoreController::class, 'index']);
Route::post('/store', [StoreController::class, 'store']);
Route::get('/store/{store}', [StoreController::class, 'show']);
Route::post('/store/{store}', [StoreController::class, 'update']);
Route::delete('/store/{store}', [StoreController::class, 'destroy']);
// end store

Route::post('login', [AuthController::class, 'login']);
Route::post('signup', [AuthController::class, 'signUp']);
// mover hacia el login
// category

// end category
Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::prefix('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
