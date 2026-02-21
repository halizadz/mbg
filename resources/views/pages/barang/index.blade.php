@extends('layouts.app')

@section('title', 'Data Barang')
@section('breadcrumb', 'Beranda / Manajemen Barang')

@section('content')
<div class="wireframe-note mb-5">
    🔵 <strong>Data Barang:</strong> CRUD barang dengan pagination, search, dan filter kategori.
</div>

<div class="page-header mb-5">
    <h2 class="text-[22px] font-bold tracking-[-0.3px]">📋 Data Barang</h2>
    <p class="text-[13px] text-[var(--text-secondary)] mt-1">Kelola semua data barang inventaris</p>
</div>

<div class="section-card">
    <div class="toolbar px-5 py-3.5 border-b border-[var(--border-color)] flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-2.5">
        <div class="toolbar-search flex items-center gap-2 bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg px-3 py-[7px] w-full sm:w-auto sm:flex-1 sm:max-w-[280px]">
            <span class="text-[var(--text-secondary)]">🔍</span>
            <input type="text" placeholder="Cari nama atau kode barang..." 
                   class="bg-transparent border-none outline-none text-[var(--text-primary)] text-[13px] font-sans w-full">
        </div>
        
        <select class="filter-select px-3 py-2 sm:py-[7px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-[13px] font-sans outline-none cursor-pointer w-full sm:w-auto">
            <option>Semua Kategori</option>
            <option>Alat Tulis Kantor</option>
            <option>Elektronik</option>
            <option>Kebersihan</option>
            <option>Furniture</option>
        </select>
        
        <div class="w-full sm:w-auto sm:ml-auto">
            <a href="{{ route('barang.tambah') }}" class="btn-primary inline-flex items-center justify-center gap-2 w-full sm:w-auto">
                ➕ Tambah Barang
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[1000px] lg:min-w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Stok</th>
                    <th>Min Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                $barang = [
                    ['id' => 1, 'kode' => 'BRG-001', 'nama' => 'Kertas HVS A4 70gsm', 'kategori' => 'ATK', 'satuan' => 'Rim', 'stok' => 45, 'min_stok' => 10, 'status' => 'Aman', 'status_color' => 'green'],
                    ['id' => 2, 'kode' => 'BRG-002', 'nama' => 'Tinta Printer Canon PG-745', 'kategori' => 'ATK', 'satuan' => 'Botol', 'stok' => 2, 'min_stok' => 5, 'status' => 'Menipis', 'status_color' => 'red'],
                    ['id' => 3, 'kode' => 'BRG-003', 'nama' => 'Mouse Wireless Logitech', 'kategori' => 'Elektronik', 'satuan' => 'Unit', 'stok' => 12, 'min_stok' => 3, 'status' => 'Aman', 'status_color' => 'green'],
                    ['id' => 4, 'kode' => 'BRG-004', 'nama' => 'Sabun Cuci Tangan', 'kategori' => 'Kebersihan', 'satuan' => 'Botol', 'stok' => 8, 'min_stok' => 5, 'status' => 'Hampir', 'status_color' => 'yellow'],
                    ['id' => 5, 'kode' => 'BRG-005', 'nama' => 'Ballpoint Pilot G2 0.5', 'kategori' => 'ATK', 'satuan' => 'Pcs', 'stok' => 120, 'min_stok' => 20, 'status' => 'Aman', 'status_color' => 'green'],
                    ['id' => 6, 'kode' => 'BRG-006', 'nama' => 'Gunting Besar Joyko', 'kategori' => 'ATK', 'satuan' => 'Pcs', 'stok' => 7, 'min_stok' => 3, 'status' => 'Aman', 'status_color' => 'green'],
                ];
                @endphp
                
                @foreach($barang as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td class="mono">{{ $item['kode'] }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td><span class="badge badge-{{ $item['kategori'] == 'ATK' ? 'blue' : ($item['kategori'] == 'Elektronik' ? 'yellow' : 'gray') }}">{{ $item['kategori'] }}</span></td>
                    <td>{{ $item['satuan'] }}</td>
                    <td class="mono {{ $item['stok'] <= $item['min_stok'] ? 'text-danger' : '' }}">{{ $item['stok'] }}</td>
                    <td class="mono">{{ $item['min_stok'] }}</td>
                    <td><span class="badge badge-{{ $item['status_color'] }}">✓ {{ $item['status'] }}</span></td>
                    <td>
                        <div class="flex gap-2">
                            <button class="tbl-btn edit px-2.5 py-1 rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] text-[var(--text-secondary)] text-[11px] hover:border-accent/40 hover:text-accent">
                                ✏️ Edit
                            </button>
                            <button class="tbl-btn del px-2.5 py-1 rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] text-[var(--text-secondary)] text-[11px] hover:border-danger/40 hover:text-danger">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3 border-t border-[var(--border-color)] text-xs text-[var(--text-secondary)]">
        <span>Menampilkan 1–6 dari 47 barang</span>
        <div class="page-btns flex gap-1">
            <button class="page-btn w-7 h-7 rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] hover:bg-[var(--glass-hover)]">‹</button>
            <button class="page-btn w-7 h-7 rounded-md border border-accent bg-accent text-white">1</button>
            <button class="page-btn w-7 h-7 rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] hover:bg-[var(--glass-hover)]">2</button>
            <button class="page-btn w-7 h-7 rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] hover:bg-[var(--glass-hover)]">3</button>
            <button class="page-btn w-7 h-7 rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] hover:bg-[var(--glass-hover)]">›</button>
        </div>
    </div>
</div>
@endsection