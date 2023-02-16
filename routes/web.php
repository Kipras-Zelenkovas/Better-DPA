<?php

use App\Http\Controllers\Auth\Authentication;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('login', [Authentication::class, 'login'])->middleware('guest')->name('login');
    Route::post('register', [Authentication::class, 'register'])->middleware('guest')->name('register');
    Route::post('logout', [Authentication::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});
