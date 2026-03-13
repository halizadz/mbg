@props([
    'color'     => 'blue',
    'label'     => '',
    'icon'      => '📦',
    'value'     => '0',
    'sub'       => '',
    'trend'     => null,
    'trendText' => ''
])

@php
$gradients = [
    'blue'   => 'from-accent to-accent2',
    'green'  => 'from-success to-emerald-400',
    'orange' => 'from-warning to-amber-400',
    'red'    => 'from-danger to-red-400',
];
$iconBgs = [
    'blue'   => 'rgba(59,130,246,0.15)',
    'green'  => 'rgba(16,185,129,0.15)',
    'orange' => 'rgba(245,158,11,0.15)',
    'red'    => 'rgba(239,68,68,0.15)',
];
$gradient = $gradients[$color] ?? $gradients['blue'];
$iconBg   = $iconBgs[$color]   ?? $iconBgs['blue'];
@endphp

<div class="stat-card">
    {{-- Top color bar --}}
    <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r {{ $gradient }}"></div>

    <div class="flex items-start justify-between mb-3">
        <div class="text-[11px] sm:text-xs font-medium" style="color:var(--text-secondary);">{{ $label }}</div>
        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg flex items-center justify-center text-sm sm:text-base shrink-0"
             style="background: <? $iconBg ?>;">
            {{ $icon }}
        </div>
    </div>

    <div class="text-2xl sm:text-[32px] font-bold font-mono tracking-[-1px]">{{ $value }}</div>

    <div class="text-[10px] sm:text-[11.5px] mt-1" style="color:var(--text-secondary);">{{ $sub }}</div>

    @if($trend)
    @php
        $trendBg   = $trend === 'up' ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)';
        $trendColor= $trend === 'up' ? '#10b981' : '#ef4444';
    @endphp
    <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-semibold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full mt-2"
          style="background:<? $trendBg ?>;color:{{ $trendColor }};">
        {{ $trendText }}
    </span>
    @endif
</div>