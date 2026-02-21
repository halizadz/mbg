@extends('layouts.app')

@section('title', 'Stok Menipis')
@section('breadcrumb', 'Beranda / Laporan / Stok Menipis')

@section('content')
<div class="wireframe-note mb-5">
    🔵 <strong>Stok Menipis:</strong> Daftar semua barang dengan stok di bawah atau mendekati batas minimum.
</div>

<div class="mb-5">
    <h2 class="text-[22px] font-bold tracking-[-0.3px]">⚠️ Stok Menipis</h2>
    <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Barang yang membutuhkan restock segera</p>
</div>

<div class="section-card">
    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[750px] lg:min-w-full">
            <thead>
                <tr>
                    <th>Prioritas</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok Saat Ini</th>
                    <th>Min Stok</th>
                    <th>Selisih</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                $stokKritis = [
                    ['prioritas' => 'Kritis', 'kode' => 'BRG-031', 'nama' => 'Tinta Printer Hitam', 'kategori' => 'ATK', 'stok' => 1, 'min' => 5,  'selisih' => -4],
                    ['prioritas' => 'Kritis', 'kode' => 'BRG-012', 'nama' => 'Kertas HVS A4',        'kategori' => 'ATK', 'stok' => 2, 'min' => 10, 'selisih' => -8],
                    ['prioritas' => 'Hampir', 'kode' => 'BRG-008', 'nama' => 'Spidol Whiteboard',    'kategori' => 'ATK', 'stok' => 4, 'min' => 5,  'selisih' => -1],
                    ['prioritas' => 'Hampir', 'kode' => 'BRG-019', 'nama' => 'Amplop Coklat F4',     'kategori' => 'ATK', 'stok' => 3, 'min' => 5,  'selisih' => -2],
                ];
                @endphp

                @foreach($stokKritis as $item)
                @php $isKritis = $item['prioritas'] === 'Kritis'; @endphp
                <tr>
                    <td>
                        <span class="badge {{ $isKritis ? 'badge-red' : 'badge-yellow' }}">
                            {{ $isKritis ? '🔴' : '🟡' }} {{ $item['prioritas'] }}
                        </span>
                    </td>
                    <td class="mono">{{ $item['kode'] }}</td>
                    <td class="font-medium">{{ $item['nama'] }}</td>
                    <td><span class="badge badge-blue">{{ $item['kategori'] }}</span></td>
                    <td class="mono font-bold text-danger">{{ $item['stok'] }}</td>
                    <td class="mono">{{ $item['min'] }}</td>
                    <td class="mono text-danger">{{ $item['selisih'] }}</td>
                    <td>
                        <a href="{{ route('transaksi.masuk') }}"
                           class="btn-primary text-[11px] px-3 py-1.5 inline-flex items-center gap-1">
                            📥 Input Masuk
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection