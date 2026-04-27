@extends('layouts.admin')

@section('content')
<div class="user-management-heading mb-5 px-2">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-1" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">{{ $dashboardTitle }}</h1>
            <p class="text-slate-500 font-medium small">Manage and explore all academic courses in your curriculum.</p>
        </div>
        <div class="d-flex flex-column flex-md-row gap-3">
            <form action="{{ route('courses.index') }}" method="GET" class="position-relative group">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search name or code..." 
                       class="form-control rounded-xl px-10 py-2.5 border-0 shadow-sm bg-white text-xs font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-300"
                       style="min-width: 320px;">
                <div class="position-absolute start-3 top-50 translate-middle-y text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-search text-xs"></i>
                </div>
                @if(request('search'))
                <a href="{{ route('courses.index') }}" class="position-absolute end-3 top-50 translate-middle-y text-slate-300 hover:text-slate-500">
                    <i class="fas fa-times-circle text-xs"></i>
                </a>
                @else
                <div class="position-absolute end-3 top-50 translate-middle-y text-[9px] font-black text-slate-300 uppercase tracking-tighter opacity-0 group-focus-within:opacity-100 transition-opacity">
                    Press Enter
                </div>
                @endif
            </form>
            
            @if(Auth::user()->role_id == 1)
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-primary rounded-xl px-4 py-2.5 fw-bold shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> Add Course
            </a>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($subjects as $index => $course)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 bg-white rounded-[28px] shadow-sm hover-shadow-xl transition-all duration-300 relative overflow-hidden group border-1 border-slate-100">
            <!-- Header Elements: Ultra Compact -->
            <div class="p-3 pb-0 d-flex justify-content-between align-items-center">
                <div class="text-[9px] font-black text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                    #{{ $subjects->firstItem() + $index }}
                </div>
                <div class="bg-emerald-50 text-emerald-600 text-[9px] font-black px-2 py-1 rounded-md border border-emerald-100/50 d-flex align-items-center gap-1">
                    <span class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></span>
                    Active
                </div>
            </div>

            <div class="card-body p-4 pt-1 text-center">
                <!-- Course Icon: Scaled Down -->
                <div class="mb-3 d-flex justify-content-center">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-indigo-100 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>

                <!-- Course Title & Badges -->
                <h5 class="text-base font-black text-slate-900 mb-2 leading-snug truncate-2-lines min-h-[40px]">{{ $course->subject_name }}</h5>
                
                <div class="d-flex justify-content-center gap-1.5 mb-3">
                    <span class="badge bg-indigo-50 text-indigo-600 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border border-indigo-100">
                        {{ $course->code ?? 'SUB-'.str_pad($course->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="badge bg-slate-50 text-slate-400 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border border-slate-100">
                        {{ $course->credits }} Credits
                    </span>
                </div>

                <!-- Info Grid: More Compact -->
                <div class="bg-slate-50/50 rounded-2xl p-3 border border-slate-100 space-y-2 mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-building text-[10px] text-slate-400"></i>
                            <span class="text-[10px] font-bold text-slate-600 truncate max-w-[120px]">{{ $course->major->department->department_name ?? 'General' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-users text-[10px] text-indigo-500"></i>
                            <span class="text-[10px] font-black text-slate-900">{{ $course->classes->sum('students_count') }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 border-t border-slate-100 pt-2">
                        <i class="fas fa-graduation-cap text-[10px] text-slate-400"></i>
                        <span class="text-[10px] font-bold text-slate-500 truncate">{{ $course->major->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Actions: Tighter -->
            <div class="px-4 pb-4 pt-0 d-flex gap-2">
                @if(Auth::user()->role_id == 1)
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-light-square" title="Manage Subjects">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.subjects.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light-square text-rose-500" title="Delete Course">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
                @endif
                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-indigo-square flex-1">
                    View <i class="fas fa-arrow-right ms-1 text-[8px]"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 py-5">
        <div class="card border-0 bg-white rounded-[40px] shadow-sm p-5 text-center border-1 border-slate-100">
            <div class="mb-4">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mx-auto border border-slate-100">
                    <i class="fas fa-book-reader fa-3x"></i>
                </div>
            </div>
            <h4 class="text-2xl font-black text-slate-900 mb-2">No Courses Assigned Yet</h4>
            <p class="text-slate-500 font-medium mx-auto mb-5 max-w-lg leading-relaxed">
                You don't have any subjects or courses assigned to your profile at the moment. 
            </p>
            @if(Auth::user()->role_id == 1)
            <div class="d-flex justify-content-center">
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-primary px-5 py-3 rounded-2xl fw-black shadow-lg">
                    <i class="fas fa-plus-circle me-2"></i> Manage Subjects
                </a>
            </div>
            @endif
        </div>
    </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $subjects->links() }}
</div>

<style>
    .btn-light-square {
        background: white;
        border: 1px solid #f1f5f9;
        color: #64748b;
        border-radius: 14px;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-light-square:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #0f172a;
    }
    .btn-indigo-square {
        background: #4f46e5;
        color: white;
        border-radius: 14px;
        height: 38px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }
    .btn-indigo-square:hover {
        background: #4338ca;
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3);
        color: white;
    }
    .truncate-2-lines {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .hover-shadow-xl:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px -5px rgba(0,0,0,0.08) !important;
    }
</style>
@endsection
