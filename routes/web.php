<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ==================== AUTH ====================
Route::get('/', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/', function (Request $request) {
    return $request->all();
});

// ==================== DASHBOARD ====================
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


// ==================== BARANG ====================
Route::prefix('barang')->group(function () {
    Route::get('/', function () {
        return view('pages.barang.index');
    })->name('barang.index');

    Route::get('/tambah', function () {
        return view('pages.barang.tambah');
    })->name('barang.tambah');
});

// ==================== TRANSAKSI ====================
Route::prefix('transaksi')->group(function () {
    Route::get('/masuk', function () {
        return view('pages.transaksi.masuk');
    })->name('transaksi.masuk');

    Route::get('/keluar', function () {
        return view('pages.transaksi.keluar');
    })->name('transaksi.keluar');
});

// ==================== LAPORAN ====================
Route::get('/laporan', function () {
    return view('laporan.index');
})->name('laporan.index');

// ==================== STOK ====================
Route::get('/stok/menipis', function () {
    return view('stok.menipis');
})->name('stok.menipis');

// ==================== USER ====================
Route::get('/user/profil', function () {
    return view('user.profile');
})->name('user.profil');