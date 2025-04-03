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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'viewAllUsers'])->name('admin.users.viewAll');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUserProfile'])->name('admin.users.edit');
    Route::put('/admin/users/{id}/update', [AdminController::class, 'updateUserProfile'])->name('admin.users.update');
    Route::delete('/admin/users/{id}/delete', [AdminController::class, 'deleteUserAccount'])->name('admin.users.delete');
});
use App\Http\Controllers\KnifeController;
Route::get('/knives2', [KnifeController::class, 'index2'])->name('knives.index2');
Route::get('/knives', [KnifeController::class, 'index'])->name('knives.index'); // View all knives

Route::middleware(['auth'])->group(function () {
    Route::get('/knives', [KnifeController::class, 'index'])->name('knives.index');
    Route::post('/knives', [KnifeController::class, 'store'])->name('knives.store');
    Route::put('/knives/{knife}', [KnifeController::class, 'update'])->name('knives.update');
    Route::delete('/knives/{id}', [KnifeController::class, 'destroy'])->name('knives.delete');
});
use App\Http\Controllers\CartController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/remove', [CartController::class, 'removeFromCart']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
});
use App\Http\Controllers\PaymentController;

Route::post('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

require __DIR__.'/auth.php';
