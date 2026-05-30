{{-- resources/views/admin/enrollments/history.blade.php --}}
@extends('layouts.admin')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<div class="max-w-full mx-auto p-6 md:p-10 font-inter text-slate-900 bg-white min-h-screen">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 border-b border-slate-100 pb-10">
        <div>
            <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-[0.25em] mb-3">
                <i class="fas fa-history text-[9px] mr-1"></i> Audit Trail
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">{{ $department->department_name }}</h1>
            <p class="text-sm text-slate-500 mt-2 max-w-2xl leading-relaxed uppercase tracking-widest text-[11px]">Complete enrollment history and activity log</p>
        </div>
        
        <div class="flex items-center gap-4 flex-wrap">
            <a href="{{ route('enrollments.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left text-[10px]"></i> Back
            </a>
            <div class="flex gap-2">
                <button onclick="window.location.href='{{ route('enrollments.export.history', $department->id) }}'" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm">
                    <i class="fas fa-download text-[10px]"></i> Export CSV
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-50 rounded-xl p-4 mb-8 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label class="text-[9px] font-bold text-slate-500 uppercase block mb-1">Filter by Action</label>
            <select id="actionFilter" class="w-full md:w-48 px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs">
                <option value="">All Actions</option>
                <option value="enrolled">Enrolled</option>
                <option value="unenrolled">Unenrolled</option>
                <option value="subject_assigned">Subject Assigned</option>
                <option value="subject_removed">Subject Removed</option>
                <option value="class_created">Class Created</option>
                <option value="class_updated">Class Updated</option>
            </select>
        </div>
        <div class="flex-1">
            <label class="text-[9px] font-bold text-slate-500 uppercase block mb-1">Date Range</label>
            <div class="flex gap-2">
                <input type="date" id="startDate" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs flex-1">
                <input type="date" id="endDate" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs flex-1">
            </div>
        </div>
        <div class="flex-1">
            <label class="text-[9px] font-bold text-slate-500 uppercase block mb-1">Search</label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                <input type="text" id="searchHistory" placeholder="Search by user, admin, or details..." 
                       class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-xs">
            </div>
        </div>
        <div class="flex items-end">
            <button onclick="resetFilters()" class="px-4 py-2 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-600 hover:bg-white transition-all">
                Reset
            </button>
        </div>
    </div>

    <!-- History Table -->
    <div class="border border-slate-100 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="historyTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="ps-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date & Time</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">User/Subject</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Class</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Details</th>
                        <th class="pe-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="historyTableBody">
                    @forelse($history as $record)
                    <tr class="hover:bg-slate-50/50 transition-all history-row" 
                        data-action="{{ $record->action }}"
                        data-date="{{ $record->created_at->format('Y-m-d') }}"
                        data-search="{{ strtolower($record->user?->username . ' ' . $record->subject?->subject_name . ' ' . $record->admin?->username . ' ' . $record->reason) }}">
                        <td class="ps-8 py-4 text-[11px] font-medium text-slate-500">
                            {{ $record->created_at->format('M d, Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 text-[11px]">
                            <span class="px-2 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest
                                {{ $record->action === 'enrolled' ? 'bg-emerald-50 text-emerald-600' : 
                                   ($record->action === 'unenrolled' ? 'bg-red-50 text-red-600' : 
                                   ($record->action === 'subject_assigned' ? 'bg-indigo-50 text-indigo-600' : 
                                   ($record->action === 'subject_removed' ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-600'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $record->action)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[11px] font-medium text-slate-900">
                            @if($record->user)
                                {{ $record->user->username }}
                                <div class="text-[8px] text-slate-400">{{ $record->user->email }}</div>
                            @elseif($record->subject)
                                {{ $record->subject->subject_name }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[11px] text-slate-600">
                            @if($record->class)
                                {{ $record->class->class_name }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[11px] text-slate-600 max-w-xs truncate">
                            {{ $record->reason ?? '—' }}
                        </td>
                        <td class="pe-8 py-4 text-[11px] font-medium text-slate-600">
                            {{ $record->admin?->username ?? 'System' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-history text-4xl text-slate-200 mb-4"></i>
                                <p class="text-[11px] font-bold text-slate-300 uppercase tracking-widest">No history records found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($history->hasPages())
        <div class="px-8 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $history->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function filterHistory() {
        const actionFilter = document.getElementById('actionFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const searchQuery = document.getElementById('searchHistory').value.toLowerCase();
        
        let visibleCount = 0;
        document.querySelectorAll('.history-row').forEach(row => {
            let show = true;
            
            if (actionFilter && row.dataset.action !== actionFilter) show = false;
            if (startDate && row.dataset.date < startDate) show = false;
            if (endDate && row.dataset.date > endDate) show = false;
            if (searchQuery && !row.dataset.search.includes(searchQuery)) show = false;
            
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
        
        // Update count display
        let countDisplay = document.getElementById('visibleCount');
        if (!countDisplay) {
            countDisplay = document.createElement('div');
            countDisplay.id = 'visibleCount';
            countDisplay.className = 'text-[10px] text-slate-400 mt-2';
            document.querySelector('.border-slate-100.rounded-2xl').appendChild(countDisplay);
        }
        countDisplay.textContent = `Showing ${visibleCount} of ${document.querySelectorAll('.history-row').length} records`;
    }
    
    function resetFilters() {
        document.getElementById('actionFilter').value = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('searchHistory').value = '';
        filterHistory();
    }
    
    document.getElementById('actionFilter').addEventListener('change', filterHistory);
    document.getElementById('startDate').addEventListener('change', filterHistory);
    document.getElementById('endDate').addEventListener('change', filterHistory);
    document.getElementById('searchHistory').addEventListener('keyup', filterHistory);
    
    filterHistory();
</script>
@endsection