<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\VaultController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/', [VaultController::class, 'index'])->name('vault.index');
    Route::get('/vault/create', [VaultController::class, 'create'])->name('vault.create');
    Route::post('/vault/preview', [VaultController::class, 'preview'])->name('vault.preview');
    Route::post('/vault', [VaultController::class, 'store'])->name('vault.store');
    Route::get('/vault/{entry}', [VaultController::class, 'show'])->name('vault.show');
    Route::get('/vault/{entry}/edit', [VaultController::class, 'edit'])->name('vault.edit');
    Route::put('/vault/{entry}', [VaultController::class, 'update'])->name('vault.update');
    Route::delete('/vault/{entry}', [VaultController::class, 'destroy'])->name('vault.destroy');

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::post('/vault/{entry}/share', [ShareController::class, 'store'])->name('share.store');
    Route::delete('/vault/{entry}/share/{linkId}', [ShareController::class, 'destroy'])->name('share.destroy');

    Route::get('/twofactor/enable', [TwoFactorController::class, 'enable'])->name('twofactor.enable');
    Route::post('/twofactor/confirm', [TwoFactorController::class, 'confirm'])->name('twofactor.confirm');
    Route::post('/twofactor/disable', [TwoFactorController::class, 'disable'])->name('twofactor.disable');
});

Route::get('/twofactor/challenge', [TwoFactorController::class, 'challenge'])->name('twofactor.challenge');
Route::post('/twofactor/challenge', [TwoFactorController::class, 'verifyChallenge'])->name('twofactor.verify');

Route::get('/s/{token}', [ShareController::class, 'show'])->name('share.show');
Route::post('/s/{token}', [ShareController::class, 'reveal'])->name('share.reveal');