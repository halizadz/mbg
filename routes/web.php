<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\TrashController;
use Illuminate\Support\Facades\Route;

// ==================== GUEST (Belum Login) ====================
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

// ==================== AUTH (Sudah Login) ====================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== BARANG (CRUD) ====================
    Route::prefix('barang')->group(function () {
        Route::get('/',              [BarangController::class, 'index'])->name('barang.index');
        Route::get('/tambah',        [BarangController::class, 'create'])->name('barang.tambah');
        Route::post('/',             [BarangController::class, 'store'])->name('barang.store');
        Route::get('/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
        Route::put('/{barang}',      [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/{barang}',   [BarangController::class, 'destroy'])->name('barang.destroy');
    });

    // ==================== TRANSAKSI ====================
    Route::prefix('transaksi')->group(function () {
        // Barang Masuk
        Route::get('/masuk',        [BarangMasukController::class, 'index'])->name('transaksi.masuk');
        Route::get('/masuk/tambah', [BarangMasukController::class, 'create'])->name('transaksi.masuk.create');
        Route::post('/masuk',       [BarangMasukController::class, 'store'])->name('transaksi.masuk.store');

        // Barang Keluar
        Route::get('/keluar',        [BarangKeluarController::class, 'index'])->name('transaksi.keluar');
        Route::get('/keluar/tambah', [BarangKeluarController::class, 'create'])->name('transaksi.keluar.create');
        Route::post('/keluar',       [BarangKeluarController::class, 'store'])->name('transaksi.keluar.store');
    });

    // ==================== LAPORAN ====================
    Route::get('/laporan',            [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
    Route::get('/laporan/print',      [LaporanController::class, 'print'])->name('laporan.print');

    // ==================== STOK MENIPIS ====================
    Route::get('/stok/menipis', [BarangController::class, 'menipis'])->name('stok.menipis');

    // ==================== USER PROFILE ====================
    Route::get('/user/profil', [UserController::class, 'profil'])->name('user.profil');

    // ==================== ADMIN ONLY AREA ====================
    Route::middleware('admin')->group(function () {
        // User Management
        Route::resource('users', UserController::class);

        // Audit Trail
        Route::get('/audit', [ActivityLogController::class, 'index'])->name('audit.index');

        // Trash / Recycle Bin
        Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
        Route::post('/trash/barang/{id}/restore', [TrashController::class, 'restoreBarang'])->name('trash.barang.restore');
        Route::delete('/trash/barang/{id}/force-delete', [TrashController::class, 'forceDeleteBarang'])->name('trash.barang.force-delete');
        Route::post('/trash/user/{id}/restore', [TrashController::class, 'restoreUser'])->name('trash.user.restore');
        Route::delete('/trash/user/{id}/force-delete', [TrashController::class, 'forceDeleteUser'])->name('trash.user.force-delete');
    });
});