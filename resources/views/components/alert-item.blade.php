@props([
    'name'  => '',
    'code'  => '',
    'unit'  => '',
    'stok'  => 0,
    'color' => 'danger'
])

@php
$colorMap = [
    'danger'  => '#ef4444',
    'warning' => '#f59e0b',
    'success' => '#10b981',
];
$hex = $colorMap[$color] ?? '#ef4444';
@endphp

<div class="flex items-center gap-2 sm:gap-3 px-3 sm:px-5 py-2.5 sm:py-3 transition-colors duration-150"
     style="border-bottom:1px solid var(--border-color);"
     onmouseover="this.style.background='var(--glass-hover)'"
     onmouseout="this.style.background=''">
    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full shrink-0" style="background:<? $hex ?>;"></div>
    <div class="flex-1 min-w-0">
        <div class="text-xs sm:text-[13px] font-medium truncate">{{ $name }}</div>
        <div class="text-[10px] sm:text-[11px] font-mono truncate" style="color:var(--text-secondary);">{{ $code }} · {{ $unit }}</div>
    </div>
    <div class="text-right shrink-0">
        <div class="text-base sm:text-lg font-bold font-mono" style="color:<? $hex ?>;">{{ $stok }}</div>
        <div class="text-[9px] sm:text-[10px]" style="color:var(--text-secondary);">sisa</div>
    </div>
</div>