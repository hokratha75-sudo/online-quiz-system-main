@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter text-slate-900">
    
    <!-- Header Section -->
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-8">
        <div>
            <h1 class="text-2xl md:text-[28px] font-bold text-slate-900 tracking-tight">Academic Enrollments</h1>
            <p class="text-[14px] font-medium text-slate-500 mt-1.5">Assign which teachers and students have access to which departments.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="bg-indigo-50/50 border border-indigo-100/50 px-4 py-2.5 rounded-xl text-[13px] font-semibold text-indigo-600 flex items-center gap-2">
                <i class="fas fa-lock text-xs text-indigo-400"></i> Admin Area
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-8 bg-white border border-emerald-100 rounded-[20px] p-4 flex items-center justify-between shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden transition-all duration-300">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>
        <div class="flex items-center gap-4 ml-2">
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100/50">
                <i class="fas fa-check text-emerald-500 text-sm"></i>
            </div>
            <div>
                <h4 class="text-[14px] font-bold text-slate-900 tracking-tight leading-none mb-1">Action Successful</h4>
                <p class="text-[13px] font-medium text-slate-500">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="w-8 h-8 mr-1 rounded-full hover:bg-slate-50 flex items-center justify-center text-slate-400 transition-colors focus:outline-none" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
    @endif

    <!-- Global Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-[24px] p-8 border border-slate-100 shadow-sm group hover:shadow-md transition-all">
            <div class="flex justify-between items-center mb-6">
                <span class="text-sm font-semibold text-slate-600 uppercase tracking-wider">Total Departments</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fas fa-building text-sm"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold tracking-tight text-slate-900 tabular-nums leading-none">{{ $departments->count() }}</h3>
            <div class="mt-2 text-xs font-medium text-slate-400">Available Departments</div>
        </div>

        <div class="bg-white rounded-[24px] p-8 border border-slate-100 shadow-sm group hover:shadow-md transition-all">
            <div class="flex justify-between items-center mb-6">
                <span class="text-sm font-semibold text-slate-600 uppercase tracking-wider">Total Students</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fas fa-user-graduate text-sm"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold tracking-tight text-slate-900 tabular-nums leading-none">{{ $departments->sum(fn($d) => $d->users->where('role_id', 3)->count()) }}</h3>
            <div class="mt-2 text-xs font-medium text-slate-400">Currently Enrolled</div>
        </div>

        <div class="bg-white rounded-[24px] p-8 border border-slate-100 shadow-sm group hover:shadow-md transition-all">
            <div class="flex justify-between items-center mb-6">
                <span class="text-sm font-semibold text-slate-600 uppercase tracking-wider">Total Teachers</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fas fa-chalkboard-teacher text-sm"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold tracking-tight text-slate-900 tabular-nums leading-none">{{ $departments->sum(fn($d) => $d->users->where('role_id', 2)->count()) }}</h3>
            <div class="mt-2 text-xs font-medium text-slate-400">Assigned Teachers</div>
        </div>
    </div>

    <!-- Relationship Matrix Interface -->
    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden mb-12">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row items-center justify-between gap-6 bg-slate-50/50">
            <div>
                <h3 class="text-sm font-bold text-slate-900 leading-none">Department Access Control</h3>
                <p class="text-xs font-medium text-slate-500 mt-2">View and assign faculty and students for each department</p>
            </div>
            <div class="relative group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="deptSearch" placeholder="Search Departments..." 
                       class="h-11 pl-12 pr-6 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all w-64 placeholder:text-slate-400 shadow-sm">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="deptTable">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="ps-8 py-5 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Department Details</th>
                        <th class="px-6 py-5 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-center">Faculty Assigned</th>
                        <th class="px-6 py-5 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-center">Enrolled Students</th>
                        <th class="px-6 py-5 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-center">Total Subjects</th>
                        <th class="pe-8 py-5 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($departments as $department)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="ps-8 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors leading-none">{{ $department->department_name }}</span>
                                <span class="text-xs font-medium text-slate-500 mt-1">ID: {{ str_pad($department->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php $teachersCount = $department->users->where('role_id', 2)->count(); @endphp
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-teal-50 border border-teal-100 text-teal-700 shadow-sm text-xs font-semibold">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>{{ $teachersCount }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php $studentsCount = $department->users->where('role_id', 3)->count(); @endphp
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-cyan-50 border border-cyan-100 text-cyan-700 shadow-sm text-xs font-semibold">
                                <i class="fas fa-users"></i>
                                <span>{{ $studentsCount }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 shadow-sm text-xs font-semibold">
                                <i class="fas fa-layer-group"></i>
                                <span>{{ $department->subjects->count() }}</span>
                            </div>
                        </td>
                        <td class="pe-8 py-5 text-right">
                            <a href="{{ route('admin.enrollments.manage', $department->id) }}" class="inline-flex items-center h-10 px-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition-all shadow-md active:scale-95 gap-2">
                                <i class="fas fa-sliders-h text-indigo-200"></i> Manage Access
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-building text-3xl text-slate-200 mb-4"></i>
                                <p class="text-xs font-medium text-slate-500">No departments found in the system</p>
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
    // Modern Search Logic for Sectors
    document.getElementById('deptSearch').addEventListener('keyup', function() {
        const query = this.value.toUpperCase();
        document.querySelectorAll('#deptTable tbody tr').forEach(row => {
            const text = row.querySelector('td:first-child').textContent.toUpperCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
@endsection
