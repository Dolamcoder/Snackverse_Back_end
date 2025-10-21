<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index']);
Route::get('/activate/{token}', [App\Http\Controllers\UserController::class, 'activate']);
Route::get('/reset-password/{token}', [App\Http\Controllers\UserController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('password.update');