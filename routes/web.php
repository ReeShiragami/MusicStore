<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman awal: arahkan ke dashboard (yang akan memilah admin/user)
Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {

    // Setelah login, Breeze mengarah ke sini. Admin ke dashboard admin, user ke katalog.
    Route::get('/dashboard', fn () => auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('catalog'))->name('dashboard');

    // ---------- SESSION USER ----------
    Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog');
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorit/{song}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::post('/beli/{song}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/pembayaran/{order}', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/pembayaran/{order}', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::get('/pembelian-saya', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/koleksi', [\App\Http\Controllers\LibraryController::class, 'index'])->name('library');

    // Bawaan Breeze (edit profil)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---------- SESSION ADMIN ---------- (wajib login + role admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/orders/{order}/batal', [Admin\DashboardController::class, 'cancel'])->name('orders.cancel');

    Route::resource('songs', Admin\SongController::class)->except('show');
    Route::resource('genres', Admin\GenreController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('albums', Admin\AlbumController::class)->only(['index', 'store', 'update', 'destroy']);
});

require __DIR__ . '/auth.php';