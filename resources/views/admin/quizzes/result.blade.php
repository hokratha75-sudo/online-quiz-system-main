@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-20 font-inter">
    
    @php 
        $isStudent = auth()->user()->role_id == 3;
        $finalScore = $attempt->result->manual_score ?? $attempt->result->score;
        $passed = $attempt->result->passed;
    @endphp

    @if(!$attempt->result->is_published && $isStudent)
        <!-- Immersive Pending Review State: Compact -->
        <div class="relative overflow-hidden bg-indigo-950 pt-16 pb-28">
            <div class="max-w-2xl mx-auto px-6 relative z-10 text-center">
                <div class="w-20 h-20 bg-white/10 text-indigo-200 rounded-[28px] flex items-center justify-center mx-auto mb-8 text-3xl border border-white/5 shadow-2xl animate-pulse">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <h1 class="text-3xl font-bold text-white tracking-tight uppercase mb-4">Results Pending Audit</h1>
                <p class="text-indigo-200/60 text-xs font-bold leading-relaxed max-w-md mx-auto uppercase tracking-wide">
                    Your assessment vectors are currently being verified by the academic proctoring department. Access will be granted once certified.
                </p>
                <div class="mt-12">
                    <a href="{{ route('students.dashboard') }}" class="inline-flex items-center gap-3 bg-white text-indigo-600 px-8 py-3.5 rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-white/5">
                        <i class="fas fa-arrow-left"></i> Return to Terminal
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Header: Achievement Visual - Compact -->
        <div class="relative overflow-hidden {{ $passed ? 'bg-emerald-600' : 'bg-rose-600' }} pt-16 pb-28 transition-colors duration-700">
            <!-- Dynamic Background -->
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div class="absolute top-0 right-0 w-80 h-80 bg-white/20 blur-[80px] rounded-full translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-56 h-56 bg-black/20 blur-[60px] rounded-full -translate-x-1/2 translate-y-1/2"></div>
            </div>

            <div class="max-w-2xl mx-auto px-6 relative z-10 text-center text-white">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center mx-auto mb-6 text-2xl shadow-xl">
                    <i class="fas {{ $passed ? 'fa-award text-yellow-300' : 'fa-info-circle text-rose-100' }}"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight uppercase mb-2 leading-none">
                    {{ $passed ? 'EXCELLENCE CONFIRMED' : 'RETRY RECOMMENDED' }}
                </h1>
                <p class="text-white/60 text-[10px] font-bold uppercase tracking-[0.2em]">
                    {{ $passed ? 'AUTHORIZED DEPLOYMENT • VECTOR MATCH' : 'FAILSAFE TRIGGERED • BELOW THRESHOLD' }}
                </p>
            </div>
        </div>

        <!-- Result Container: Compact & High Density -->
        <div class="max-w-4xl mx-auto px-6 -mt-16 relative z-20">
            <div class="bg-white rounded-[40px] p-8 md:p-12 border border-slate-200/50 shadow-2xl overflow-hidden relative">
                
                <!-- Main Score Circle: Compact UI -->
                <div class="flex flex-col md:flex-row items-center gap-10 border-b border-slate-100 pb-10 mb-10">
                    <div class="relative w-32 h-32 shrink-0">
                        <svg class="w-full h-full -rotate-90 filter drop-shadow-sm" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="#f1f5f9" stroke-width="10" />
                            <circle cx="50" cy="50" r="45" fill="none" stroke="{{ $passed ? '#10b981' : '#f43f5e' }}" stroke-width="10" stroke-dasharray="283" stroke-dashoffset="{{ 283 - (283 * $finalScore / 100) }}" stroke-linecap="round" class="transition-all duration-[2000ms] delay-500 ease-out" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-bold text-slate-900 leading-none tabular-nums">{{ round($finalScore) }}<span class="text-xs text-slate-400 ml-0.5">%</span></span>
                        </div>
                    </div>

                    <div class="flex-grow text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg {{ $passed ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} text-[10px] font-bold uppercase tracking-widest mb-4 border border-current opacity-70">
                            <i class="fas {{ $passed ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $passed ? 'AUTHORIZED PASS' : 'SYNC THRESHOLD FAILED' }}
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight leading-none mb-3 uppercase">VALIDATION REPORT</h2>
                        <p class="text-xs font-bold text-slate-400 leading-relaxed max-w-sm uppercase tracking-tight">
                            Your performance vector for <strong>{{ $attempt->quiz->title }}</strong> has been logged in the institutional ledger.
                        </p>
                    </div>
                </div>

                <!-- Stat Grid: Compact Design -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center hover:bg-white hover:border-indigo-500/20 transition-all shadow-sm">
                        <i class="far fa-clock text-indigo-600 text-sm mb-3"></i>
                        @php
                            $diff = \Carbon\Carbon::parse($attempt->started_at)->diff($attempt->completed_at);
                            $timeStr = ($diff->i > 0 ? $diff->i . 'm ' : '') . $diff->s . 's';
                        @endphp
                        <span class="text-lg font-bold text-slate-900 tabular-nums">{{ $timeStr }}</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Period</span>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center hover:bg-white hover:border-indigo-500/20 transition-all shadow-sm">
                        <i class="far fa-calendar text-indigo-600 text-sm mb-3"></i>
                        <span class="text-lg font-bold text-slate-900 tabular-nums">{{ $attempt->completed_at->format('d M') }}</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Stamp</span>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center text-center hover:bg-white hover:border-indigo-500/20 transition-all shadow-sm">
                        <i class="far fa-shield text-{{ $attempt->violations > 0 ? 'rose' : 'emerald' }}-500 text-sm mb-3"></i>
                        <span class="text-lg font-bold {{ $attempt->violations > 0 ? 'text-rose-500' : 'text-emerald-500' }}">{{ $attempt->violations ? $attempt->violations : 'CLEAN' }}</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Trust</span>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center hover:bg-white hover:border-indigo-500/20 transition-all shadow-sm">
                        <i class="far fa-star text-indigo-600 text-sm mb-3"></i>
                        <span class="text-lg font-bold text-slate-900 tabular-nums">{{ $attempt->quiz->pass_percentage }}%</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Aim</span>
                    </div>
                </div>

                @if($attempt->result->teacher_feedback)
                <!-- Professional Feedback Block: Compact -->
                <div class="mb-10 p-8 bg-indigo-50 rounded-[32px] border border-indigo-100 relative overflow-hidden group">
                    <div class="absolute right-0 bottom-0 opacity-5 group-hover:scale-110 transition-transform">
                        <i class="fas fa-quote-right text-6xl text-indigo-600 -mr-4 -mb-4"></i>
                    </div>
                    <div class="flex items-start gap-6 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-indigo-600/20">
                            <i class="fas fa-comment-dots text-sm"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mb-2">Auditor Feedback</div>
                            <p class="text-slate-700 text-sm font-bold leading-relaxed uppercase tracking-tight opacity-80">"{{ $attempt->result->teacher_feedback }}"</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Footer: Compact Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ $isStudent ? route('students.dashboard') : route('quizzes.index') }}" class="h-14 px-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest flex items-center justify-center gap-3 transition-all shadow-xl shadow-indigo-600/20">
                        <i class="fas fa-home"></i> Return to Terminal
                    </a>
                    <a href="{{ $isStudent ? route('students.quizzes.take', $attempt->quiz_id) : route('quizzes.take', $attempt->quiz_id) }}" class="h-14 px-10 bg-white border border-slate-200 text-slate-500 hover:border-slate-800 hover:text-slate-900 rounded-2xl font-bold text-[10px] uppercase tracking-widest flex items-center justify-center gap-3 transition-all">
                        <i class="fas fa-rotate-right"></i> {{ $isStudent ? 'Request New Attempt' : 'Simulate Session' }}
                    </a>
                </div>
            </div>

            @if(in_array(auth()->user()->role_id, [1, 2]))
            <!-- Teacher Management Portal: Compact Audit -->
            <div class="mt-12 bg-white rounded-[40px] border border-slate-200/50 shadow-2xl overflow-hidden p-8 md:p-12">
                <div class="flex items-center gap-5 mb-12 border-b border-slate-50 pb-8">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-600/20">
                        <i class="fas fa-gavel text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 uppercase tracking-tight">Manual Audit Adjustment</h3>
                        <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mt-1.5">Verify result vectors and certify records.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    @foreach($attempt->quiz->questions as $index => $q)
                        @php $ans = isset($attemptAnswers) ? $attemptAnswers->get($q->id) : null; @endphp
                        <div class="p-8 bg-slate-50 rounded-[32px] border border-slate-100">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-8">
                                <div class="flex-grow">
                                    <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mb-2 px-2.5 py-1 bg-indigo-50 border border-indigo-100 rounded-md inline-block">Vector #{{ $index + 1 }}</div>
                                    <h4 class="text-lg font-bold text-slate-800 leading-snug uppercase tracking-tight">{!! $q->content !!}</h4>
                                </div>
                                <div class="shrink-0 flex items-center gap-2.5 px-4 py-2 rounded-xl bg-white border border-slate-100 shadow-sm">
                                    <i class="fas {{ $q->type === 'short_answer' ? 'fa-pen-nib text-amber-500' : 'fa-list-ul text-indigo-600' }} text-[10px]"></i>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ str_replace('_', ' ', $q->type) }}</span>
                                </div>
                            </div>

                            @if($q->type === 'short_answer')
                                <div class="p-8 bg-white border border-slate-200 rounded-[28px] text-slate-700 font-bold uppercase text-sm leading-relaxed relative shadow-inner">
                                    <div class="absolute -top-3 left-8 px-3 bg-indigo-600 text-[10px] font-bold text-white uppercase tracking-widest rounded-full h-6 flex items-center">Candidate Input</div>
                                    "{{ $ans->short_text ?? 'ERR: DATA LOSS DETECTED' }}"
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($q->answers as $option)
                                        @php $isSelected = $ans && $ans->answer_id == $option->id; @endphp
                                        <div class="flex items-center gap-4 p-5 rounded-2xl border transition-all {{ $isSelected ? ($option->is_correct ? 'bg-emerald-50 border-emerald-500/30' : 'bg-rose-50 border-rose-500/30 ring-2 ring-rose-100') : 'bg-white border-slate-100 opacity-60' }}">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold shadow-sm transition-all {{ $isSelected ? ($option->is_correct ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white') : 'bg-slate-50 text-slate-300' }}">
                                                {{ $option->is_correct ? '✓' : '✗' }}
                                            </div>
                                            <span class="text-sm font-bold uppercase tracking-tight {{ $isSelected ? 'text-slate-900' : 'text-slate-400' }}">{{ $option->answer_text }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Grading Logic -->
                <form action="{{ route('quizzes.grade', $attempt->id) }}" method="POST" class="mt-20 pt-16 border-t border-slate-100">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-indigo-950 rounded-[40px] p-10 md:p-16 text-white shadow-3xl">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.2em] text-indigo-300 mb-6 flex items-center gap-3">
                                <i class="fas fa-sliders-h"></i> Audit Score Index
                            </label>
                            <div class="relative">
                                <input type="number" name="manual_score" value="{{ $attempt->result->manual_score ?? round($attempt->result->score) }}" min="0" max="100" class="w-full bg-white/5 border-2 border-white/10 rounded-[28px] p-8 text-5xl font-bold text-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 outline-none transition-all tabular-nums">
                                <span class="absolute right-8 top-1/2 -translate-y-1/2 text-white/10 text-5xl font-bold">%</span>
                            </div>
                            <div class="mt-6 flex items-center gap-3 px-5 py-3 bg-white/5 rounded-xl border border-white/5">
                                <i class="fas fa-bolt text-indigo-400 text-xs"></i>
                                <span class="text-[10px] font-bold text-indigo-200/60 uppercase tracking-widest">Automated System Confidence: {{ round($attempt->result->score) }}%</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.2em] text-indigo-300 mb-6 flex items-center gap-3">
                                <i class="fas fa-file-signature"></i> Professional Observation
                            </label>
                            <textarea name="teacher_feedback" rows="5" class="w-full bg-white/5 border-2 border-white/10 rounded-[28px] p-8 text-indigo-50 font-bold uppercase tracking-tight focus:border-indigo-500 outline-none transition-all placeholder:text-white/10 text-sm leading-relaxed" placeholder="Record constructive analysis concerning candidate performance vectors...">{{ $attempt->result->teacher_feedback }}</textarea>
                        </div>

                        <div class="lg:col-span-2 mt-8 flex flex-col md:flex-row items-center justify-between gap-10 p-10 bg-white/5 rounded-[40px] border border-white/10">
                            <label class="flex items-center gap-6 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="publish" value="1" class="sr-only peer" {{ $attempt->result->is_published ? 'checked' : '' }}>
                                    <div class="w-16 h-9 bg-white/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-indigo-500 shadow-inner"></div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold uppercase tracking-widest text-white group-hover:text-indigo-300 transition-colors">Authorize Publication</span>
                                    <span class="text-[10px] font-bold text-indigo-400 mt-1 uppercase tracking-widest opacity-60">Authorize immediate record release to candidate terminal.</span>
                                </div>
                            </label>

                            <button type="submit" class="w-full md:w-auto h-16 px-14 bg-white text-indigo-950 rounded-2xl font-bold uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all text-[11px]">
                                Finalize Audit Protocol
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    @endif
</div>
@endsection
