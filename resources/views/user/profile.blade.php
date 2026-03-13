@extends('layouts.app')

@section('title', 'Profil Saya - MBG')

@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Informasi Profil</h1>
        <p class="text-blue-100 mt-2">Kelola informasi akun dan pengaturan keamanan Anda secara aman.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri: Ringkasan Profil --}}
        <div class="lg:col-span-1">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20 p-8 text-center">
                <div class="relative inline-block mb-6">
                    {{-- Inisial Nama Dinamis --}}
                    <div class="w-28 h-28 bg-blue-600 rounded-full flex items-center justify-center text-4xl font-bold text-white shadow-xl mx-auto border-4 border-white/50">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="absolute bottom-1 right-1 w-8 h-8 bg-green-500 border-4 border-white rounded-full"></div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                
                <div class="mt-8 pt-8 border-t border-gray-100">
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-500 font-medium">Status Akun</span>
                        <span class="text-green-600 font-bold bg-green-50 px-3 py-1 rounded-full text-xs">Aktif</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Terdaftar Sejak</span>
                        <span class="text-gray-800 font-semibold">{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Tombol Logout --}}
            <div class="mt-6">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 flex items-center justify-between border border-white/20 shadow-xl">
                    <div>
                        <h3 class="text-blue-900 font-bold text-sm">Sesi Aktif</h3>
                        <p class="text-[11px] text-blue-700">Ingin keluar dari sistem?</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition duration-200 transform hover:scale-105 shadow-lg flex items-center gap-2">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail & Form --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Data Pribadi --}}
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20">
                <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Detail Akun</h3>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-widest">Nama Lengkap</label>
                            <div class="text-sm font-semibold text-gray-800 bg-gray-50 px-5 py-4 rounded-xl border border-gray-100 shadow-inner">
                                {{ auth()->user()->name }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-widest">Alamat Email</label>
                            <div class="text-sm font-semibold text-gray-800 bg-gray-50 px-5 py-4 rounded-xl border border-gray-100 shadow-inner">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Keamanan --}}
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20">
                <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Keamanan Kata Sandi</h3>
                </div>
                <div class="p-8">
                    <form action="#" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-widest">Password Baru</label>
                                <input type="password" class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition shadow-inner" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-widest">Konfirmasi Password</label>
                                <input type="password" class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition shadow-inner" placeholder="••••••••">
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-xl font-bold text-sm transition duration-200 transform hover:scale-105 shadow-xl">
                                Perbarui Keamanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection