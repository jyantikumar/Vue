<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountsManager;
use App\Http\Controllers\EntitiesManager;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/register', function () {
    return Inertia::render('Auth/Register');
});

Route::middleware('auth')->group(function () {

    Route::get('/entities/create', [EntitiesManager::class, 'create'])->name('entities.create');
    Route::post('/entities', [EntitiesManager::class, 'store'])->name('entities.store');

    Route::get('/accounts/create', [AccountsManager::class, 'create'])->name('accounts.create');
    Route::post('/accounts', [AccountsManager::class, 'store'])->name('accounts.store');

    Route::get('/accounts/{account}/edit', [AccountsManager::class, 'edit'])->name('accounts.edit');
    Route::patch('/accounts/{account}', [AccountsManager::class, 'update'])->name('accounts.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';