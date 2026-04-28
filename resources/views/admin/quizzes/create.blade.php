@extends('layouts.admin')

@section('topbar-title', 'Create Assessment')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter bg-slate-50/30 min-h-screen">
    
    <!-- Stepper Navigation -->
    <div class="flex items-center justify-start gap-12 mb-10 overflow-x-auto pb-4 scrollbar-hide">
        <div class="flex items-center gap-3 shrink-0">
            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-lg shadow-indigo-200">1</div>
            <span class="text-sm font-semibold text-slate-900 tracking-tight">Configuration</span>
        </div>
        <div class="h-px w-16 bg-slate-200 shrink-0"></div>
        <div class="flex items-center gap-3 shrink-0 opacity-40">
            <div class="w-8 h-8 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center text-xs font-bold">2</div>
            <span class="text-sm font-semibold text-slate-500 tracking-tight">Availability</span>
        </div>
        <div class="h-px w-16 bg-slate-200 shrink-0"></div>
        <div class="flex items-center gap-3 shrink-0 opacity-40">
            <div class="w-8 h-8 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center text-xs font-bold">3</div>
            <span class="text-sm font-semibold text-slate-500 tracking-tight">Questions</span>
        </div>
    </div>

    <!-- Header -->
    <div class="flex items-center gap-5 mb-8">
        <a href="{{ route('quizzes.index') }}" class="w-10 h-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Create quiz</h1>
            <p class="text-sm text-slate-500 mt-0.5">Define the foundational configuration settings</p>
        </div>
    </div>

    <form action="{{ route('quizzes.store') }}" method="POST" class="max-w-4xl pb-32">
        @csrf
        
        <div class="space-y-6">
            <!-- Card 1: Initial configuration -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <h3 class="text-[15px] font-bold text-slate-900 tracking-tight">Initial configuration</h3>
                </div>
                
                <div class="p-8 space-y-6">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Quiz title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="e.g. Midterm Examination"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all placeholder:text-slate-400">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <textarea name="description" rows="4" placeholder="Brief instructions for students..."
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all resize-none placeholder:text-slate-400"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Subject <span class="text-rose-500">*</span></label>
                            <select name="subject_id" required 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                                <option value="">-- Select subject --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-[42px] text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>

                        <div class="relative">
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Status <span class="text-rose-500">*</span></label>
                            <select name="status" required 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                                <option value="draft">Draft (Hidden)</option>
                                <option value="published" selected>Published (Active)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-[42px] text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Quiz settings -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                        <i class="fas fa-gear"></i>
                    </div>
                    <h3 class="text-[15px] font-bold text-slate-900 tracking-tight">Quiz settings</h3>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Time limit <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="time_limit" value="30" required min="1" 
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 px-2 py-0.5 bg-slate-200/50 rounded-md text-[10px] font-bold text-slate-500 uppercase">min</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Pass mark <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="pass_percentage" value="60" required min="0" max="100" 
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 px-2 py-0.5 bg-slate-200/50 rounded-md text-[10px] font-bold text-slate-500 uppercase">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between group">
                        <div>
                            <h4 class="text-[13px] font-bold text-slate-900 tracking-tight">Shuffle questions</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Randomize the order of questions for each student</p>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="shuffle_questions" id="shuffleCheck" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Quiz availability -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                            <i class="fas fa-calendar-days"></i>
                        </div>
                        <h3 class="text-[15px] font-bold text-slate-900 tracking-tight">Quiz availability</h3>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md uppercase tracking-wider">Optional</span>
                </div>
                
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Opens at</label>
                            <input type="datetime-local" name="opened_at" value="{{ old('opened_at') }}"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Closes at</label>
                            <input type="datetime-local" name="closed_at" value="{{ old('closed_at') }}"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all">
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200/50">
                        <i class="fas fa-info-circle text-slate-400 mt-0.5"></i>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Students can only attempt this quiz within the set time window. Leave blank for no restriction.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer Bar -->
        <div class="fixed bottom-0 right-0 left-0 md:left-[260px] bg-white border-t border-slate-200 px-8 py-4 flex items-center justify-between z-50 shadow-[0_-10px_20px_rgba(0,0,0,0.02)]">
            <p class="text-xs font-medium text-slate-500 hidden sm:flex items-center gap-2">
                <i class="fas fa-circle-info text-indigo-500"></i>
                You can add questions in the next step.
            </p>
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-slate-900 text-white px-10 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-3 group {{ $subjects->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $subjects->isEmpty() ? 'disabled' : '' }}>
                    <span>Continue</span>
                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
    </form>
</div>
</div>
@endsection
