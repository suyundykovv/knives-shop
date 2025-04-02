<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Register'); // Новый Vue-компонент
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/knives', function () {
    return Inertia::render('Admin/KnifePanel');
})->middleware(['auth', 'verified'])->name('knifePanel');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'viewAllUsers'])->name('admin.users.viewAll');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUserProfile'])->name('admin.users.edit');
    Route::post('/admin/users/{id}/update', [AdminController::class, 'updateUserProfile'])->name('admin.users.update');
    Route::delete('/admin/users/{id}/delete', [AdminController::class, 'deleteUserAccount'])->name('admin.users.delete');
});
use App\Http\Controllers\KnifeController;
Route::get('/knives/knifePanel', [KnifeController::class, 'knifePanel'])->name('knives.knifePanel');
// После существующего маршрута knives.index
Route::get('/knives2', [KnifeController::class, 'index2'])->name('knives.index2');
Route::get('/knives', [KnifeController::class, 'index'])->name('knives.index'); // View all knives

Route::middleware(['auth'])->group(function () {
    Route::get('/knives/create', [KnifeController::class, 'create'])->name('knives.create'); // Only admin
    Route::post('/knives', [KnifeController::class, 'store'])->name('knives.store'); // Only admin
    Route::get('/knives/{knife}/edit', [KnifeController::class, 'edit'])->name('knives.edit'); // Only admin
    Route::put('/knives/{knife}', [KnifeController::class, 'update'])->name('knives.update'); // Only admin
    Route::delete('/knives/{knife}', [KnifeController::class, 'destroy'])->name('knives.destroy'); // Only admin
});

require __DIR__.'/auth.php';
