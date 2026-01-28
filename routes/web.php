<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountsManager;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Keep Auth routes separate
Route::get('/register', function () {
    return Inertia::render('Auth/Register');
});

Route::middleware('auth')->group(function () {
    // --- Account Management Routes ---
    
    // 1. Create/Add Routes
    Route::get('/accounts/create', [AccountsManager::class, 'create'])->name('accounts.create');
    Route::post('/accounts', [AccountsManager::class, 'store'])->name('accounts.store');

    // 2. Edit/Update Routes (Notice the {account} parameter)
    Route::get('/accounts/{account}/edit', [AccountsManager::class, 'edit'])->name('accounts.edit');
    Route::patch('/accounts/{account}', [AccountsManager::class, 'update'])->name('accounts.update');

    // --- Profile Routes ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';