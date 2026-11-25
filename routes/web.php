<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;

Route::get('/', [AuthController::class, 'register'])->name('login');
Route::post('/register', [AuthController::class, 'RegisterForm'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/dashboard', [TransactionController::class, 'TransactionIndex'])->name('dashboard');
    Route::view('/categories', 'dashboard.categories')->name('categories');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/month/{month}', [TransactionController::class, 'month']);
    // Route::get('/search', [TransactionController::class, 'search'])->name('transactions.search');
});
