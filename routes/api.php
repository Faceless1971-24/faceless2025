<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::post('signin', [AuthController::class, 'signin']);
Route::post('user/{user_id}/update-token', [UserController::class, 'updateToken']);

Route::get('user-phonebook', [UserController::class, 'user_phone_book']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'show']);
    Route::get('user-dashboard', [UserController::class, 'user_data']);

    Route::post('notify', [UserController::class, 'notify']);

    Route::post('signout', [AuthController::class, 'signout']);
});
