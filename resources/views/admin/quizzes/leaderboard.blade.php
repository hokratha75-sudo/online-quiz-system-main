@extends('layouts.admin')

@section('topbar-title', 'Leaderboard')

@section('content')
<div class="max-w-[1200px] mx-auto p-6 md:p-8 font-inter">
    <!-- Header: Compact Leaderboard -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-6 md:p-8 mb-8 shadow-sm border border-slate-100">
        <div class="absolute inset-0 opacity-40 pointer-events-none">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 blur-[80px] rounded-full translate-x-1/2 -translate-y-1/2"></div>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 text-indigo-600 text-[10px] font-black uppercase tracking-[0.2em] mb-2">
                    <i class="fas fa-crown"></i> Academic Rankings
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">Leaderboard</h1>
            </div>
            
            <form action="{{ route('leaderboard') }}" method="GET" id="filterForm" class="flex flex-wrap items-center gap-4">
                <div class="relative min-w-[240px]">
                    <select name="subject_id" onchange="document.getElementById('filterForm').submit()" 
                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-2xl py-3 px-10 appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all cursor-pointer">
                        <option value="">All Subjects</option>
                        @foreach($filterSubjects as $subj)
                            <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                                {{ $subj->subject_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>

                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-100 shrink-0">
                    <div class="w-7 h-7 bg-white text-amber-500 rounded-lg flex items-center justify-center text-xs shadow-sm border border-slate-100">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="text-[10px] font-bold text-slate-900 tabular-nums">{{ now()->format('M d, H:i') }}</div>
                </div>
            </form>
        </div>
    </div>

    <!-- Leaderboard Content -->
    <div class="space-y-12">
        @if(isset($rankingsBySubject))
            @foreach($rankingsBySubject as $subjectName => $subjectRankings)
                <div class="bg-white/80 backdrop-blur-sm rounded-[32px] border border-slate-200/60 shadow-xl overflow-hidden relative">
                    <!-- Subject Banner -->
                    <div class="bg-slate-50/80 px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                <i class="fas fa-book text-xs"></i>
                            </div>
                            <h2 class="text-lg font-black text-slate-900">{{ $subjectName }}</h2>
                        </div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-white px-3 py-1 rounded-full border border-slate-200">Subject Class</div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-0">
                            <thead>
                                <tr class="bg-slate-50/30">
                                    <th width="100" class="ps-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Rank</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Student Name</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Quizzes Taken</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Avg. Score</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Pass Rate</th>
                                    <th width="100" class="pe-8 py-5"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($subjectRankings as $index => $student)
                                    <tr class="group hover:bg-indigo-50/30 transition-all duration-500">
                                        <td class="ps-8 py-4">
                                            <div class="flex items-center">
                                                @if($index == 0)
                                                    <div class="w-9 h-9 bg-gradient-to-br from-yellow-100 to-amber-50 text-amber-600 rounded-xl flex items-center justify-center font-black shadow-sm border border-amber-100/50">1st</div>
                                                @elseif($index == 1)
                                                    <div class="w-8 h-8 bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center font-black shadow-sm border border-slate-200/50">2nd</div>
                                                @elseif($index == 2)
                                                    <div class="w-8 h-8 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center font-black shadow-sm border border-orange-100/50">3rd</div>
                                                @else
                                                    <div class="w-8 h-8 text-slate-400 flex items-center justify-center font-bold text-xs">#{{ $index + 1 }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                                    @if($student->profile_photo)
                                                        <img src="{{ asset('storage/' . $student->profile_photo) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold bg-slate-50">
                                                            {{ substr($student->username, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900">{{ $student->username }}</div>
                                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Learner</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg border border-slate-200/50">{{ $student->quizzes_taken }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="text-sm font-black text-indigo-600 tabular-nums">{{ $student->avg_score }}%</div>
                                            <div class="w-16 h-1 bg-slate-100 rounded-full mx-auto mt-2 overflow-hidden border border-slate-200/50">
                                                <div class="h-full bg-indigo-500" style="width: {{ $student->avg_score }}%"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <div class="text-xs font-bold tabular-nums {{ $student->pass_rate >= 50 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                    {{ $student->pass_rate }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="pe-8 py-4 text-right">
                                            <button class="w-8 h-8 rounded-lg text-slate-400 hover:bg-white hover:text-indigo-600 hover:shadow-sm transition-all border border-transparent hover:border-slate-100">
                                                <i class="fas fa-chevron-right text-[10px]"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-slate-400 font-medium italic">No performance data yet for this subject.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Global Ranking -->
            <div class="bg-white/80 backdrop-blur-sm rounded-[32px] border border-slate-200/60 shadow-xl overflow-hidden relative">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-50/80">
                                <th width="100" class="ps-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Rank</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Student Name</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Quizzes Taken</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Avg. Score</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Pass Rate</th>
                                <th width="100" class="pe-8 py-6"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rankings as $index => $student)
                                <tr class="group hover:bg-indigo-50/30 transition-all duration-500">
                                    <td class="ps-8 py-6">
                                        <div class="flex items-center">
                                            @if($index == 0)
                                                <div class="w-10 h-10 bg-gradient-to-br from-yellow-100 to-amber-50 text-amber-600 rounded-xl flex items-center justify-center font-black shadow-sm border border-amber-100/50">1st</div>
                                            @elseif($index == 1)
                                                <div class="w-9 h-9 bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center font-black shadow-sm border border-slate-200/50">2nd</div>
                                            @elseif($index == 2)
                                                <div class="w-9 h-9 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center font-black shadow-sm border border-orange-100/50">3rd</div>
                                            @else
                                                <div class="w-9 h-9 text-slate-400 flex items-center justify-center font-bold text-xs">#{{ $index + 1 }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                                @if($student->profile_photo)
                                                    <img src="{{ asset('storage/' . $student->profile_photo) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold bg-slate-50">
                                                        {{ substr($student->username, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-slate-900 tracking-tight">{{ $student->username }}</div>
                                                <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Learner</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <span class="inline-flex items-center px-4 py-1.5 bg-slate-100 text-slate-700 text-xs font-black rounded-xl border border-slate-200/50 shadow-sm">{{ $student->quizzes_taken }}</span>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <div class="text-lg font-black text-indigo-600 tabular-nums">{{ $student->avg_score }}%</div>
                                        <div class="w-20 h-1.5 bg-slate-100 rounded-full mx-auto mt-2 overflow-hidden border border-slate-200/50">
                                            <div class="h-full bg-indigo-500" style="width: {{ $student->avg_score }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <div class="text-sm font-black tabular-nums {{ $student->pass_rate >= 50 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $student->pass_rate }}%
                                        </div>
                                    </td>
                                    <td class="pe-8 py-6 text-right">
                                        <button class="w-10 h-10 rounded-xl text-slate-400 hover:bg-white hover:text-indigo-600 hover:shadow-lg transition-all border border-transparent hover:border-slate-100">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center text-slate-400 font-medium italic">No student performance data available at the moment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
