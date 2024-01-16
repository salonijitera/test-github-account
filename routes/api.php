<?php

use App\Http\Controllers\Api\HealthCheckController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
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

// Health Check Route
Route::any('/health-check', [HealthCheckController::class, 'index'])->name('health-check');

// User Registration Route
Route::post('/users/register', [UserController::class, 'register'])
    ->name('users.register')->middleware('guest');

// Email Verification Route
Route::post('/users/verify-email', [AuthController::class, 'verifyEmail'])
    ->name('users.verify-email');

// Shop Update Route
Route::put('/users/{id}/shop', [ShopController::class, 'update'])->middleware('auth:api')->name('shop.update');
