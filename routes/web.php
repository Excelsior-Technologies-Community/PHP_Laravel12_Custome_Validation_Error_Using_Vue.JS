<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/login', [UserController::class, 'showAuth'])->name('login');
Route::get('/register', [UserController::class, 'showAuth']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/logout', [UserController::class, 'logout']);
Route::get('/check-email', [UserController::class, 'checkEmail']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('user-form');
    });
    Route::get('/get-users', [UserController::class, 'getUsers']);
    Route::post('/store', [UserController::class, 'store']);
    Route::post('/update/{id}', [UserController::class, 'update']);
    Route::get('/delete/{id}', [UserController::class, 'destroy']);
    Route::get('/status/{id}', [UserController::class, 'toggleStatus']);
});

Route::get('/', function () {
    return redirect('/login');
});