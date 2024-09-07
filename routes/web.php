<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;

Route::get('/', [PasswordController::class, 'showForm'])->name('password.form');
Route::post('/generate', [PasswordController::class, 'generate'])->name('password.generate');
Route::get('/history', [PasswordController::class, 'showHistory'])->name('password.history');
Route::delete('/history/{index}', [PasswordController::class, 'delete'])->name('password.delete');
