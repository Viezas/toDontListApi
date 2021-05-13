<?php

use App\Http\Controllers\AuthController;
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

//Auth routes
Route::post('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('auth/signin', [AuthController::class, 'signin'])->name('signin');
Route::middleware('auth:sanctum')->post('auth/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth:sanctum')->post('auth/me', [AuthController::class, 'me'])->name('me');