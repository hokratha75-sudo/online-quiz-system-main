@extends('layouts.admin')

@section('title', $department->department_name . ' - Department Detail')

@section('content')

    @if(session('success'))
    <div class="fixed top-4 right-4 z-[9999] success-alert w-[360px] bg-white border border-emerald-100 rounded-2xl p-3.5 flex items-center justify-between shadow-xl overflow-hidden transition-all duration-300">
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100/50">
                <i class="fas fa-check text-emerald-500 text-xs"></i>
            </div>
            <div>
                <h4 class="text-[13px] font-bold text-slate-900 leading-tight">Action Successful</h4>
                <p class="text-[11px] font-medium text-slate-500">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="group relative w-10 h-10 rounded-full bg-green-100 border border-green-600 border border-green-500 text-green-600 hover:bg-green-200 focus:outline-none flex items-center justify-center" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-sm "></i>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="fixed top-4 right-4 z-[9999] error-alert w-[360px] bg-white border border-rose-100 rounded-2xl p-3.5 flex items-center justify-between shadow-xl overflow-hidden transition-all duration-300">
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-full bg-rose-50 flex items-center justify-center shrink-0 border border-rose-100/50">
                <i class="fas fa-exclamation-triangle text-rose-500 text-xs"></i>
            </div>
            <div>
                <h4 class="text-[13px] font-bold text-slate-900 leading-tight">Action Failed</h4>
                <p class="text-[11px] font-medium text-slate-500">{{ $errors->first() }}</p>
            </div>
        </div>
        <button type="button" class="group relative w-10 h-10 rounded-full bg-red-100 border border-red-500 text-red-600 hover:bg-red-200 focus:outline-none flex items-center justify-center" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
    @endif

<div class="max-w-full mx-auto p-6 md:p-10 font-inter text-slate-900 bg-slate-50/50 min-h-screen">
    
    <!-- Elegant Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 border-b border-slate-200 pb-10">
        <div>
            <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-[0.25em] mb-3">
                <i class="fas fa-building text-[9px] mr-1"></i> Department Hub
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">{{ $department->department_name }}</h1>
            <p class="text-sm text-slate-500 mt-2 max-w-2xl leading-relaxed uppercase tracking-widest text-[11px]">CODE: {{ $department->code ?? 'N/A' }} | Managing academic structures and offerings</p>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.departments.index') }}" class="h-10 px-6 bg-white border border-slate-200 text-slate-600 hover:text-indigo-600 hover:border-indigo-100 transition-all text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 shadow-sm rounded-2xl">
                <i class="fas fa-arrow-left text-[9px]"></i> Directory
            </a>
            <button data-bs-toggle="modal" data-bs-target="#editDeptModal" class="h-10 px-6 bg-indigo-600 hover:bg-indigo-700 text-white transition-all text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 shadow-sm rounded-2xl focus:outline-none focus:ring-0 border-none">
                <i class="fas fa-edit text-[9px]"></i> Edit Dept
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white border border-slate-200 p-6 flex items-center gap-5 relative overflow-hidden group shadow-sm rounded-[24px]">
            <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-50/50 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0 border border-indigo-100 relative z-10 rounded-2xl">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="relative z-10">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Majors</div>
                <div class="text-3xl font-bold text-slate-900 tracking-tight">{{ $department->majors_count }}</div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6 flex items-center gap-5 relative overflow-hidden group shadow-sm rounded-[24px]">
            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50/50 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 border border-emerald-100 relative z-10 rounded-2xl">
                <i class="fas fa-chalkboard"></i>
            </div>
            <div class="relative z-10">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Active Classes</div>
                <div class="text-3xl font-bold text-slate-900 tracking-tight">{{ $department->classes_count }}</div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 p-6 flex items-center gap-5 relative overflow-hidden group shadow-sm rounded-[24px]">
            <div class="absolute right-0 top-0 w-32 h-32 bg-amber-50/50 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="w-14 h-14 bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0 border border-amber-100 relative z-10 rounded-2xl">
                <i class="fas fa-book"></i>
            </div>
            <div class="relative z-10">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Course Catalog</div>
                <div class="text-3xl font-bold text-slate-900 tracking-tight">{{ $department->subjects_count }}</div>
            </div>
        </div>
    </div>

    <!-- Major Structures -->
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
            <span class="w-1.5 h-4 bg-indigo-600 inline-block"></span> Academic Structures
        </h2>
    </div>

    <div class="space-y-8">
        @forelse($department->majors as $major)
            <div class="bg-white border border-slate-200 overflow-hidden shadow-sm rounded-[24px]">
                <!-- Major Header -->
                <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50/50">
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 bg-indigo-600 text-white flex items-center justify-center text-lg shrink-0 shadow-md shadow-indigo-600/20 rounded-2xl">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1.5">
                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[9px] font-bold uppercase tracking-widest border border-indigo-100 rounded-lg">{{ $major->code }}</span>
                            </div>
                            <a href="{{ route('admin.majors.show', $major->id) }}" class="text-xl font-bold text-slate-900 hover:text-indigo-600 transition-colors tracking-tight">{{ $major->name }}</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 rounded-xl">
                            <i class="fas fa-chalkboard text-slate-400"></i> {{ $major->classes->count() }} Classes
                        </div>
                        <div class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 rounded-xl">
                            <i class="fas fa-book text-slate-400"></i> {{ $major->subjects->count() }} Courses
                        </div>
                    </div>
                </div>

                <!-- Major Content (Classes and Courses Grid) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
                    
                    <!-- Classes Column -->
                    <div class="p-6 md:p-8">
                        <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <span class="w-1 h-3 bg-emerald-500"></span> Associated Classes
                        </h3>
                        
                        <div class="space-y-3">
                            @forelse($major->classes as $class)
                                <div class="group flex items-center justify-between p-4 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all bg-white rounded-2xl">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-9 h-9 bg-slate-50 text-slate-400 group-hover:text-emerald-500 group-hover:bg-emerald-50 flex items-center justify-center text-xs transition-colors border border-slate-100 group-hover:border-emerald-100 rounded-xl">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.classes.show', $class->id) }}" class="text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors uppercase tracking-tight block mb-0.5">{{ $class->name }}</a>
                                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">CODE: {{ $class->code }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 text-right">
                                        @if($class->academic_year)
                                            <span class="px-2 py-1 bg-slate-50 text-slate-500 border border-slate-200 text-[9px] font-bold uppercase tracking-widest rounded-md">{{ $class->academic_year }}</span>
                                        @endif
                                        <span class="px-2 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 text-[9px] font-bold uppercase tracking-widest flex items-center gap-1.5 min-w-[70px] justify-center rounded-md">
                                            <i class="fas fa-user-graduate text-[8px]"></i> {{ $class->students_count }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 border border-dashed border-slate-200 bg-slate-50/50 rounded-2xl">
                                    <i class="fas fa-users-slash text-slate-300 text-2xl mb-3"></i>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">No classes assigned</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Courses Column -->
                    <div class="p-6 md:p-8 bg-slate-50/20">
                        <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <span class="w-1 h-3 bg-amber-500"></span> Core Curriculum
                        </h3>
                        
                        <div class="space-y-3">
                            @forelse($major->subjects as $subject)
                                <div class="group flex items-center justify-between p-4 border border-slate-100 hover:border-amber-200 hover:bg-amber-50/30 transition-all bg-white rounded-2xl">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-9 h-9 bg-slate-50 text-slate-400 group-hover:text-amber-500 group-hover:bg-amber-50 flex items-center justify-center text-xs transition-colors border border-slate-100 group-hover:border-amber-100 rounded-xl">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.subjects.show', $subject->id) }}" class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition-colors uppercase tracking-tight block mb-0.5">{{ $subject->name }}</a>
                                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">CODE: {{ $subject->code }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="px-2 py-1 bg-amber-50 text-amber-600 border border-amber-100 text-[9px] font-bold uppercase tracking-widest min-w-[70px] text-center rounded-md">{{ $subject->credits }} Credits</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 border border-dashed border-slate-200 bg-white rounded-2xl">
                                    <i class="fas fa-book-open text-slate-300 text-2xl mb-3"></i>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">No courses assigned</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="text-center py-24 border border-dashed border-slate-300 bg-white shadow-sm rounded-[24px]">
                <div class="w-16 h-16 bg-slate-50 text-slate-300 flex items-center justify-center text-2xl mx-auto mb-5 border border-slate-200 rounded-2xl">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight mb-2">No Academic Structure</h3>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-8">This department hasn't been assigned any majors yet.</p>
                <a href="{{ route('admin.majors.index', ['tab' => 'majors', 'add' => 1, 'department_id' => $department->id]) }}" class="inline-flex h-10 items-center px-8 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold uppercase tracking-[0.15em] transition-all shadow-lg shadow-indigo-600/20 rounded-2xl">
                    <i class="fas fa-plus text-[9px] mr-2"></i> Add First Major
                </a>
            </div>
        @endforelse
    </div>
</div>

<!-- Edit Department Modal -->
<div class="modal fade" id="editDeptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-[24px] border-0 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-[#5f60ef] to-[#9a4ce7] px-6 py-4 flex items-center justify-between">
                <h5 class="text-xl font-bold text-white tracking-tight flex items-center gap-3">
                    <i class="fas fa-edit text-white text-sm"></i>
                    <span class="text-lg">Edit Department</span>
                </h5>
                <button type="button" class="group relative w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 border-none text-white flex items-center justify-center focus:outline-none" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="divide-y divide-slate-100">
                @csrf
                @method('PUT')
                
                <div class="space-y-5 p-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Department Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="department_name" value="{{ old('department_name', $department->department_name) }}" required placeholder="e.g. Faculty of Science"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Code <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <input type="text" name="code" value="{{ old('code', $department->code) }}" placeholder="e.g. DPT001"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none uppercase">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <textarea name="description" rows="3" placeholder="Brief summary of the department..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">{{ old('description', $department->description) }}</textarea>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 p-6">
                    <button type="button" class="bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80 px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 shadow-sm border-none">Update Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // SUCCESS ALERT
        const successAlert = document.querySelector(".success-alert");
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.add("opacity-0", "translate-x-5");
                setTimeout(() => { successAlert.remove(); }, 300);
            }, 5000);
        }

        // ERROR ALERT
        const errorAlert = document.querySelector(".error-alert");
        if (errorAlert) {
            setTimeout(() => {
                errorAlert.classList.add("opacity-0", "translate-x-5");
                setTimeout(() => { errorAlert.remove(); }, 300);
            }, 5000);
        }
    });
</script>
@endsection
