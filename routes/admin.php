<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\Admin\ProvidersController;

Route::get('', [HomeController::class, 'index']);
Route::get('proveedores', [ProveedorController::class, 'show']);
Route::post('admin/registro', [ProveedorController::class, 'store']);
Route::resource('providers', ProvidersController::class )->names('admin.providers');