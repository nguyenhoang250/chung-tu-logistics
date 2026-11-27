<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\LoaiChungTuController;
use App\Http\Controllers\MauChecklistController;
use App\Http\Controllers\LoHangController;
use App\Http\Controllers\TepDinhKemController;
use App\Http\Controllers\ChungTuController;
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
    Route::post('chung-tu/{chungTu}/tep', [TepDinhKemController::class, 'store'])->name('chung-tu.tep.store');
Route::get('tep/{tepDinhKem}/xem', [TepDinhKemController::class, 'xem'])->name('tep.xem');
Route::get('tep/{tepDinhKem}/tai', [TepDinhKemController::class, 'tai'])->name('tep.tai');
Route::post('lo-hang/{loHang}/chung-tu', [ChungTuController::class, 'store'])->name('lo-hang.chung-tu.store');
Route::patch('chung-tu/{chungTu}/khong-ap-dung', [ChungTuController::class, 'khongApDung'])->name('chung-tu.khong-ap-dung');
Route::patch('chung-tu/{chungTu}/ap-dung-lai', [ChungTuController::class, 'apDungLai'])->name('chung-tu.ap-dung-lai');
Route::patch('chung-tu/{chungTu}/ban-goc', [ChungTuController::class, 'banGoc'])->name('chung-tu.ban-goc');
Route::delete('chung-tu/{chungTu}', [ChungTuController::class, 'destroy'])->name('chung-tu.destroy');
});

require __DIR__.'/auth.php';
