<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\LoaiChungTuController;
use App\Http\Controllers\MauChecklistController;
use App\Http\Controllers\LoHangController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('khach-hang', KhachHangController::class)
    ->parameters(['khach-hang' => 'khachHang'])
    ->except('show');
        Route::middleware('admin')->group(function () {
        Route::resource('loai-chung-tu', LoaiChungTuController::class)
            ->parameters(['loai-chung-tu' => 'loaiChungTu'])
            ->except(['show', 'destroy']);
            Route::resource('mau-checklist', MauChecklistController::class)
    ->parameters(['mau-checklist' => 'mauChecklist'])
    ->except(['show', 'destroy']);
    });
    Route::resource('lo-hang', LoHangController::class)
    ->parameters(['lo-hang' => 'loHang']);
});

require __DIR__.'/auth.php';
