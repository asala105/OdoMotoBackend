<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => ['admin']], function () {
        Route::get('user_profile', [AuthController::class, 'userProfile']);
    });
});
