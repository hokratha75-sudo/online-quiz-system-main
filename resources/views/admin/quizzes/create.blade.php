@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-20 pt-12">
    <div class="max-w-4xl mx-auto px-6">
        
        <!-- Progress Header -->
        <div class="flex items-center justify-between mb-12">
            <div class="flex items-center gap-6">
                <a href="{{ route('quizzes.index') }}" class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm group">
                    <i class="fas fa-chevron-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase italic leading-none">Create Module</h1>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Step 01</span>
                        <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Architecting Assessment</span>
                    </div>
                </div>
            </div>
            
            <!-- Step Indicators -->
            <div class="hidden md:flex items-center gap-3">
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-500/20">
                    <span class="w-5 h-5 rounded-lg bg-white/20 flex items-center justify-center text-[10px] font-black italic">01</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Config</span>
                </div>
                <div class="w-8 h-px bg-slate-200"></div>
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-400">
                    <span class="w-5 h-5 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-black italic">02</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Questions</span>
                </div>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-8 p-5 bg-rose-50 border border-rose-100 rounded-3xl flex items-start gap-4 animate-shake">
            <div class="w-10 h-10 rounded-2xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-rose-500/20">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="text-xs font-black text-rose-900 uppercase tracking-widest mb-1">Configuration Error</h4>
                <p class="text-sm font-semibold text-rose-700/80 leading-relaxed italic">{{ $errors->first() }}</p>
            </div>
        </div>
        @endif

        <!-- Form Interface -->
        <form action="{{ route('quizzes.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="bg-white rounded-[40px] border border-slate-200/60 shadow-xl shadow-slate-200/40 overflow-hidden">
                <div class="p-8 md:p-14 space-y-10">
                    
                    <!-- Section Header -->
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-8">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl border border-indigo-100/50">
                            <i class="far fa-compass"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight leading-none mb-1">Primary Metadata</h3>
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Define the unique identity of this assessment.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-10">
                        <!-- Left Column: Text Data -->
                        <div class="space-y-10 lg:pr-6">
                            <div class="group">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 group-hover:text-indigo-500 transition-colors">Assessment Title <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" required placeholder="e.g. Advanced Quantum Mechanics" 
                                       class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-[20px] p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all placeholder:text-slate-300">
                            </div>

                            <div class="group">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 group-hover:text-indigo-500 transition-colors">Context Description</label>
                                <textarea name="description" rows="5" placeholder="Operational instructions or brief summary..." 
                                          class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-[20px] p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all placeholder:text-slate-300 resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Right Column: Settings -->
                        <div class="space-y-10">
                            <div class="relative group">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 group-hover:text-indigo-500 transition-colors">Unit Subject <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="subject_id" required class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-[20px] p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                                        <option value="">-- Select Deployment Subject --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-hover:text-indigo-400 transition-colors"></i>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div class="group">
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 group-hover:text-indigo-500 transition-colors">Time Limit (Min) <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" name="time_limit" value="30" required min="1" 
                                               class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-[20px] p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all tabular-nums">
                                        <i class="far fa-clock absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-hover:text-indigo-400"></i>
                                    </div>
                                </div>
                                <div class="group">
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 group-hover:text-indigo-500 transition-colors">Pass Rate (%) <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" name="pass_percentage" value="60" min="0" max="100" 
                                               class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-[20px] p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all tabular-nums">
                                        <i class="far fa-star absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-hover:text-indigo-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="relative group">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 group-hover:text-indigo-500 transition-colors">Operational Status</label>
                                <div class="relative">
                                    <select name="status" class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-[20px] p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                                        <option value="draft">STAGING (Save as Draft)</option>
                                        <option value="published" selected>PRODUCTION (Publish Live)</option>
                                    </select>
                                    <i class="fas fa-shuttle-space absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-hover:text-indigo-400 transition-colors"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Options -->
                    <div class="pt-10 border-t border-slate-100">
                        <label class="relative flex flex-col md:flex-row items-center gap-8 p-10 bg-slate-50/50 hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-200/50 rounded-[32px] transition-all cursor-pointer group overflow-hidden">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                            
                            <div class="shrink-0">
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="shuffle_questions" id="shuffleCheck" value="1" class="sr-only peer" checked>
                                    <div class="w-16 h-9 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-lg font-black text-slate-900 uppercase tracking-tight group-hover:text-indigo-700 transition-colors">Entropic Randomization Engine</h4>
                                <p class="text-sm font-semibold text-slate-500 mt-1 leading-relaxed antialiased">Activate full-spectrum randomization of questions and choice vectors to neutralize integrity vulnerabilities.</p>
                            </div>
                        </label>
                    </div>

                </div>
            </div>

            <!-- Global Footer Actions -->
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest hidden md:block">
                    <i class="fas fa-lock text-indigo-300 mr-2"></i> All configurations are encrypted during transit.
                </p>
                <button type="submit" class="h-20 px-12 bg-indigo-600 hover:bg-slate-900 text-white rounded-[24px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-indigo-500/20 transition-all flex items-center gap-6 group active:scale-95 {{ $subjects->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $subjects->isEmpty() ? 'disabled' : '' }}>
                    Proceed to Question Architect <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake { animation: shake 0.4s ease-in-out 0s 2; }
</style>
@endsection
