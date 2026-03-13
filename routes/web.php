<?php

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - Project MBG (Makan Bergizi Gratis)
|--------------------------------------------------------------------------
*/

// ==================== AUTH (PUBLIC) ====================
Route::get('/', function () {
    return view('login');
})->name('login');

// Handler Proses Login
Route::post('/', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Autentikasi ke database
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->name('login.post');

Route::get('/register', function () {
    return view('register');
})->name('register');

// Handler Proses Register & Simpan Database
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Simpan akun baru dengan password terenkripsi
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), 
    ]);

    // Otomatis Login setelah register
    Auth::login($user);

    return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat!');
})->name('register.post');

// Proses Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');


// ==================== PROTECTED ROUTES (Hanya untuk yang sudah login) ====================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Manajemen Barang
    Route::prefix('barang')->group(function () {
        Route::get('/', function () { return view('pages.barang.index'); })->name('barang.index');
        Route::get('/tambah', function () { return view('pages.barang.tambah'); })->name('barang.tambah');
    });

    // Transaksi Stok
    Route::prefix('transaksi')->group(function () {
        Route::get('/masuk', function () { return view('pages.transaksi.masuk'); })->name('transaksi.masuk');
        Route::get('/keluar', function () { return view('pages.transaksi.keluar'); })->name('transaksi.keluar');
    });

    Route::get('/laporan', function () { return view('laporan.index'); })->name('laporan.index');
    Route::get('/stok/menipis', function () { return view('stok.menipis'); })->name('stok.menipis');
    
    Route::get('/user/profil', function () { 
        return view('user.profile'); 
    })->name('user.profile');
});