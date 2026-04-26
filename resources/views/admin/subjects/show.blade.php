@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter text-slate-900">
    
        <!-- Header: Subject Details -->
    <header class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-[24px] bg-indigo-600 text-white flex items-center justify-center text-2xl shadow-xl shadow-indigo-600/30">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <div class="flex items-center gap-3 mb-2">
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-indigo-100">SUBJECT ID #{{ $subject->id }}</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight leading-none uppercase">{{ $subject->subject_name }}</h1>
                <div class="mt-2 flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    @if($subject->department)
                        <i class="fas fa-building text-indigo-500"></i>
                                                <a href="{{ route('admin.departments.show', $subject->department->id) }}" class="hover:text-indigo-600 transition-colors">{{ $subject->department->department_name }} Department</a>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.subjects.index') }}" class="h-12 px-8 bg-white border border-slate-100 text-slate-500 hover:text-indigo-600 hover:border-indigo-100 rounded-2xl text-[10px] font-bold uppercase tracking-widest flex items-center gap-3 transition-all shadow-sm active:scale-95">
                <i class="fas fa-arrow-left text-[8px]"></i> Return to Directory
            </a>
        </div>
    </header>

    <!-- Content Architecture Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Primary Metrics & Profile -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm">
                                    <i class="fas fa-info-circle text-indigo-500"></i> Subject Information
                </h3>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between py-4 border-b border-slate-50">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Created By</span>
                        <span class="text-xs font-bold text-slate-900 uppercase leading-none">{{ $subject->creator->username ?? 'SYSTEM-GEN' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-4 border-b border-slate-50">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Status</span>
                                                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-bold uppercase tracking-widest border border-emerald-100">Active</span>
                    </div>
                    <div class="flex items-center justify-between py-4 border-b border-slate-50">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Quizzes Count</span>
                        <span class="text-xs font-bold text-indigo-600 tabular-nums leading-none">{{ $subject->quizzes->count() }} Quizzes</span>
                    </div>
                    <div class="flex items-center justify-between py-4">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Created Date</span>
                        <span class="text-xs font-bold text-slate-900 uppercase leading-none">{{ $subject->created_at?->format('d M, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-600 rounded-[32px] p-8 text-white shadow-xl shadow-indigo-600/20 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:rotate-12 transition-transform duration-500">
                    <i class="fas fa-graduation-cap text-6xl"></i>
                </div>
                                <h4 class="text-[10px] font-bold text-indigo-100 uppercase tracking-[0.2em] mb-8 relative z-10">Subject Summary</h4>
                <p class="text-2xl font-bold leading-tight uppercase tracking-tight relative z-10 mb-2">Ready for <br/> Enrollment</p>
                <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest relative z-10 opacity-60">Full course subject information and assessments.</p>
            </div>
        </div>

        <!-- Analytical List: Quizzes -->
        <div class="lg:col-span-2 bg-white rounded-[32px] p-10 border border-slate-100 shadow-sm overflow-hidden">
            <header class="flex items-center justify-between mb-12">
                                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest flex items-center gap-3">
                    <i class="fas fa-question-circle text-indigo-500"></i> Assessment Quizzes
                </h3>
                <span class="px-4 py-2 bg-slate-50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-widest border border-slate-100">{{ $subject->quizzes->count() }} Quizzes Found</span>
            </header>

            <div class="space-y-6">
                @forelse($subject->quizzes as $quiz)
                <div class="group flex items-center justify-between p-6 bg-slate-50 hover:bg-white border border-transparent hover:border-indigo-100 rounded-[24px] transition-all duration-300">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 text-indigo-600 flex items-center justify-center shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                            <i class="fas fa-scroll text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{{ $quiz->title }}</h4>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest tabular-nums leading-none">
                                                                        <i class="far fa-clock me-1 text-indigo-400"></i> Created: {{ $quiz->created_at?->format('d M Y') }}
                                </span>
                                @if($quiz->creator)
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">
                                    <i class="far fa-user me-1 text-indigo-400"></i> {{ $quiz->creator->username }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1.5 rounded-lg {{ $quiz->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-400 border-slate-200' }} text-[9px] font-bold uppercase tracking-widest border shadow-sm transition-all tabular-nums">
                            {{ $quiz->status }}
                        </span>
                        <a href="{{ route('quizzes.show', $quiz->quiz_id) }}" class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all flex items-center justify-center active:scale-95 shadow-sm">
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="py-24 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-[32px] flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-inbox text-3xl text-slate-200"></i>
                    </div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No quizzes found for this subject.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
