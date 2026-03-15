@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem (Audit Trail)')
@section('breadcrumb', 'Beranda / Sistem / Audit Trail')

@section('content')
<div class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-history mr-2"></i> Audit Trail</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Rekam jejak setiap perubahan pada sistem</p>
    </div>
</div>

<div class="section-card">
    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[800px] lg:min-w-full">
            <thead>
                <tr>
                    <th class="w-12 text-center">Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Keterangan</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                <tr>
                    <td class="text-[12px] whitespace-nowrap">
                        <div class="font-medium">{{ $log->created_at->format('d/m/Y') }}</div>
                        <div style="color:var(--text-secondary);">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[9px] font-bold text-slate-600 dark:text-slate-300">
                                {{ strtoupper(substr($log->user->name ?? '?', 0, 2)) }}
                            </div>
                            <span class="font-medium text-[13px]">{{ $log->user->name ?? 'System/Deleted' }}</span>
                        </div>
                    </td>
                    <td>
                        @if($log->action === 'create')
                            <span class="badge badge-green text-[10px]"><i class="fas fa-plus mr-1"></i> CREATE</span>
                        @elseif($log->action === 'update')
                            <span class="badge badge-blue text-[10px]"><i class="fas fa-edit mr-1"></i> UPDATE</span>
                        @elseif($log->action === 'delete')
                            <span class="badge text-red-700 bg-red-100 border border-red-200 text-[10px]"><i class="fas fa-trash mr-1"></i> DELETE</span>
                        @elseif($log->action === 'restore')
                            <span class="badge text-emerald-700 bg-emerald-100 border border-emerald-200 text-[10px]"><i class="fas fa-trash-restore mr-1"></i> RESTORE</span>
                        @elseif($log->action === 'force_delete')
                            <span class="badge text-white bg-red-600 border border-red-700 text-[10px]"><i class="fas fa-times mr-1"></i> PERMANENT</span>
                        @else
                            <span class="badge text-slate-700 bg-slate-100 border border-slate-200 text-[10px]">{{ strtoupper($log->action) }}</span>
                        @endif
                    </td>
                    <td class="text-[13px] max-w-[250px] truncate" title="{{ $log->description }}">{{ $log->description }}</td>
                    <td class="text-[12px] font-mono text-slate-500">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-slate-500">Belum ada log aktivitas yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($logs->hasPages())
    <div class="px-5 py-4" style="border-top:1px solid var(--border-color);">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
