@extends('layouts.app')

@section('title', 'Tempat Sampah')
@section('breadcrumb', 'Beranda / Sistem / Tempat Sampah')

@section('content')
<div class="mb-5">
    <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-trash-restore mr-2 text-red-500"></i> Tempat Sampah</h2>
    <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Kembalikan atau hapus permanen data yang telah dihapus sementara (Soft Delete)</p>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-lg text-sm bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Tabel Sampah Barang --}}
    <div class="section-card">
        <div class="p-4 border-b flex justify-between items-center" style="border-color:var(--border-color);">
            <h3 class="font-bold"><i class="fas fa-box text-accent mr-2"></i> Data Barang</h3>
            <span class="badge badge-blue">{{ $barangs->count() }} Item</span>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Kode & Nama</th>
                        <th>Dihapus Pada</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr>
                        <td>
                            <div class="font-medium text-[13px]">{{ $barang->kode_barang }}</div>
                            <div class="text-[12px] truncate max-w-[150px]" style="color:var(--text-secondary);">{{ $barang->nama_barang }}</div>
                        </td>
                        <td class="text-[12px]">{{ $barang->deleted_at->format('d/m/Y H:i') }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <form action="{{ route('trash.barang.restore', $barang->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded" title="Restore Data"><i class="fas fa-trash-restore"></i></button>
                                </form>
                                <form action="{{ route('trash.barang.force-delete', $barang->id) }}" method="POST" onsubmit="return confirm('Yakin hapus permanen? Data tidak bisa kembali!');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Hapus Permanen"><i class="fas fa-times-circle"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-6 text-[13px] text-slate-500">Tempat sampah barang kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Sampah User --}}
    <div class="section-card">
        <div class="p-4 border-b flex justify-between items-center" style="border-color:var(--border-color);">
            <h3 class="font-bold"><i class="fas fa-users text-accent2 mr-2"></i> Data Akun Admin</h3>
            <span class="badge badge-blue">{{ $users->count() }} Item</span>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Nama & Email</th>
                        <th>Dihapus Pada</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="font-medium text-[13px]">{{ $user->name }}</div>
                            <div class="text-[12px] truncate max-w-[150px]" style="color:var(--text-secondary);">{{ $user->email }}</div>
                        </td>
                        <td class="text-[12px]">{{ $user->deleted_at->format('d/m/Y H:i') }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <form action="{{ route('trash.user.restore', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded" title="Restore User"><i class="fas fa-trash-restore"></i></button>
                                </form>
                                <form action="{{ route('trash.user.force-delete', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus permanen akun ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Hapus Permanen"><i class="fas fa-times-circle"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-6 text-[13px] text-slate-500">Tempat sampah user kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
