<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin
Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::post('login', 'login');
    Route::middleware('check.auth:admin_api')->group(function () {
        Route::get('logout', 'logout');
        Route::get('profile', 'profile');
        Route::post('update', 'updateProfile');
    });
});

// User
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('forgot-pw-sendcode', 'forgotSend');
    // Route::post('forgot-update', 'forgotUpdate');
    Route::middleware('check.auth:user_api')->group(function () {
        Route::get('logout', 'logout');
        Route::get('profile', 'profile');
        Route::post('update', 'updateProfile');
    });
});

// Category
Route::prefix('category')->controller(CategoryController::class)->group(function () {
    Route::middleware(['check.auth:user_api,admin_api'])->group(function () {
        Route::post('/add', 'addCategory');
    });

    Route::middleware(['check.auth:user_api', 'role:hospital,doctor'])->group(function () {
    // Route::middleware(['check.auth:user_api,admin_api', 'role:hospital,doctor,admin'])->group(function () {
        Route::get('/all', 'getAll');
    });
});



// Route::middleware(['check.auth:user_api, admin_api', 'role:hospital,doctor'])->group(function () {
