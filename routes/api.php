<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\client\AddressController;
use App\Http\Controllers\admin\VoucherController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
        Route::post('/{id}', [CategoryController::class, 'update']);
        Route::get('/{id}', [CategoryController::class, 'show']);
    });
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
    Route::prefix('address')->group(function () {
        Route::get('/', [AddressController::class, 'getAddressByUserId']);
        Route::post('/', [AddressController::class, 'store']);
        Route::delete('/{id}', [AddressController::class, 'destroy']);
    });
    Route::get('/vouchers', [App\Http\Controllers\client\ViewVoucher::class, 'index']);
    Route::prefix('admin')->group(function () {
        Route::prefix('vouchers')->group(function () {
            Route::get('/', [VoucherController::class, 'index']);
            Route::post('/', [VoucherController::class, 'store']);
            Route::post('/{id}', [VoucherController::class, 'update']);
            Route::delete('/{id}', [VoucherController::class, 'destroy']);
        });
    });
});
