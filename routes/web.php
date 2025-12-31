<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;



// Route::get('/', [UserController::class, 'index']);
// Load the user form
Route::get('/user-form', [UserController::class, 'index'])->name('user.form');
Route::post('/submit-form', [UserController::class, 'store']);

Route::get('/', function () {
    return view('welcome');
});
