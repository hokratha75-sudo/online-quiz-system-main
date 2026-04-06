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
            <div class="inline-flex items-center gap-3 bg-white/5 border border-white/10 px-3 py-1.5 rounded-full mb-6 backdrop-blur-md italic">
                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                <span class="text-[9px] font-black text-indigo-200 uppercase tracking-[.2em]">{{ $quiz->subject?->subject_name ?? 'UNIT MODULE' }}</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-tight mb-4 uppercase italic">
                {{ $quiz->title }}
            </h1>
            <p class="text-white text-sm md:text-base font-black max-w-xl mx-auto leading-relaxed opacity-60 italic uppercase tracking-tight italic">
                "{{ $quiz->description ?: 'This assessment marks a critical milestone in your learning journey.' }}"
            </p>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-4xl mx-auto px-6 -mt-16 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Information Grid -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Quick Stats Cards: Compact -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-white rounded-[24px] p-5 border border-slate-200/50 shadow-sm flex flex-col items-center text-center group hover:bg-indigo-50/10 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class="far fa-copy text-lg"></i>
                        </div>
                        <span class="text-xl font-black text-slate-900 leading-none tabular-nums italic">{{ $quiz->questions->count() }}</span>
                        <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mt-2 italic">Items</span>
                    </div>
                    <div class="bg-white rounded-[24px] p-5 border border-slate-200/50 shadow-sm flex flex-col items-center text-center group hover:bg-rose-50/10 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class="far fa-clock text-lg"></i>
                        </div>
                        <span class="text-xl font-black text-slate-900 leading-none tabular-nums italic">{{ $quiz->time_limit ?? 30 }}</span>
                        <span class="text-[9px] font-black text-rose-600 uppercase tracking-widest mt-2 italic">Minutes</span>
                    </div>
                    <div class="bg-white rounded-[24px] p-5 border border-slate-200/50 shadow-sm flex flex-col items-center text-center group hover:bg-emerald-50/10 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class="far fa-star text-lg"></i>
                        </div>
                        <span class="text-xl font-black text-slate-900 leading-none tabular-nums italic">{{ $quiz->pass_percentage ?? 60 }}%</span>
                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mt-2 italic">Rate</span>
                    </div>
                </div>

                @if($userRole !== 'student')
                <!-- Teacher View: Submissions Detail -->
                <div class="bg-white rounded-[28px] border border-slate-200/50 shadow-sm overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight italic">Deployment Logs</h3>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-950 text-[9px] font-black text-white uppercase tracking-widest italic shadow-lg">
                            {{ $quiz->attempts->where('status', 'completed')->count() }} Synchronized
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-white border-b border-slate-100/50">
                                    <th class="px-8 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Sync ID</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Timestamp</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Yield</th>
                                    <th class="px-8 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic text-right italic">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($quiz->attempts->where('status', 'completed') as $attempt)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-950 text-white flex items-center justify-center font-black text-[10px] shadow-sm">
                                                {{ strtoupper(substr($attempt->user->username, 0, 1)) }}
                                            </div>
                                            <span class="text-xs font-black text-slate-900 uppercase tracking-tight">{{ $attempt->user->username }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-[11px] font-bold text-slate-700 tracking-tight tabular-nums italic">{{ $attempt->completed_at ? $attempt->completed_at->format('d M, Y') : 'N/A' }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-sm font-black italic {{ $attempt->result?->passed ? 'text-emerald-500' : 'text-rose-500' }}">
                                            {{ round($attempt->result?->score ?? 0) }}%
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <a href="{{ route('quizzes.result', $attempt->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-300 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm">
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-200">
                                            <i class="fas fa-inbox text-xl"></i>
                                        </div>
                                        <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Logs Empty</h4>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Instructions Block: Compact & Modern -->
                <div class="bg-indigo-950 rounded-[28px] p-8 md:p-10 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-48 h-48 bg-indigo-500/10 blur-[60px] rounded-full"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-8 border-b border-white/5 pb-4">
                            <i class="fas fa-microchip text-indigo-400 text-lg"></i>
                            <h3 class="text-sm font-black uppercase tracking-[0.2em] italic">Operational Protocol</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center text-indigo-300 shrink-0 mt-0.5 border border-white/5">
                                    <i class="fas fa-fingerprint text-[10px]"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1 italic">Proctor Engine</h4>
                                    <p class="text-indigo-200/50 text-[11px] leading-relaxed font-semibold italic">Focus monitoring is active. Session terminates on policy violation.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center text-indigo-300 shrink-0 mt-0.5 border border-white/5">
                                    <i class="fas fa-bolt text-[10px]"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs uppercase tracking-widest mb-1 italic">Timed Flow</h4>
                                    <p class="text-indigo-200/50 text-[11px] leading-relaxed font-semibold italic">Session is locked once initiated. Non-pauseable environment.</p>
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
                            <h3 class="text-[9px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-5 italic border-b border-slate-50 pb-3">Session Controls</h3>
                            
                            <a href="{{ route('students.quizzes.take', $quiz->id) }}" class="group relative flex flex-col items-center justify-center w-full aspect-video bg-slate-950 rounded-[20px] hover:bg-slate-900 transition-all duration-500 overflow-hidden shadow-2xl shadow-slate-950/20 active:scale-95">
                                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-indigo-600/10 to-transparent"></div>
                                <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xl mb-3 shadow-lg shadow-indigo-600/20 group-hover:scale-110 transition-transform">
                                    <i class="fas {{ $isRetake ? 'fa-rotate-left' : 'fa-play' }} text-xs"></i>
                                </div>
                                <div class="text-white font-black text-xs tracking-[0.2em] leading-none uppercase italic">{{ $isRetake ? 'Retake Exam' : 'Enter Arena' }}</div>
                                <div class="absolute inset-0 border-4 border-indigo-500/0 group-hover:border-indigo-500/5 rounded-[20px] transition-all"></div>
                            </a>
                        @else
                            <h3 class="text-[9px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-5 italic border-b border-slate-50 pb-3">Architecture</h3>
                            <a href="{{ route('quizzes.edit', $quiz->id) }}" class="w-full h-14 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-black uppercase tracking-widest flex items-center justify-center gap-3 shadow-lg shadow-indigo-600/10 transition-all italic text-xs">
                                <i class="fas fa-sliders text-[10px]"></i> Open Builder
                            </a>
                        @endif
                    </div>
                </div>

                @if($userRole === 'student' && isset($previousAttempts) && $previousAttempts->count() > 0)
                <!-- Previous Attempts History: Minimalist -->
                <div class="bg-white rounded-[28px] border border-slate-200/50 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Vector History</h3>
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                        </div>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @foreach($previousAttempts as $prevAttempt)
                        <div class="p-5 hover:bg-slate-50/30 transition-colors group">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest tabular-nums italic">{{ $prevAttempt->completed_at ? $prevAttempt->completed_at->format('M d, Y') : 'SYNCING' }}</span>
                                <div class="text-lg font-black italic tabular-nums {{ $prevAttempt->result?->passed ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $prevAttempt->result ? round($prevAttempt->result->score) . '%' : '--' }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[8px] font-black uppercase tracking-[0.2em] {{ $prevAttempt->result?->passed ? 'text-emerald-400' : 'text-rose-400' }} italic">
                                    {{ $prevAttempt->result?->passed ? 'Authorized' : 'Sync-Fail' }}
                                </span>
                                <a href="{{ route('students.quizzes.result', $prevAttempt->id) }}" class="text-[9px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-500 transition-colors italic">Report <i class="fas fa-chevron-right text-[7px] ml-1"></i></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
