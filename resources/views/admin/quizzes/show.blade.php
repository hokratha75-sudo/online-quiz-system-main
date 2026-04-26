@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-20">
    <!-- Immersive Header: Compact & Impactful -->
    <div class="relative overflow-hidden bg-slate-900 pt-16 pb-28">
        <!-- Abstract Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
            <div class="absolute -top-[10%] -left-[5%] w-[40%] h-[60%] rounded-full bg-indigo-500/20 blur-[100px] animate-pulse"></div>
            <div class="absolute -bottom-[10%] -right-[5%] w-[30%] h-[50%] rounded-full bg-blue-500/15 blur-[80px]"></div>
        </div>

        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
            <div class="inline-flex items-center gap-3 bg-white/10 px-3 py-1.5 rounded-full mb-6 backdrop-blur-sm">
                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                <span class="text-xs font-semibold text-indigo-100 tracking-wide">{{ $quiz->subject?->subject_name ?? 'General Quiz' }}</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight leading-tight mb-4">
                {{ $quiz->title }}
            </h1>
            <p class="text-white/80 text-sm md:text-base font-medium max-w-xl mx-auto leading-relaxed">
                {{ $quiz->description ?: 'This assessment marks a critical milestone in your learning journey.' }}
            </p>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-4xl mx-auto px-6 -mt-16 relative z-20">

        {{-- Quiz Availability Banner --}}
        @if($quiz->opened_at || $quiz->closed_at)
        <div class="bg-white rounded-2xl border border-slate-200/80 px-6 py-4 mb-6 shadow-sm space-y-1">
            @if($quiz->opened_at)
            <p class="text-[13px] text-slate-700 leading-relaxed">
                <span class="font-bold text-slate-800">Opened:</span>
                {{ \Carbon\Carbon::parse($quiz->opened_at)->format('l, d F Y, g:i A') }}
            </p>
            @endif
            @if($quiz->closed_at)
            <p class="text-[13px] text-slate-700 leading-relaxed">
                <span class="font-bold text-slate-800">Closed:</span>
                {{ \Carbon\Carbon::parse($quiz->closed_at)->format('l, d F Y, g:i A') }}
            </p>
            @endif
        </div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Information Grid -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Quick Stats Cards: Compact -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-white rounded-[24px] p-5 border border-slate-100 shadow-sm flex flex-col items-center text-center group hover:shadow-md transition-all">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class="far fa-copy text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-slate-900 leading-none tabular-nums">{{ $quiz->questions->count() }}</span>
                        <span class="text-xs font-medium text-slate-500 mt-2">Questions</span>
                    </div>
                    <div class="bg-white rounded-[24px] p-5 border border-slate-100 shadow-sm flex flex-col items-center text-center group hover:shadow-md transition-all">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class="far fa-clock text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-slate-900 leading-none tabular-nums">{{ $quiz->time_limit ?? 30 }}</span>
                        <span class="text-xs font-medium text-slate-500 mt-2">Minutes</span>
                    </div>
                    <div class="bg-white rounded-[24px] p-5 border border-slate-100 shadow-sm flex flex-col items-center text-center group hover:shadow-md transition-all">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class="far fa-star text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-slate-900 leading-none tabular-nums">{{ $quiz->pass_percentage ?? 60 }}%</span>
                        <span class="text-xs font-medium text-slate-500 mt-2">To Pass</span>
                    </div>
                </div>

                @if($userRole !== 'student')
                <!-- Teacher View: Submissions Detail -->
                <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-900">Recent Attempt Logs</h3>
                        <span class="px-3 py-1 rounded-full bg-indigo-50 text-xs font-semibold text-indigo-600 shadow-sm">
                            {{ $quiz->attempts->where('status', 'completed')->count() }} Recorded
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-white border-b border-slate-50 hover:bg-transparent">
                                    <th class="px-8 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-8 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                                    <th class="px-8 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Score</th>
                                    <th class="px-8 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-right">View</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($quiz->attempts->where('status', 'completed') as $attempt)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs shadow-sm">
                                                {{ strtoupper(substr($attempt->user->username, 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-semibold text-slate-900 tracking-tight">{{ $attempt->user->username }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-xs font-medium text-slate-500 tracking-tight">{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : 'N/A' }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-sm font-bold shadow-sm {{ $attempt->result?->passed ? 'text-emerald-500' : 'text-rose-500' }}">
                                            {{ round($attempt->result?->score ?? 0) }}%
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <a href="{{ route('quizzes.result', $attempt->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm">
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-300">
                                            <i class="fas fa-inbox text-xl"></i>
                                        </div>
                                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">No submissions yet</h4>
                                    </td>
                                </tr>
                                @endforelse
                @endif

                <!-- Instructions Block: Compact & Modern -->
                <div class="bg-indigo-950 rounded-[28px] p-8 md:p-10 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-48 h-48 bg-indigo-500/10 blur-[60px] rounded-full"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-8 border-b border-white/10 pb-4">
                            <i class="fas fa-list-check text-indigo-400 text-lg"></i>
                            <h3 class="text-sm font-bold tracking-wide">Quiz Guidelines</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center text-indigo-300 shrink-0 mt-0.5 border border-white/5">
                                    <i class="fas fa-eye text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm mb-1">Focus Environment</h4>
                                    <p class="text-indigo-200/70 text-xs leading-relaxed font-medium">Please remain on this tab. Leaving the assessment window may trigger a warning.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center text-indigo-300 shrink-0 mt-0.5 border border-white/5">
                                    <i class="fas fa-hourglass-half text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm mb-1">Timed Session</h4>
                                    <p class="text-indigo-200/70 text-xs leading-relaxed font-medium">The timer cannot be paused once initiated. Make sure you have enough time left.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start Action / History Column: Refined -->
            <div class="space-y-6">
                <!-- Action Card: Compact & Professional -->
                <div class="bg-white rounded-[28px] p-6 border border-slate-200/50 shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        @if($userRole === 'student')
                            @php $isRetake = isset($previousAttempts) && $previousAttempts->count() > 0; @endphp
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-5 border-b border-slate-50 pb-3">Session Controls</h3>
                            
                            <a href="{{ route('students.quizzes.take', $quiz->id) }}" class="group relative flex flex-col items-center justify-center w-full aspect-video bg-indigo-600 rounded-[20px] hover:bg-indigo-700 transition-all duration-300 overflow-hidden shadow-md active:scale-95">
                                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/10 to-transparent"></div>
                                <div class="w-12 h-12 rounded-xl bg-white text-indigo-600 flex items-center justify-center text-xl mb-3 shadow-lg group-hover:scale-110 transition-transform">
                                    <i class="fas {{ $isRetake ? 'fa-rotate-left' : 'fa-play' }} text-base"></i>
                                </div>
                                <div class="text-white font-bold text-sm tracking-wide">{{ $isRetake ? 'Retake Exam' : 'Start Assessment' }}</div>
                            </a>
                        @else
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-5 border-b border-slate-50 pb-3">Management</h3>
                            <a href="{{ route('quizzes.edit', $quiz->id) }}" class="w-full h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold flex items-center justify-center gap-3 shadow-md transition-all text-sm">
                                <i class="fas fa-sliders text-xs"></i> Edit Quiz
                            </a>
                        @endif
                    </div>
                </div>

                @if($userRole === 'student' && isset($previousAttempts) && $previousAttempts->count() > 0)
                <!-- Previous Attempts History: Minimalist -->
                <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900">Your History</h3>
                            <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                        </div>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @foreach($previousAttempts as $prevAttempt)
                        <div class="p-5 hover:bg-slate-50/30 transition-colors group">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-slate-500 tabular-nums">{{ $prevAttempt->completed_at ? $prevAttempt->completed_at->format('M d, Y') : 'Ongoing...' }}</span>
                                <div class="text-lg font-bold tabular-nums {{ $prevAttempt->result?->passed ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $prevAttempt->result ? round($prevAttempt->result->score) . '%' : '--' }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md {{ $prevAttempt->result?->passed ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                    {{ $prevAttempt->result?->passed ? 'Passed' : 'Failed' }}
                                </span>
                                <a href="{{ route('students.quizzes.result', $prevAttempt->id) }}" class="text-xs font-semibold text-slate-400 hover:text-indigo-500 transition-colors">Details <i class="fas fa-chevron-right text-[10px] ml-1"></i></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($userRole === 'student')
                <!-- Student Question List -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Questions</h3>
                    @foreach($quiz->questions as $index => $question)
                    <div class="mb-6 p-4 bg-white rounded-xl shadow-sm">
                        <div class="flex items-center mb-2">
                            <span class="font-bold text-indigo-600 mr-2">{{ $index + 1 }}.</span>
                            <span class="text-lg font-medium">{!! $question->content !!}</span>
                        </div>
                        @if($question->type !== 'short_answer')
                        <ul class="list-disc list-inside ml-6">
                            @foreach($question->answers as $ans)
                            <li class="{{ $ans->is_correct ? 'text-emerald-600 font-semibold' : '' }}">{{ $ans->answer_text }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-slate-500 italic">Short answer question – answer will be graded manually.</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
