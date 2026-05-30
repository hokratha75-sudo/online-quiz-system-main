{{-- resources/views/admin/enrollments/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    
    .department-row {
        transition: all 0.2s ease;
    }
    .department-row:hover {
        background-color: #f8fafc;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .toast-notification {
        animation: slideIn 0.3s ease-out, fadeOut 0.3s ease-out 2.7s forwards;
    }
    
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes fadeOut {
        to { opacity: 0; visibility: hidden; }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 p-6 md:p-10">
    
    <!-- Header -->
    <div class="mb-10 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-indigo-100 px-4 py-2 rounded-full mb-4 shadow-sm">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                    <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-wider">Administrative Portal</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-bold text-slate-900 tracking-tight mb-3 bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-transparent">Enrollment Management</h1>
                <p class="text-sm text-slate-500 max-w-2xl">Centralized control for departmental assignments, user access, and academic resource allocation.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="bg-emerald-50 border border-emerald-100 px-4 py-2 rounded-full flex items-center gap-2 text-[10px] font-bold text-emerald-600 uppercase tracking-wider shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    System Active
                </div>
                <button onclick="location.reload()" class="bg-white border border-slate-200 px-3 py-2 rounded-full text-slate-500 hover:text-indigo-600 transition-all">
                    <i class="fas fa-sync-alt text-xs"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="stat-card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <i class="fas fa-building text-indigo-600 text-xl"></i>
                </div>
                <span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 px-2 py-1 rounded-full">{{ $departments->count() }} Total</span>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Departments</span>
                <div class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($departments->count()) }}</div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100">
                <span class="text-[9px] text-slate-400">Academic Units</span>
            </div>
        </div>

        <div class="stat-card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class="fas fa-chalkboard-user text-emerald-600 text-xl"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-full">Active</span>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Faculty Members</span>
                <div class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_faculty'] ?? 0) }}</div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100">
                <span class="text-[9px] text-slate-400">Teaching Staff</span>
            </div>
        </div>

        <div class="stat-card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class="fas fa-user-graduate text-amber-600 text-xl"></i>
                </div>
                <span class="text-[10px] font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-full">Enrolled</span>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Student Body</span>
                <div class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_students'] ?? 0) }}</div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100">
                <span class="text-[9px] text-slate-400">Active Learners</span>
            </div>
        </div>

        <div class="stat-card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center">
                    <i class="fas fa-book-open text-rose-600 text-xl"></i>
                </div>
                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-full">Active</span>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Subjects</span>
                <div class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_subjects'] ?? 0) }}</div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100">
                <span class="text-[9px] text-slate-400">Across All Divisions</span>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <!-- Toolbar -->
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-gradient-to-r from-white to-slate-50/50">
            <div class="flex items-center gap-4">
                <div class="w-1 h-8 bg-indigo-500 rounded-full"></div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Departmental Overview</h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">Manage enrollment across all departments</p>
                </div>
                <div class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider rounded-full ml-4">
                    {{ $departments->count() }} Departments
                </div>
            </div>
            
            <div class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                <input type="text" id="deptSearch" placeholder="Search by department name or code..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="deptTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="ps-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Faculty</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Students</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Subjects</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Classes</th>
                        <th class="pe-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($departments as $department)
                    @php
                        $facultyCount = $department->users->where('role_id', 2)->count();
                        $studentCount = $department->users->where('role_id', 3)->count();
                        $subjectCount = $department->subjects->count();
                        $classCount = $department->majors->sum(fn($m) => $m->classes->count());
                    @endphp
                    <tr class="department-row transition-colors" data-dept-name="{{ strtolower($department->department_name) }}">
                        <td class="ps-8 py-6">
                            <span class="text-[11px] font-bold text-slate-300">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex flex-col">
                                <div class="text-[15px] font-bold text-slate-900">{{ $department->department_name }}</div>
                                <div class="text-[9px] font-mono text-slate-400 mt-0.5">DEPT-{{ str_pad($department->id, 3, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-flex items-center justify-center min-w-[40px] px-3 py-1.5 rounded-full {{ $facultyCount > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-400' }} text-[11px] font-bold">
                                {{ $facultyCount }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-flex items-center justify-center min-w-[40px] px-3 py-1.5 rounded-full {{ $studentCount > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-400' }} text-[11px] font-bold">
                                {{ $studentCount }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-flex items-center justify-center min-w-[40px] px-3 py-1.5 rounded-full {{ $subjectCount > 0 ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-400' }} text-[11px] font-bold">
                                {{ $subjectCount }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-flex items-center justify-center min-w-[40px] px-3 py-1.5 rounded-full {{ $classCount > 0 ? 'bg-sky-50 text-sky-700' : 'bg-slate-100 text-slate-400' }} text-[11px] font-bold">
                                {{ $classCount }}
                            </span>
                        </td>
                        <td class="pe-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.enrollments.statistics', $department->id) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-all" title="Statistics">
                                    <i class="fas fa-chart-line text-xs"></i>
                                </a>
                                <a href="{{ route('admin.enrollments.manage', $department->id) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm">
                                    <i class="fas fa-users-gear text-[9px]"></i> Manage
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                    <i class="fas fa-building text-3xl text-slate-300"></i>
                                </div>
                                <p class="text-[13px] font-bold text-slate-500">No departments found</p>
                                <p class="text-[10px] text-slate-400 mt-1">Create a department to start managing enrollments</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="px-8 py-4 border-t border-slate-100 bg-slate-50/30">
            <div class="flex items-center justify-between">
                <p class="text-[10px] text-slate-400" id="footerCount">Showing {{ $departments->count() }} departments</p>
                <div class="flex gap-1">
                    <button class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all disabled:opacity-50">
                        <i class="fas fa-chevron-left text-[9px]"></i>
                    </button>
                    <button class="w-7 h-7 rounded-lg bg-indigo-600 text-white text-[10px] font-bold">1</button>
                    <button class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                        <i class="fas fa-chevron-right text-[9px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('deptSearch')?.addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        let visibleCount = 0;
        document.querySelectorAll('#deptTable tbody tr').forEach(row => {
            const deptName = row.querySelector('.text-\\[15px\\]')?.textContent.toLowerCase() || '';
            const deptCode = row.querySelector('.font-mono')?.textContent.toLowerCase() || '';
            const matches = deptName.includes(query) || deptCode.includes(query);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });
        
        const footerText = document.getElementById('footerCount');
        if (footerText) {
            const total = document.querySelectorAll('#deptTable tbody tr').length;
            footerText.textContent = `Showing ${visibleCount} of ${total} departments`;
        }
    });
</script>
@endsection