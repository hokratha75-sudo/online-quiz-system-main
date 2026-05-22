@extends('layouts.admin')

@section('content')
<<<<<<< HEAD
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 pb-16">
    {{-- Immersive Header: Modern & Atmospheric --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 pt-16 pb-32">
        {{-- Abstract shapes --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 -left-32 w-96 h-96 bg-indigo-500/20 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-blue-500/20 rounded-full blur-[100px]"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-gradient-to-r from-indigo-500/5 via-transparent to-blue-500/5 blur-3xl"></div>
=======
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4f46e5; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
    [x-cloak] { display: none !important; }
</style>

<div class="bg-slate-50/50 pb-20">
    {{-- Immersive Header --}}
    <div class="relative overflow-hidden bg-slate-900 pt-16 pb-28">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
            <div class="absolute -top-[10%] -left-[5%] w-[40%] h-[60%] rounded-full bg-indigo-500/20 blur-[100px] animate-pulse"></div>
            <div class="absolute -bottom-[10%] -right-[5%] w-[30%] h-[50%] rounded-full bg-blue-500/15 blur-[80px]"></div>
>>>>>>> 79c1a1fe496f31be9519289ef8b16203199aa300
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full mb-6 shadow-lg border border-white/20">
                <span class="w-2 h-2 rounded-full bg-indigo-300 animate-pulse"></span>
                <span class="text-xs font-bold text-indigo-100 tracking-wider uppercase">{{ $quiz->subject?->subject_name ?? 'Knowledge Assessment' }}</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-white tracking-tight mb-5 leading-tight drop-shadow-2xl">
                {{ $quiz->title }}
            </h1>
            <p class="text-indigo-100/90 text-base md:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                {{ $quiz->description ?: 'Challenge yourself and validate your understanding of key concepts.' }}
            </p>
        </div>
    </div>

    {{-- Main Content Container --}}
<<<<<<< HEAD
    <div class="max-w-5xl mx-auto px-6 -mt-16 relative z-20">
        
        {{-- Availability Banner --}}
=======
    <div class="max-w-[1175px] mx-auto px-6 -mt-16 relative z-20">

        {{-- Quiz Availability Banner --}}
>>>>>>> 79c1a1fe496f31be9519289ef8b16203199aa300
        @if($quiz->opened_at || $quiz->closed_at)
        <div class="bg-white/70 backdrop-blur-md rounded-2xl border border-white/40 shadow-lg px-6 py-4 mb-8 flex flex-wrap items-center justify-between gap-3">
            @if($quiz->opened_at)
            <div class="flex items-center gap-3 text-sm text-slate-700">
                <i class="fas fa-calendar-alt text-indigo-400 w-4"></i>
                <span><span class="font-semibold text-slate-800">Opens:</span> {{ \Carbon\Carbon::parse($quiz->opened_at)->format('M d, Y · g:i A') }}</span>
            </div>
            @endif
            @if($quiz->closed_at)
            <div class="flex items-center gap-3 text-sm text-slate-700">
                <i class="fas fa-clock text-rose-400 w-4"></i>
                <span><span class="font-semibold text-slate-800">Deadline:</span> {{ \Carbon\Carbon::parse($quiz->closed_at)->format('M d, Y · g:i A') }}</span>
            </div>
            @endif
        </div>
        @endif
<<<<<<< HEAD

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column: Stats & Guidelines --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Metrics Cards --}}
                <div class="grid grid-cols-3 gap-5">
                    <div class="bg-white rounded-2xl p-5 shadow-md hover:shadow-lg transition-all duration-300 border border-slate-100 group">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center mb-4 shadow-md group-hover:scale-110 transition-transform">
                            <i class="fas fa-question-circle text-xl"></i>
                        </div>
                        <p class="text-2xl font-black text-slate-800">{{ $quiz->questions->count() }}</p>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mt-1">Total Questions</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-md hover:shadow-lg transition-all duration-300 border border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-rose-500 text-white flex items-center justify-center mb-4 shadow-md group-hover:scale-110 transition-transform">
                            <i class="fas fa-hourglass-half text-xl"></i>
                        </div>
                        <p class="text-2xl font-black text-slate-800">{{ $quiz->time_limit ?? 30 }} <span class="text-sm font-medium text-slate-500">min</span></p>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mt-1">Time Limit</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-md hover:shadow-lg transition-all duration-300 border border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white flex items-center justify-center mb-4 shadow-md group-hover:scale-110 transition-transform">
                            <i class="fas fa-flag-checkered text-xl"></i>
                        </div>
                        <p class="text-2xl font-black text-slate-800">{{ $quiz->pass_percentage ?? 60 }}<span class="text-sm font-medium text-slate-500">%</span></p>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mt-1">Passing Score</p>
=======
        
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Quick Stats Cards --}}
            <div class="grid grid-cols-3 gap-4 w-full">
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

            @if(($userRole ?? 'student') !== 'student')
            {{-- Teacher View: Submissions Detail --}}
            <div class="bg-white rounded-[28px] border overflow-hidden space-y-4">
                <div class="px-8 py-5 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900">Recent Attempt Logs</h3>
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-xs font-semibold text-indigo-600 shadow-sm">
                        {{ $quiz->attempts->where('status', 'completed')->count() }} Recorded
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <div class="h-[calc(100vh-100px)] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-200 border-b border-slate-400">
                                <th class="px-8 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-8 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-8 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Score</th>
                                <th class="px-8 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-right">View</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-500">
                            @forelse($quiz->attempts->where('status', 'completed') as $attempt)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($attempt->user->username ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-semibold text-slate-900 tracking-tight">{{ $attempt->user->username ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="text-xs font-medium text-slate-500 tracking-tight">{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : 'N/A' }}</div>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="text-sm font-bold {{ $attempt->result?->passed ? 'text-emerald-500' : 'text-rose-500' }}">
                                        {{ round($attempt->result?->score ?? 0) }}%
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <a href="{{ route('quizzes.result', $attempt->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm no-underline">
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
                        </tbody>
                    </table>
>>>>>>> 79c1a1fe496f31be9519289ef8b16203199aa300
                    </div>
                </div>
            </div>
            @endif

<<<<<<< HEAD
                {{-- Teacher View: Submissions Table --}}
                @if($userRole !== 'student')
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                    <div class="px-7 py-5 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider flex items-center gap-2">
                            <i class="fas fa-users text-indigo-500 text-xs"></i> Recent Submissions
                        </h3>
                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">{{ $quiz->attempts->where('status', 'completed')->count() }} attempts</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                    <th class="px-7 py-4">Student</th>
                                    <th class="px-7 py-4">Date</th>
                                    <th class="px-7 py-4">Score</th>
                                    <th class="px-7 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($quiz->attempts->where('status', 'completed') as $attempt)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-7 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-700 flex items-center justify-center font-bold text-xs shadow-sm">
                                                {{ strtoupper(substr($attempt->user->username, 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-slate-700 text-sm">{{ $attempt->user->username }}</span>
                                        </div>
                                    </td>
                                    <td class="px-7 py-4 text-sm text-slate-500">{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : '—' }}</td>
                                    <td class="px-7 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $attempt->result?->passed ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ round($attempt->result?->score ?? 0) }}%
                                        </span>
                                    </td>
                                    <td class="px-7 py-4 text-right">
                                        <a href="{{ route('quizzes.result', $attempt->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all">
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-7 py-12 text-center">
                                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-inbox text-slate-300 text-lg"></i>
                                        </div>
                                        <p class="text-xs font-medium text-slate-400">No submissions recorded yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Instructions Block --}}
                <div class="relative rounded-2xl bg-gradient-to-br from-indigo-900 via-indigo-800 to-slate-900 p-8 text-white shadow-xl overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6 pb-3 border-b border-white/10">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-clipboard-list text-indigo-300 text-sm"></i>
                            </div>
                            <h3 class="font-bold text-sm tracking-wide uppercase">Before you begin</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-eye text-indigo-300 text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm mb-1">Single Focus</p>
                                    <p class="text-indigo-200/70 text-xs leading-relaxed">Do not refresh or leave the quiz page during an active attempt.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-hourglass-start text-indigo-300 text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm mb-1">Time Management</p>
                                    <p class="text-indigo-200/70 text-xs leading-relaxed">Once started, the timer cannot be paused or reset.</p>
                                </div>
=======
            {{-- Instructions Block --}}
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
>>>>>>> 79c1a1fe496f31be9519289ef8b16203199aa300
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<<<<<<< HEAD
            {{-- Right Column: Actions & History --}}
            <div class="space-y-8">
                {{-- Primary Action Card --}}
                <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 sticky top-24">
                    @if($userRole === 'student')
                        @php $hasAttempts = isset($previousAttempts) && $previousAttempts->count() > 0; @endphp
                        <div class="text-center mb-5">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas {{ $hasAttempts ? 'fa-rotate-right' : 'fa-rocket' }} text-indigo-600 text-2xl"></i>
                            </div>
                            <h3 class="font-bold text-slate-800">{{ $hasAttempts ? 'Ready to Retake?' : 'Ready to Begin?' }}</h3>
                            <p class="text-xs text-slate-500 mt-1">{{ $hasAttempts ? 'Improve your previous score' : 'Start your assessment journey' }}</p>
                        </div>
                        <a href="{{ route('students.quizzes.take', $quiz->id) }}" class="group flex items-center justify-center gap-3 w-full py-4 bg-indigo-600 hover:bg-indigo-700 rounded-xl text-white font-bold transition-all shadow-md hover:shadow-lg active:scale-95">
                            <i class="fas {{ $hasAttempts ? 'fa-repeat' : 'fa-play' }} text-sm"></i>
                            <span>{{ $hasAttempts ? 'Retake Assessment' : 'Start Assessment' }}</span>
                        </a>
                    @else
                        <div class="text-center mb-5">
                            <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-sliders-h text-indigo-600 text-2xl"></i>
                            </div>
                            <h3 class="font-bold text-slate-800">Instructor Tools</h3>
                            <p class="text-xs text-slate-500 mt-1">Modify quiz parameters</p>
                        </div>
                        <a href="{{ route('quizzes.edit', $quiz->id) }}" class="flex items-center justify-center gap-3 w-full py-4 bg-indigo-100 hover:bg-indigo-200 text-indigo-800 rounded-xl font-bold transition-all">
                            <i class="fas fa-edit text-sm"></i> Edit Quiz
                        </a>
                    @endif
                </div>

                {{-- Student History --}}
                @if($userRole === 'student' && isset($previousAttempts) && $previousAttempts->count() > 0)
                <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                                <i class="fas fa-history text-indigo-400 text-xs"></i> Previous Attempts
                            </h3>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $previousAttempts->count() }} recorded</span>
                        </div>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @foreach($previousAttempts as $attempt)
                        <div class="p-5 hover:bg-slate-50/50 transition">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-mono text-slate-500">{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y · g:i A') : 'In progress' }}</span>
                                <span class="text-base font-black {{ $attempt->result?->passed ? 'text-emerald-600' : 'text-rose-500' }}">
                                    {{ $attempt->result ? round($attempt->result->score) . '%' : '—' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-1 rounded-full {{ $attempt->result?->passed ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    <i class="fas {{ $attempt->result?->passed ? 'fa-check-circle' : 'fa-times-circle' }} text-[9px]"></i>
                                    {{ $attempt->result?->passed ? 'PASSED' : 'FAILED' }}
                                </span>
                                <a href="{{ route('students.quizzes.result', $attempt->id) }}" class="text-xs font-medium text-indigo-500 hover:text-indigo-700 transition flex items-center gap-1">
                                    Details <i class="fas fa-chevron-right text-[9px]"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Student Question Preview (compact) --}}
                @if($userRole === 'student')
                <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-100">
                        <h3 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                            <i class="fas fa-list-ul text-indigo-400 text-xs"></i> Question Preview
                        </h3>
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                        @foreach($quiz->questions as $index => $question)
                        <div class="p-5 hover:bg-slate-50/30 transition">
                            <div class="flex gap-3">
                                <div class="font-bold text-indigo-500 text-sm">{{ $index + 1 }}.</div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-slate-800 mb-2">{!! $question->content !!}</div>
                                    @if($question->type !== 'short_answer')
                                    <div class="space-y-1 mt-2">
                                        @foreach($question->answers as $ans)
                                        <div class="text-xs text-slate-600 {{ $ans->is_correct ? 'text-emerald-600 font-semibold' : '' }}">• {{ $ans->answer_text }}</div>
                                        @endforeach
                                    </div>
                                    @else
                                    <p class="text-xs text-slate-400 italic mt-1">✍️ Short answer (manual grading)</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
=======
        {{-- Sidebar Actions --}}
        <div class="space-y-6">
            {{-- Action Card --}}
            <div class="bg-white rounded-[28px] p-6 border border-slate-200/50 shadow-xl relative overflow-hidden mt-4">
                <div class="relative z-10">
                    @if(($userRole ?? 'student') === 'student')
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

            @if(($userRole ?? 'student') === 'student' && isset($previousAttempts) && $previousAttempts->count() > 0)
            {{-- Previous Attempts History --}}
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
>>>>>>> 79c1a1fe496f31be9519289ef8b16203199aa300
                </div>
            </div>
            @endif

            @if(($userRole ?? 'student') === 'student')
            {{-- Student Question List --}}
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
@endsection