@extends('layouts.admin')

@section('topbar-title', 'Leaderboard')

@section('content')
<div class="max-w-[1100px] mx-auto p-6 md:p-8 font-inter">

    {{-- Page header --}}
    <div class="bg-white border border-slate-100 rounded-2xl px-6 py-5 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-1.5 text-indigo-600 text-[10px] font-semibold uppercase tracking-widest mb-1">
                <i class="fas fa-trophy text-[10px]"></i> Academic Rankings
            </div>
            <h1 class="text-xl font-bold text-slate-900">Leaderboard</h1>
        </div>

        <form action="{{ route('leaderboard') }}" method="GET" id="filterForm" class="flex items-center gap-3">
            <div class="relative">
                <select name="subject_id" onchange="document.getElementById('filterForm').submit()"
                        class="appearance-none bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl py-2.5 pl-9 pr-8 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all cursor-pointer min-w-[200px]">
                    <option value="">All Subjects</option>
                    @foreach($filterSubjects as $subj)
                        <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                            {{ $subj->subject_name }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[11px] pointer-events-none"></i>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
            </div>
            <div class="text-xs font-semibold text-slate-500 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 shrink-0">
                <i class="fas fa-clock mr-1 text-slate-400"></i>{{ now()->format('M d, H:i') }}
            </div>
        </form>
    </div>

    {{-- Leaderboard sections --}}
    <div class="space-y-8">
        @if(isset($rankingsBySubject))
            @foreach($rankingsBySubject as $subjectName => $subjectRankings)
                <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">

                    {{-- Subject header --}}
                    <div class="flex items-center justify-between px-6 py-4 bg-slate-50/70 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-sm">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-900">{{ $subjectName }}</span>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider bg-white border border-slate-200 rounded-full px-3 py-1">Subject Class</span>
                    </div>

                    {{-- Podium: top 3 --}}
                    @php 
                        $podiumItems = $subjectRankings->currentPage() == 1 ? $subjectRankings->items() : [];
                    @endphp
                    @if($subjectRankings->currentPage() == 1 && count($podiumItems) >= 1)
                    <div class="flex items-end justify-center gap-4 px-8 py-8 border-b border-slate-100 bg-gradient-to-b from-slate-50/50 to-white">
                        {{-- 2nd place --}}
                        @if(isset($podiumItems[1]))
                        <div class="flex flex-col items-center gap-2 min-w-[120px]">
                            <div class="w-12 h-12 rounded-full bg-slate-100 border-2 border-slate-300 flex items-center justify-center font-bold text-slate-600 text-base overflow-hidden">
                                @if($podiumItems[1]->profile_photo)
                                    <img src="{{ asset('storage/' . $podiumItems[1]->profile_photo) }}" class="w-full h-full object-cover">
                                @else {{ substr($podiumItems[1]->username, 0, 1) }} @endif
                            </div>
                            <div class="text-center">
                                <div class="text-xs font-bold text-slate-800">{{ $podiumItems[1]->username }}</div>
                                <div class="text-[11px] text-slate-500">{{ $podiumItems[1]->avg_score }}%</div>
                            </div>
                            <span class="text-[11px] font-bold text-slate-500 bg-slate-100 border border-slate-200 rounded-full px-3 py-0.5">2nd</span>
                            <div class="w-full h-12 bg-slate-100 rounded-t-lg flex items-center justify-center text-xl font-black text-slate-400">2</div>
                        </div>
                        @endif

                        {{-- 1st place --}}
                        @if(isset($podiumItems[0]))
                        <div class="flex flex-col items-center gap-2 min-w-[140px] -mb-0">
                            <div class="w-16 h-16 rounded-full bg-amber-50 border-2 border-amber-300 flex items-center justify-center font-bold text-amber-600 text-xl overflow-hidden ring-4 ring-amber-50">
                                @if($podiumItems[0]->profile_photo)
                                    <img src="{{ asset('storage/' . $podiumItems[0]->profile_photo) }}" class="w-full h-full object-cover">
                                @else {{ substr($podiumItems[0]->username, 0, 1) }} @endif
                            </div>
                            <div class="text-center">
                                <div class="text-sm font-bold text-slate-900">{{ $podiumItems[0]->username }}</div>
                                <div class="text-[11px] text-slate-500">{{ $podiumItems[0]->avg_score }}%</div>
                            </div>
                            <span class="text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-full px-3 py-0.5">1st</span>
                            <div class="w-full h-20 bg-amber-50 rounded-t-lg flex items-center justify-center text-2xl font-black text-amber-400">1</div>
                        </div>
                        @endif

                        {{-- 3rd place --}}
                        @if(isset($podiumItems[2]))
                        <div class="flex flex-col items-center gap-2 min-w-[120px]">
                            <div class="w-12 h-12 rounded-full bg-orange-50 border-2 border-orange-300 flex items-center justify-center font-bold text-orange-500 text-base overflow-hidden">
                                @if($podiumItems[2]->profile_photo)
                                    <img src="{{ asset('storage/' . $podiumItems[2]->profile_photo) }}" class="w-full h-full object-cover">
                                @else {{ substr($podiumItems[2]->username, 0, 1) }} @endif
                            </div>
                            <div class="text-center">
                                <div class="text-xs font-bold text-slate-800">{{ $podiumItems[2]->username }}</div>
                                <div class="text-[11px] text-slate-500">{{ $podiumItems[2]->avg_score }}%</div>
                            </div>
                            <span class="text-[11px] font-bold text-orange-600 bg-orange-50 border border-orange-200 rounded-full px-3 py-0.5">3rd</span>
                            <div class="w-full h-8 bg-orange-50 rounded-t-lg flex items-center justify-center text-xl font-black text-orange-300">3</div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Rest of table (rank 4+) --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/30">
                                    <th class="pl-6 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest w-20">Rank</th>
                                    <th class="px-4 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Student</th>
                                    <th class="px-4 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest text-center">Quizzes</th>
                                    <th class="px-4 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest text-center">Avg. Score</th>
                                    <th class="px-4 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest text-center">Pass Rate</th>
                                    <th class="pr-6 py-3 w-12"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @php $startIndex = $subjectRankings->currentPage() == 1 ? 3 : 0; $tableItems = array_slice($subjectRankings->items(), $startIndex); @endphp
                                @forelse($tableItems as $i => $student)
                                    @php $rank = ($subjectRankings->currentPage() - 1) * $subjectRankings->perPage() + $startIndex + $i + 1; @endphp
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="pl-6 py-4">
                                            <span class="text-xs font-semibold text-slate-400 tabular-nums">#{{ $rank }}</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-semibold text-sm text-slate-500 shrink-0 overflow-hidden">
                                                    @if($student->profile_photo)
                                                        <img src="{{ asset('storage/' . $student->profile_photo) }}" class="w-full h-full object-cover">
                                                    @else {{ substr($student->username, 0, 1) }} @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-900">{{ $student->username }}</div>
                                                    <div class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">Learner</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-lg border border-slate-200/70">{{ $student->quizzes_taken }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="text-sm font-bold text-indigo-600 tabular-nums">{{ $student->avg_score }}%</div>
                                            <div class="w-20 h-1.5 bg-slate-100 rounded-full mx-auto mt-1.5 overflow-hidden">
                                                <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $student->avg_score }}%"></div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="text-sm font-semibold tabular-nums {{ $student->pass_rate >= 50 ? 'text-emerald-600' : 'text-rose-500' }}">
                                                {{ $student->pass_rate }}%
                                            </span>
                                        </td>
                                        <td class="pr-6 py-4 text-right">
                                            <button class="w-8 h-8 rounded-lg text-slate-300 hover:text-indigo-500 hover:bg-indigo-50 transition-all">
                                                <i class="fas fa-chevron-right text-[11px]"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    @if($subjectRankings->isEmpty())
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-slate-400 text-sm italic">No performance data yet for this subject.</td>
                                    </tr>
                                    @endif
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($subjectRankings->total() > $subjectRankings->perPage())
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/40">
                        <span class="text-xs text-slate-500">
                            {{ $subjectRankings->firstItem() }}–{{ $subjectRankings->lastItem() }} of {{ $subjectRankings->total() }} students
                        </span>
                        <div class="flex gap-1.5">
                            @if($subjectRankings->onFirstPage())
                                <button disabled class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-50 rounded-lg border border-slate-200 cursor-not-allowed">← Prev</button>
                            @else
                                <a href="{{ $subjectRankings->previousPageUrl() }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">← Prev</a>
                            @endif
                            @foreach($subjectRankings->getUrlRange(1, $subjectRankings->lastPage()) as $page => $url)
                                @if($page == $subjectRankings->currentPage())
                                    <button class="px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg border border-indigo-600">{{ $page }}</button>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if($subjectRankings->hasMorePages())
                                <a href="{{ $subjectRankings->nextPageUrl() }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">Next →</a>
                            @else
                                <button disabled class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-50 rounded-lg border border-slate-200 cursor-not-allowed">Next →</button>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>
            @endforeach

        @else
            {{-- Global ranking (no subject filter) --}}
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">

                {{-- Podium --}}
                @php 
                    $globalPodium = $rankings->currentPage() == 1 ? $rankings->items() : [];
                @endphp
                @if($rankings->currentPage() == 1 && count($globalPodium) >= 1)
                <div class="flex items-end justify-center gap-4 px-8 py-8 border-b border-slate-100 bg-gradient-to-b from-slate-50/50 to-white">
                    @if(isset($globalPodium[1]))
                    <div class="flex flex-col items-center gap-2 min-w-[120px]">
                        <div class="w-12 h-12 rounded-full bg-slate-100 border-2 border-slate-300 flex items-center justify-center font-bold text-slate-600 text-base overflow-hidden">
                            @if($globalPodium[1]->profile_photo)<img src="{{ asset('storage/' . $globalPodium[1]->profile_photo) }}" class="w-full h-full object-cover">@else{{ substr($globalPodium[1]->username, 0, 1) }}@endif
                        </div>
                        <div class="text-center"><div class="text-xs font-bold text-slate-800">{{ $globalPodium[1]->username }}</div><div class="text-[11px] text-slate-500">{{ $globalPodium[1]->avg_score }}%</div></div>
                        <span class="text-[11px] font-bold text-slate-500 bg-slate-100 border border-slate-200 rounded-full px-3 py-0.5">2nd</span>
                        <div class="w-full h-12 bg-slate-100 rounded-t-lg flex items-center justify-center text-xl font-black text-slate-400">2</div>
                    </div>
                    @endif
                    @if(isset($globalPodium[0]))
                    <div class="flex flex-col items-center gap-2 min-w-[140px]">
                        <div class="w-16 h-16 rounded-full bg-amber-50 border-2 border-amber-300 flex items-center justify-center font-bold text-amber-600 text-xl overflow-hidden ring-4 ring-amber-50">
                            @if($globalPodium[0]->profile_photo)<img src="{{ asset('storage/' . $globalPodium[0]->profile_photo) }}" class="w-full h-full object-cover">@else{{ substr($globalPodium[0]->username, 0, 1) }}@endif
                        </div>
                        <div class="text-center"><div class="text-sm font-bold text-slate-900">{{ $globalPodium[0]->username }}</div><div class="text-[11px] text-slate-500">{{ $globalPodium[0]->avg_score }}%</div></div>
                        <span class="text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-full px-3 py-0.5">1st</span>
                        <div class="w-full h-20 bg-amber-50 rounded-t-lg flex items-center justify-center text-2xl font-black text-amber-400">1</div>
                    </div>
                    @endif
                    @if(isset($globalPodium[2]))
                    <div class="flex flex-col items-center gap-2 min-w-[120px]">
                        <div class="w-12 h-12 rounded-full bg-orange-50 border-2 border-orange-300 flex items-center justify-center font-bold text-orange-500 text-base overflow-hidden">
                            @if($globalPodium[2]->profile_photo)<img src="{{ asset('storage/' . $globalPodium[2]->profile_photo) }}" class="w-full h-full object-cover">@else{{ substr($globalPodium[2]->username, 0, 1) }}@endif
                        </div>
                        <div class="text-center"><div class="text-xs font-bold text-slate-800">{{ $globalPodium[2]->username }}</div><div class="text-[11px] text-slate-500">{{ $globalPodium[2]->avg_score }}%</div></div>
                        <span class="text-[11px] font-bold text-orange-600 bg-orange-50 border border-orange-200 rounded-full px-3 py-0.5">3rd</span>
                        <div class="w-full h-8 bg-orange-50 rounded-t-lg flex items-center justify-center text-xl font-black text-orange-300">3</div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/30">
                                <th class="pl-6 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest w-20">Rank</th>
                                <th class="px-4 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Student</th>
                                <th class="px-4 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest text-center">Quizzes</th>
                                <th class="px-4 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest text-center">Avg. Score</th>
                                <th class="px-4 py-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest text-center">Pass Rate</th>
                                <th class="pr-6 py-3 w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @php $startGlobal = $rankings->currentPage() == 1 ? 3 : 0; $globalTableItems = array_slice($rankings->items(), $startGlobal); @endphp
                            @forelse($globalTableItems as $i => $student)
                                @php $rank = ($rankings->currentPage() - 1) * $rankings->perPage() + $startGlobal + $i + 1; @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="pl-6 py-4"><span class="text-xs font-semibold text-slate-400 tabular-nums">#{{ $rank }}</span></td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-semibold text-sm text-slate-500 shrink-0 overflow-hidden">
                                                @if($student->profile_photo)<img src="{{ asset('storage/' . $student->profile_photo) }}" class="w-full h-full object-cover">@else{{ substr($student->username, 0, 1) }}@endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900">{{ $student->username }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">Learner</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-lg border border-slate-200/70">{{ $student->quizzes_taken }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="text-sm font-bold text-indigo-600 tabular-nums">{{ $student->avg_score }}%</div>
                                        <div class="w-20 h-1.5 bg-slate-100 rounded-full mx-auto mt-1.5 overflow-hidden">
                                            <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $student->avg_score }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="text-sm font-semibold tabular-nums {{ $student->pass_rate >= 50 ? 'text-emerald-600' : 'text-rose-500' }}">{{ $student->pass_rate }}%</span>
                                    </td>
                                    <td class="pr-6 py-4 text-right">
                                        <button class="w-8 h-8 rounded-lg text-slate-300 hover:text-indigo-500 hover:bg-indigo-50 transition-all">
                                            <i class="fas fa-chevron-right text-[11px]"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                @if($rankings->isEmpty())
                                <tr><td colspan="6" class="py-16 text-center text-slate-400 text-sm italic">No student performance data available yet.</td></tr>
                                @endif
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($rankings->total() > $rankings->perPage())
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/40">
                    <span class="text-xs text-slate-500">{{ $rankings->firstItem() }}–{{ $rankings->lastItem() }} of {{ $rankings->total() }} students</span>
                    <div class="flex gap-1.5">
                        @if($rankings->onFirstPage())
                            <button disabled class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-50 rounded-lg border border-slate-200 cursor-not-allowed">← Prev</button>
                        @else
                            <a href="{{ $rankings->previousPageUrl() }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">← Prev</a>
                        @endif
                        @foreach($rankings->getUrlRange(1, $rankings->lastPage()) as $page => $url)
                            @if($page == $rankings->currentPage())
                                <button class="px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg border border-indigo-600">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach
                        @if($rankings->hasMorePages())
                            <a href="{{ $rankings->nextPageUrl() }}" class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">Next →</a>
                        @else
                            <button disabled class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-50 rounded-lg border border-slate-200 cursor-not-allowed">Next →</button>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        @endif
    </div>
</div>
@endsection