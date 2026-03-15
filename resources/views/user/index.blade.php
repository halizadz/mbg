@extends('layouts.app')

@section('title', 'Manajemen User')
@section('breadcrumb', 'Beranda / Manajemen User')

@section('content')
<div class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-users mr-2"></i> Manajemen User</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Kelola admin dan pengguna sistem</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn-primary text-[13px] px-4 py-2 inline-flex items-center gap-2">
        <span>+</span> Tambah User
    </a>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-lg text-sm bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 px-4 py-3 rounded-lg text-sm bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400">
    {{ session('error') }}
</div>
@endif

<div class="section-card">
    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[700px] lg:min-w-full">
            <thead>
                <tr>
                    <th class="w-12 text-center">No</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($users->count() > 0)
                    @foreach($users as $index => $user)
                    <tr>
                        <td class="text-center text-[13px]" style="color:var(--text-secondary);">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-accent rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0">
                                    {{ $user->initials }}
                                </div>
                                <span class="font-medium">{{ $user->name }}</span>
                                @if(auth()->id() === $user->id)
                                    <span class="badge badge-green text-[10px] ml-2">You</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-blue">Admin</span>
                            @else
                                <span class="badge text-slate-800 bg-slate-100 border border-slate-200">Operator</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn-secondary text-[11px] px-3 py-1.5">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                @if(auth()->id() !== $user->id)
                                <button type="button"
                                        class="btn-secondary text-[11px] px-3 py-1.5 text-danger border-danger/30 hover:bg-danger/10"
                                        onclick="bukaModalHapus({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 mb-4">
                                <i class="fas fa-users text-2xl text-blue-500"></i>
                            </div>
                            <h3 class="text-base font-semibold text-gray-800 mb-1">Belum ada data pengguna</h3>
                            <p class="text-sm text-gray-500 mb-5 max-w-sm mx-auto">Tambahkan admin atau pengguna pertama ke dalam sistem Anda untuk mulai mengelola akses.</p>
                            <a href="{{ route('users.create') }}" class="btn-primary flex-inline items-center justify-center gap-2 mx-auto max-w-xs">
                                <i class="fas fa-plus"></i> Tambah Pengguna
                            </a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-5 py-4" style="border-top:1px solid var(--border-color);">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Form hapus (tersembunyi) --}}
<form id="formHapusUser" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- Modal Konfirmasi Hapus --}}
<div id="modalHapusUser"
     class="fixed inset-0 z-[200] hidden items-center justify-center"
     style="background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);">
    <div class="rounded-2xl shadow-2xl p-6 w-[90vw] max-w-sm mx-auto text-center"
         style="background:var(--bg-secondary);border:1px solid var(--border-color);">
        <div class="text-4xl mb-3"><i class="fas fa-trash-alt text-red-500"></i></div>
        <h3 class="text-base font-bold mb-1">Hapus User</h3>
        <p class="text-xs mb-1" style="color:var(--text-secondary);">Anda akan menghapus user:</p>
        <p id="namaUserHapus" class="font-semibold text-sm mb-4" style="color:var(--text-primary);">-</p>
        <p class="text-xs mb-5" style="color:#ef4444;">Tindakan ini tidak dapat dibatalkan!</p>
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="tutupModalHapus()"
                    class="btn-secondary px-5 py-2 text-sm">Batal</button>
            <button type="button" onclick="eksekusiHapus()"
                    class="btn-primary px-5 py-2 text-sm" style="background:#ef4444;"><i class="fas fa-trash-alt mr-2"></i> Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
let targetHapusId = null;

function bukaModalHapus(userId, userName) {
    targetHapusId = userId;
    document.getElementById('namaUserHapus').textContent = userName;
    document.getElementById('modalHapusUser').classList.remove('hidden');
    document.getElementById('modalHapusUser').classList.add('flex');
}

function tutupModalHapus() {
    targetHapusId = null;
    document.getElementById('modalHapusUser').classList.add('hidden');
    document.getElementById('modalHapusUser').classList.remove('flex');
}

function eksekusiHapus() {
    if (!targetHapusId) return;
    const form = document.getElementById('formHapusUser');
    form.action = `/users/${targetHapusId}`;
    form.submit();
}

// Tutup modal saat klik luar
document.getElementById('modalHapusUser').addEventListener('click', function(e) {
    if (e.target === this) tutupModalHapus();
});
</script>
@endsection
