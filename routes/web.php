<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [PasswordController::class, 'showForm'])->name('password.form');
    Route::post('/generate', [PasswordController::class, 'generate'])->name('password.generate');
    Route::get('/history', [PasswordController::class, 'showHistory'])->name('password.history');
    Route::delete('/history/{id}', [PasswordController::class, 'delete'])->name('password.delete');
});
