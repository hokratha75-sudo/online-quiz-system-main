@extends('layouts.admin')

@section('topbar-title', 'Create Assessment')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('quizzes.index') }}" class="w-10 h-10 rounded-2xl border border-slate-100 bg-white flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight uppercase">Create Quiz</h1>
                <p class="text-xs font-bold text-indigo-600 mt-1 uppercase tracking-widest">Define the foundational configuration settings</p>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-circle text-rose-500 text-base"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <div class="max-w-3xl">
        <form action="{{ route('quizzes.store') }}" method="POST" class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden">
            @csrf
            
            <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex justify-center items-center shadow-sm">
                    <i class="fas fa-plus text-[10px]"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-900 tracking-widest uppercase">Initial Configuration</h3>
            </div>

            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-xs font-bold tracking-widest text-indigo-600 uppercase mb-3">Quiz Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. Midterm Examination"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm placeholder:text-slate-400">
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-widest text-indigo-600 uppercase mb-3">Description</label>
                    <textarea name="description" rows="4" placeholder="Brief instructions for students..."
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm resize-none placeholder:text-slate-400"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Subject <span class="text-rose-500">*</span></label>
                        <select name="subject_id" required 
                                class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3.5 top-[35px] text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status <span class="text-rose-500">*</span></label>
                        <select name="status" required 
                                class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                            <option value="draft">Draft (Hidden)</option>
                            <option value="published" selected>Published (Active)</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3.5 top-[35px] text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Time Limit <span class="text-slate-400 font-normal">(Min)</span> <span class="text-rose-500">*</span></label>
                        <input type="number" name="time_limit" value="30" required min="1" 
                               class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pass Mark (%) <span class="text-rose-500">*</span></label>
                        <input type="number" name="pass_percentage" value="60" required min="0" max="100" 
                               class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <label class="p-6 border border-slate-100 rounded-2xl bg-slate-50/30 flex items-center gap-5 hover:border-indigo-500 hover:bg-slate-50 transition-all cursor-pointer group mt-4 shadow-sm">
                    <div class="pt-0.5">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="shuffle_questions" id="shuffleCheck" value="1" class="sr-only peer" checked>
                            <div class="w-10 h-6 bg-slate-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest group-hover:text-indigo-600 transition-all">Shuffle Questions</h4>
                        <p class="text-[10px] font-bold text-slate-500 uppercase mt-0.5 leading-relaxed tracking-tight">Randomize the order of inquiries</p>
                    </div>
                </label>

                <div class="rounded-2xl border border-amber-100 bg-amber-50/60 p-5 space-y-4">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-calendar-alt text-amber-500 text-xs"></i>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Quiz Availability</h4>
                        <span class="text-[10px] text-slate-400 font-medium ml-auto">Optional</span>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            <i class="fas fa-door-open text-emerald-500 mr-1"></i> Opens At
                        </label>
                        <input type="datetime-local" name="opened_at" value="{{ old('opened_at') }}"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            <i class="fas fa-door-closed text-rose-500 mr-1"></i> Closes At
                        </label>
                        <input type="datetime-local" name="closed_at" value="{{ old('closed_at') }}"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-400/30 focus:border-rose-400 transition-all shadow-sm">
                    </div>
                    <p class="text-[10px] text-slate-400 leading-relaxed">
                        <i class="fas fa-info-circle mr-0.5"></i>
                        Students can only attempt this quiz within the set time window. Leave blank for no restriction.
                    </p>
                </div>
            </div>

            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                <p class="text-xs font-medium text-slate-500">
                    <i class="fas fa-info-circle text-indigo-400 mr-1"></i> You can add questions in the next step.
                </p>
                <button type="submit" class="bg-indigo-600 hover:bg-slate-900 text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-lg flex items-center gap-2 {{ $subjects->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $subjects->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-arrow-right"></i> Continue
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
