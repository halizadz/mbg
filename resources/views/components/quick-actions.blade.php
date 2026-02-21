<div class="grid grid-cols-2 gap-2 p-3 sm:p-4">
    @php
    $actions = [
        ['route' => 'transaksi.masuk',  'icon' => '📥', 'label' => 'Barang Masuk'],
        ['route' => 'transaksi.keluar', 'icon' => '📤', 'label' => 'Barang Keluar'],
        ['route' => 'barang.tambah',    'icon' => '➕', 'label' => 'Tambah Barang'],
        ['route' => 'laporan.index',    'icon' => '🖨️', 'label' => 'Cetak Laporan'],
    ];
    @endphp

    @foreach($actions as $action)
    <a href="{{ route($action['route']) }}"
       class="flex flex-col items-center gap-1.5 sm:gap-2 p-3 sm:p-4 rounded-[10px] transition-all duration-200 no-underline"
       style="border:1px solid var(--border-color);background:var(--glass-bg);color:var(--text-primary);"
       onmouseover="this.style.background='var(--glass-hover)';this.style.borderColor='rgba(255,255,255,0.12)'"
       onmouseout="this.style.background='var(--glass-bg)';this.style.borderColor='var(--border-color)'">
        <span class="text-xl sm:text-[22px]">{{ $action['icon'] }}</span>
        <span class="text-[10px] sm:text-xs text-center">{{ $action['label'] }}</span>
    </a>
    @endforeach
</div>