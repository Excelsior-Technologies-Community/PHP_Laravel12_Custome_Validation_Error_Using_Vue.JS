<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


// 🔐 LOGIN / REGISTER
Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::get('/register', [UserController::class, 'showRegister']);
Route::post('/register', [UserController::class, 'register']);

Route::get('/logout', [UserController::class, 'logout']);

// 🔒 PROTECTED CRUD
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('user-form'); // ✅ your CRUD page
    });

    Route::get('/get-users', [UserController::class, 'getUsers']);
    Route::post('/store', [UserController::class, 'store']);
    Route::post('/update/{id}', [UserController::class, 'update']);
    Route::get('/delete/{id}', [UserController::class, 'destroy']);
    Route::get('/status/{id}', [UserController::class, 'toggleStatus']);
});

// 🚀 DEFAULT → LOGIN PAGE
Route::get('/', function () {
    return redirect('/login');
});