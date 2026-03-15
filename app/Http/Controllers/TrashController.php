<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TrashController extends Controller
{
    public function index(): View
    {
        $barangs = Barang::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $users = User::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        return view('trash.index', compact('barangs', 'users'));
    }

    public function restoreBarang($id): RedirectResponse
    {
        $barang = Barang::onlyTrashed()->findOrFail($id);
        $barang->restore();
        ActivityLog::log('restore', "Barang di-restore: {$barang->nama_barang}", $barang);
        return back()->with('success', "Barang {$barang->nama_barang} berhasil dipulihkan.");
    }

    public function forceDeleteBarang($id): RedirectResponse
    {
        $barang = Barang::onlyTrashed()->findOrFail($id);
        $nama = $barang->nama_barang;
        ActivityLog::log('force_delete', "Barang dihapus permanen: {$nama}");
        $barang->forceDelete();
        return back()->with('success', "Barang {$nama} telah dihapus permanen.");
    }

    public function restoreUser($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        ActivityLog::log('restore', "User di-restore: {$user->name}", $user);
        return back()->with('success', "User {$user->name} berhasil dipulihkan.");
    }

    public function forceDeleteUser($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $nama = $user->name;
        ActivityLog::log('force_delete', "User dihapus permanen: {$nama}");
        $user->forceDelete();
        return back()->with('success', "User {$nama} telah dihapus permanen.");
    }
}
