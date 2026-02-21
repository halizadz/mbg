@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Beranda / Dashboard')

@section('content')
<div class="wireframe-note mb-5">
    🔵 <strong>Dashboard:</strong> Ringkasan stok, transaksi hari ini, grafik, dan alert stok menipis.
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
    @include('components.stat-card', [
        'color' => 'blue', 'label' => 'Total Stok Tersedia',
        'icon' => '📦', 'value' => '1,248', 'sub' => '47 jenis barang',
        'trend' => 'up', 'trendText' => '↑ 3.2%'
    ])
    @include('components.stat-card', [
        'color' => 'green', 'label' => 'Barang Masuk Hari Ini',
        'icon' => '📥', 'value' => '38', 'sub' => '5 transaksi',
        'trend' => 'up', 'trendText' => '↑ 12 vs kemarin'
    ])
    @include('components.stat-card', [
        'color' => 'orange', 'label' => 'Barang Keluar Hari Ini',
        'icon' => '📤', 'value' => '22', 'sub' => '3 transaksi',
        'trend' => 'down', 'trendText' => '↓ 5 vs kemarin'
    ])
    @include('components.stat-card', [
        'color' => 'red', 'label' => 'Stok Menipis',
        'icon' => '⚠️', 'value' => '4', 'sub' => 'Perlu restock segera',
        'trend' => 'down', 'trendText' => 'Kritis'
    ])
</div>

<!-- Chart & Alerts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4 sm:mb-6">
    <!-- Chart -->
    <div class="lg:col-span-2">
        <div class="section-card">
            <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
                <div>
                    <div class="section-title">Grafik Transaksi — 7 Hari Terakhir</div>
                    <div class="section-sub">Perbandingan barang masuk vs keluar</div>
                </div>
                <a href="{{ route('laporan.index') }}" class="section-action text-[11px] sm:text-xs px-2 sm:px-2.5 py-1 sm:py-1.5">
                    Lihat Laporan →
                </a>
            </div>
            <div class="p-3 sm:p-5">
                <div class="overflow-x-auto pb-2">
                    @php
                        $chartDays   = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Hari ini'];
                        $chartMasuk  = [80, 110, 60, 140, 95, 50, 38];
                        $chartKeluar = [45, 70, 90, 55, 120, 30, 22];
                        $maxVal      = max(array_merge($chartMasuk, $chartKeluar)); // 140
                        $chartH      = 140; // px tinggi area chart
                    @endphp
                    <div class="flex items-end gap-1 sm:gap-2 min-w-[560px] sm:min-w-0" style="height:<? $chartH + 20 ?>px;">
                        @foreach($chartDays as $index => $day)
                        @php
                            $hMasuk  = (int) round($chartMasuk[$index]  / $maxVal * $chartH);
                            $hKeluar = (int) round($chartKeluar[$index] / $maxVal * $chartH);
                            $isToday = $index === count($chartDays) - 1;
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="flex gap-[3px] items-end w-full" style="height:<? $chartH ?>px;">
                                {{-- Bar Masuk --}}
                                <div class="flex-1 rounded-t transition-opacity hover:opacity-75 cursor-pointer"
                                     style="height:<? $hMasuk ?>px; background:linear-gradient(to bottom,#3b82f6,rgba(59,130,246,0.5));"
                                     title="Masuk: {{ $chartMasuk[$index] }}"></div>
                                {{-- Bar Keluar --}}
                                <div class="flex-1 rounded-t transition-opacity hover:opacity-75 cursor-pointer"
                                     style="height:<? $hKeluar ?>px; background:linear-gradient(to bottom,#ef4444,rgba(239,68,68,0.5));"
                                     title="Keluar: {{ $chartKeluar[$index] }}"></div>
                            </div>
                            <span class="text-[10px]" style="color:<? $isToday ? '#3b82f6' : 'var(--text-secondary)' ?>; font-weight:{{ $isToday ? '600' : '400' }};">
                                {{ $day }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- Legend -->
                <div class="flex gap-3 sm:gap-4 mt-3 pt-3 text-[11px] sm:text-xs" style="border-top:1px solid var(--border-color);">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-sm" style="background:#3b82f6;"></span>
                        Barang Masuk
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-sm" style="background:#ef4444;"></span>
                        Barang Keluar
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts & Actions -->
    <div class="space-y-4">
        <!-- Alert Stok -->
        <div class="section-card">
            <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
                <div>
                    <div class="section-title">⚠️ Stok Menipis</div>
                    <div class="section-sub">Di bawah batas minimum</div>
                </div>
                <a href="{{ route('stok.menipis') }}" class="section-action text-[11px] sm:text-xs">Lihat Semua</a>
            </div>
            <div class="divide-y" style="border-color:var(--border-color);">
                @php
                $alerts = [
                    ['name' => 'Kertas HVS A4',       'code' => 'BRG-012', 'unit' => 'Lembar', 'stok' => 2, 'color' => 'danger'],
                    ['name' => 'Tinta Printer Hitam',  'code' => 'BRG-031', 'unit' => 'Botol',  'stok' => 1, 'color' => 'danger'],
                    ['name' => 'Spidol Whiteboard',    'code' => 'BRG-008', 'unit' => 'Pcs',    'stok' => 4, 'color' => 'warning'],
                    ['name' => 'Amplop Coklat F4',     'code' => 'BRG-019', 'unit' => 'Pack',   'stok' => 3, 'color' => 'warning'],
                ];
                @endphp
                @foreach($alerts as $alert)
                    @include('components.alert-item', $alert)
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-card">
            <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
                <div class="section-title">Aksi Cepat</div>
            </div>
            @include('components.quick-actions')
        </div>
    </div>
</div>

<!-- Riwayat Transaksi -->
<div class="section-card overflow-hidden">
    <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
        <div>
            <div class="section-title">Transaksi Terbaru</div>
            <div class="section-sub">10 transaksi terakhir hari ini</div>
        </div>
        <a href="{{ route('laporan.index') }}" class="section-action text-[11px] sm:text-xs">Lihat Semua →</a>
    </div>

    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[700px] sm:min-w-full">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Stok Sekarang</th>
                    <th>Dicatat oleh</th>
                </tr>
            </thead>
            <tbody>
                @php
                $rows = [
                    ['14:32','BRG-012','Kertas HVS A4',       'Keluar','red',   10,  2, 'Admin'],
                    ['13:15','BRG-005','Ballpoint Pilot',      'Masuk', 'green', 50,120, 'Admin'],
                    ['11:48','BRG-021','Binder Clip No.2',     'Masuk', 'green', 30, 85, 'Admin'],
                    ['10:20','BRG-031','Tinta Printer Hitam',  'Keluar','red',    5,  1, 'Admin'],
                    ['09:05','BRG-014','Map Plastik Bening',   'Masuk', 'green', 24, 67, 'Admin'],
                ];
                @endphp
                @foreach($rows as [$waktu,$kode,$nama,$tipe,$tipeColor,$jml,$stok,$admin])
                <tr>
                    <td>{{ $waktu }}</td>
                    <td class="mono">{{ $kode }}</td>
                    <td>{{ $nama }}</td>
                    <td><span class="badge badge-{{ $tipeColor }}">{{ $tipe === 'Masuk' ? '📥' : '📤' }} {{ $tipe }}</span></td>
                    <td class="mono">{{ $jml }}</td>
                    <td class="mono {{ $stok <= 3 ? 'text-danger' : '' }}">{{ $stok }}</td>
                    <td>{{ $admin }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection