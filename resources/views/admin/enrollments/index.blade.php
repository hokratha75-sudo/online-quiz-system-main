@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50/50 text-slate-900 p-6 md:p-10 font-inter">
    
    <!-- Breadcrumb & Header -->
    <div class="mb-10">
        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">
            <i class="fas fa-circle text-[6px] text-indigo-500"></i>
            <span>Institutional Hierarchy</span>
        </div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight mb-2">Enrollment Management</h1>
                <p class="text-sm text-slate-600 max-w-2xl font-medium opacity-80">Administrative portal for departmental assignments and user access control.</p>
            </div>
            
            <div class="flex items-center">
                <div class="bg-indigo-50 border border-indigo-100 px-4 py-2 rounded-full flex items-center gap-2 text-[10px] font-bold text-indigo-600 uppercase tracking-widest shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    Session Verified
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm shadow-slate-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="fas fa-users-viewfinder"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Faculty Members</span>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1 tracking-tight">{{ number_format($stats['total_faculty']) }}</div>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Teaching Staff</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm shadow-slate-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Student Body</span>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1 tracking-tight">{{ number_format($stats['total_students']) }}</div>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Active Learners</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm shadow-slate-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <i class="fas fa-book-bookmark"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Curriculum Items</span>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1 tracking-tight">{{ number_format($stats['total_subjects']) }}</div>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Across {{ $departments->count() }} divisions</p>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm shadow-slate-100">
        <!-- Toolbar -->
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Departmental Overview</h3>
                <div class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-widest rounded-md border border-indigo-100">
                    {{ $departments->count() }} Sectors
                </div>
            </div>
            
            <div class="relative w-full sm:w-96 group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs group-focus-within:text-indigo-600 transition-colors"></i>
                <input type="text" id="deptSearch" placeholder="Filter by department name..." 
                       class="w-full h-11 pl-11 pr-4 bg-slate-50 border border-slate-200 text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all placeholder:text-slate-400 rounded-xl">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="deptTable">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="ps-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Departmental Identity</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Faculty Count</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Student Count</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Subject Count</th>
                        <th class="pe-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Access Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($departments as $department)
                    @php
                        $facultyCount = $department->users->where('role_id', 2)->count();
                        $studentCount = $department->users->where('role_id', 3)->count();
                        $subjectCount = $department->subjects->count();
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-all group">
                        <td class="ps-8 py-6">
                            <div class="flex items-center gap-4">
                                <span class="text-[11px] font-bold text-slate-400 w-6">{{ $loop->iteration }}</span>
                                <div class="flex flex-col">
                                    <span class="text-[14px] font-bold text-slate-900 group-hover:text-indigo-600 transition-colors tracking-tight uppercase">{{ $department->department_name }}</span>
                                    <span class="text-[9px] font-bold text-slate-500 mt-0.5 tracking-widest">IDENTIFIER: #{{ str_pad($department->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-7 rounded-full bg-slate-50 border border-slate-200 text-[11px] font-bold {{ $facultyCount > 0 ? 'text-slate-900' : 'text-slate-400 opacity-80' }}">
                                {{ $facultyCount }}
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-7 rounded-full bg-slate-50 border border-slate-200 text-[11px] font-bold {{ $studentCount > 0 ? 'text-slate-900' : 'text-slate-400 opacity-80' }}">
                                {{ $studentCount }}
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-7 rounded-full bg-slate-50 border border-slate-200 text-[11px] font-bold {{ $subjectCount > 0 ? 'text-slate-900' : 'text-slate-400 opacity-80' }}">
                                {{ $subjectCount }}
                            </div>
                        </td>
                        <td class="pe-8 py-6 text-right">
                            <a href="{{ route('admin.enrollments.manage', $department->id) }}" class="inline-flex items-center h-8 px-4 rounded-lg border border-slate-200 bg-white hover:border-indigo-600 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 text-[10px] font-bold uppercase tracking-widest transition-all group/btn shadow-sm">
                                <span>Manage</span>
                                <i class="fas fa-arrow-right-long ms-2 text-[8px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-layer-group text-slate-200 text-4xl mb-4"></i>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em]">No departmental records identified</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Modern Search Logic
    document.getElementById('deptSearch').addEventListener('keyup', function() {
        const query = this.value.toUpperCase();
        document.querySelectorAll('#deptTable tbody tr').forEach(row => {
            const deptName = row.querySelector('td:first-child .text-\\[14px\\]').textContent.toUpperCase();
            row.style.display = deptName.includes(query) ? '' : 'none';
        });
    });
</script>
@endsection

