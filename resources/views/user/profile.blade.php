@extends('layouts.app')

@section('title', 'Profil & User')
@section('breadcrumb', 'Beranda / Sistem / User')

@section('content')
<div class="wireframe-note mb-5">
    🔵 <strong>Profil & User:</strong> Ganti password dan informasi akun admin.
</div>

<div class="mb-5">
    <h2 class="text-[22px] font-bold tracking-[-0.3px]">👤 Profil & Manajemen User</h2>
    <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Pengaturan akun administrator</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Informasi Akun --}}
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Informasi Akun</div>
        </div>

        <div class="p-4 sm:p-5">
            {{-- Avatar & Info --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 pb-4 mb-5" style="border-bottom:1px solid var(--border-color);">
                <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-accent rounded-xl flex items-center justify-center text-2xl font-bold text-white shrink-0">
                    A
                </div>
                <div>
                    <div class="text-lg font-bold">Admin Gudang</div>
                    <div class="text-sm text-accent">Administrator</div>
                    <div class="text-xs mt-0.5" style="color:var(--text-secondary);">admin@inventaris.local</div>
                </div>
            </div>

            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">Nama Lengkap</label>
                    <input type="text" name="nama" value="Admin Gudang" class="form-input w-full">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">Email</label>
                    <input type="email" name="email" value="admin@inventaris.local" class="form-input w-full">
                </div>
                <div class="flex justify-end pt-3" style="border-top:1px solid var(--border-color);">
                    <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ganti Password --}}
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Ganti Password</div>
        </div>

        <div class="p-4 sm:p-5">
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Password Saat Ini <span class="text-danger">*</span>
                    </label>
                    <input type="password" name="password_lama" placeholder="Masukkan password lama" class="form-input w-full">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Password Baru <span class="text-danger">*</span>
                    </label>
                    <input type="password" name="password_baru" placeholder="Minimal 8 karakter" class="form-input w-full">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Konfirmasi Password Baru <span class="text-danger">*</span>
                    </label>
                    <input type="password" name="password_konfirmasi" placeholder="Ulangi password baru" class="form-input w-full">
                </div>
                <div class="flex justify-end pt-3" style="border-top:1px solid var(--border-color);">
                    <button type="submit" class="btn-primary" style="background:#10b981;">
                        🔐 Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection