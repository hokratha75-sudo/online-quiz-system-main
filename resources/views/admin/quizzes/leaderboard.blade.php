@extends('layouts.admin')

@section('topbar-title', 'Leaderboard')

@section('content')
<div class="max-w-[1200px] mx-auto p-4 md:p-8 font-inter">

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200/70 rounded-3xl px-6 py-5 mb-8 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">

            <div>
                <div class="flex items-center gap-2 text-indigo-600 text-[10px] font-bold uppercase tracking-[0.2em] mb-2">
                    <i class="fas fa-trophy"></i>
                    Academic Rankings
                </div>

                <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                    Leaderboard
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Track student performance and rankings across subjects.
                </p>
            </div>

            <form action="{{ route('leaderboard') }}" method="GET" id="filterForm" class="flex items-center gap-3 flex-wrap">

                <div class="relative">
                    <select
                        name="subject_id"
                        onchange="document.getElementById('filterForm').submit()"
                        class="appearance-none bg-slate-50 border border-slate-200 text-slate-700 text-sm font-semibold rounded-2xl py-3 pl-10 pr-10 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 transition-all min-w-[220px]"
                    >
                        <option value="">All Subjects</option>

                        @foreach($filterSubjects as $subj)
                            <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                                {{ $subj->subject_name }}
                            </option>
                        @endforeach
                    </select>

                    <i class="fas fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold text-slate-500">
                    <i class="fas fa-clock mr-1 text-slate-400"></i>
                    {{ now()->format('M d, H:i') }}
                </div>

            </form>
        </div>
    </div>

    {{-- Subject Rankings --}}
    <div class="space-y-8">

        @if(isset($rankingsBySubject))

            @foreach($rankingsBySubject as $subjectName => $subjectRankings)

                <div class="bg-white border border-slate-200/70 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">

                    {{-- Subject Header --}}
                    <div class="flex items-center justify-between px-6 py-5 bg-gradient-to-r from-indigo-50 to-white border-b border-slate-200/70">

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-book-open text-sm"></i>
                            </div>

                            <div>
                                <div class="text-sm font-black text-slate-900">
                                    {{ $subjectName }}
                                </div>

                                <div class="text-[11px] text-slate-500 font-medium mt-0.5">
                                    Subject Rankings
                                </div>
                            </div>

                        </div>

                        <div class="hidden md:flex items-center gap-2 bg-white border border-slate-200 rounded-full px-4 py-1.5">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Active
                            </span>
                        </div>

                    </div>

                    {{-- PODIUM --}}
                    @php
                        $podiumItems = $subjectRankings->currentPage() == 1 ? $subjectRankings->items() : [];
                    @endphp

                    @if($subjectRankings->currentPage() == 1 && count($podiumItems) >= 1)

                        <div class="flex items-end justify-center gap-6 px-6 md:px-10 py-10 bg-gradient-to-b from-indigo-50/40 to-white border-b border-slate-200/70 overflow-x-auto flex-nowrap md:flex-wrap">

                            {{-- SECOND --}}
                            @if(isset($podiumItems[1]))
                            <div class="flex flex-col items-center gap-1.5 min-w-[120px]">

                                <div class="w-14 h-14 rounded-full overflow-hidden border-[3px] border-slate-300 bg-slate-100 shadow-sm">
                                    @if($podiumItems[1]->profile_photo)
                                        <img src="{{ asset('storage/' . $podiumItems[1]->profile_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center font-black text-slate-600 text-lg">
                                            {{ substr($podiumItems[1]->username, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="text-center">
                                    <div class="text-sm font-bold text-slate-800">
                                        {{ $podiumItems[1]->username }}
                                    </div>

                                    <div class="text-xs text-slate-500 font-semibold">
                                        {{ $podiumItems[1]->avg_score }}%
                                    </div>
                                </div>

                                <span class="text-[11px] font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-full px-3 py-1">
                                    2nd
                                </span>

                                <div class="w-full h-20 bg-gradient-to-b from-slate-200 to-slate-100 rounded-t-2xl flex items-center justify-center text-2xl font-black text-slate-500">
                                    2
                                </div>

                            </div>
                            @endif

                            {{-- FIRST --}}
                            @if(isset($podiumItems[0]))
                            <div class="flex flex-col items-center gap-1.5 min-w-[160px] scale-105">

                                <div class="relative">

                                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 text-yellow-400 text-xl">
                                        <i class="fas fa-crown"></i>
                                    </div>

                                    <div class="w-20 h-20 rounded-full overflow-hidden border-[4px] border-amber-300 bg-gradient-to-b from-yellow-100 to-amber-50 ring-4 ring-amber-50 shadow-lg shadow-amber-100">
                                        @if($podiumItems[0]->profile_photo)
                                            <img src="{{ asset('storage/' . $podiumItems[0]->profile_photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center font-black text-amber-700 text-2xl">
                                                {{ substr($podiumItems[0]->username, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                </div>

                                <div class="text-center">
                                    <div class="text-base font-black text-slate-900">
                                        {{ $podiumItems[0]->username }}
                                    </div>

                                    <div class="text-sm text-slate-500 font-semibold">
                                        {{ $podiumItems[0]->avg_score }}%
                                    </div>
                                </div>

                                <span class="text-[11px] font-black text-amber-700 bg-amber-50 border border-amber-200 rounded-full px-4 py-1">
                                    1st Place
                                </span>

                                <div class="w-full h-28 bg-gradient-to-b from-yellow-100 to-amber-50 rounded-t-2xl flex items-center justify-center text-3xl font-black text-amber-500">
                                    1
                                </div>

                            </div>
                            @endif

                            {{-- THIRD --}}
                            @if(isset($podiumItems[2]))
                            <div class="flex flex-col items-center gap-1.5 min-w-[120px]">

                                <div class="w-14 h-14 rounded-full overflow-hidden border-[3px] border-orange-300 bg-orange-50 shadow-sm">
                                    @if($podiumItems[2]->profile_photo)
                                        <img src="{{ asset('storage/' . $podiumItems[2]->profile_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center font-black text-orange-600 text-lg">
                                            {{ substr($podiumItems[2]->username, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="text-center">
                                    <div class="text-sm font-bold text-slate-800">
                                        {{ $podiumItems[2]->username }}
                                    </div>

                                    <div class="text-xs text-slate-500 font-semibold">
                                        {{ $podiumItems[2]->avg_score }}%
                                    </div>
                                </div>

                                <span class="text-[11px] font-bold text-orange-600 bg-orange-50 border border-orange-200 rounded-full px-3 py-1">
                                    3rd
                                </span>

                                <div class="w-full h-14 bg-gradient-to-b from-orange-100 to-orange-50 rounded-t-2xl flex items-center justify-center text-2xl font-black text-orange-400">
                                    3
                                </div>

                            </div>
                            @endif

                        </div>

                    @endif

                    {{-- TABLE --}}
                    <div class="overflow-x-auto">

                        <table class="w-full text-left">

                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/70">

                                    <th class="pl-6 py-4 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">
                                        Rank
                                    </th>

                                    <th class="px-4 py-4 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">
                                        Student
                                    </th>

                                    <th class="px-4 py-4 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500 text-center">
                                        Quizzes
                                    </th>

                                    <th class="px-4 py-4 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500 text-center">
                                        Avg Score
                                    </th>

                                    <th class="px-4 py-4 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500 text-center">
                                        Pass Rate
                                    </th>

                                    <th class="pr-4 py-4"></th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">

                                @php
                                    $startIndex = $subjectRankings->currentPage() == 1 ? 3 : 0;
                                    $tableItems = array_slice($subjectRankings->items(), $startIndex);
                                @endphp

                                @forelse($tableItems as $i => $student)

                                    @php
                                        $rank = ($subjectRankings->currentPage() - 1) * $subjectRankings->perPage() + $startIndex + $i + 1;
                                    @endphp

                                    <tr class="hover:bg-indigo-50/40 transition-all duration-200">

                                        <td class="pl-6 py-4">
                                            <span class="text-sm font-black text-indigo-500">
                                                #{{ $rank }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4">

                                            <div class="flex items-center gap-3">

                                                <div class="w-11 h-11 rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-50 to-slate-100 border border-slate-200 shadow-sm shrink-0">

                                                    @if($student->profile_photo)
                                                        <img src="{{ asset('storage/' . $student->profile_photo) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-sm font-black text-indigo-600">
                                                            {{ substr($student->username, 0, 1) }}
                                                        </div>
                                                    @endif

                                                </div>

                                                <div>
                                                    <div class="text-sm font-bold text-slate-900">
                                                        {{ $student->username }}
                                                    </div>

                                                    <div class="text-[11px] text-slate-500 font-medium uppercase tracking-wider mt-1">
                                                        Learner
                                                    </div>
                                                </div>

                                            </div>

                                        </td>

                                        <td class="px-4 py-4 text-center">

                                            <span class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200">
                                                {{ $student->quizzes_taken }}
                                            </span>

                                        </td>

                                        <td class="px-4 py-4 text-center">

                                            <div class="text-sm font-black text-slate-800">
                                                {{ $student->avg_score }}%
                                            </div>

                                            <div class="w-24 h-2 bg-slate-100 rounded-full mx-auto mt-2 overflow-hidden">

                                                <div
                                                    class="h-full rounded-full
                                                    {{ $student->avg_score >= 85 ? 'bg-emerald-500' :
                                                       ($student->avg_score >= 70 ? 'bg-indigo-500' :
                                                       ($student->avg_score >= 50 ? 'bg-amber-500' : 'bg-rose-500')) }}"
                                                    style="width: {{ $student->avg_score }}%">
                                                </div>

                                            </div>

                                        </td>

                                        <td class="px-4 py-4 text-center">

                                            <span class="text-sm font-black {{ $student->pass_rate >= 50 ? 'text-emerald-600' : 'text-rose-500' }}">
                                                {{ $student->pass_rate }}%
                                            </span>

                                        </td>

                                        <td class="pr-4 py-4 text-right">

                                            <button class="w-9 h-9 rounded-xl border border-transparent hover:border-indigo-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-400 transition-all duration-200">
                                                <i class="fas fa-chevron-right text-[11px]"></i>
                                            </button>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="py-16 text-center">

                                            <div class="flex flex-col items-center">

                                                <i class="fas fa-chart-line text-4xl text-slate-300 mb-4"></i>

                                                <div class="text-slate-500 text-sm font-semibold">
                                                    No performance data yet for this subject.
                                                </div>

                                            </div>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- PAGINATION --}}
                    @if($subjectRankings->total() > $subjectRankings->perPage())

                    <div class="px-6 py-5 border-t border-slate-200 bg-white flex items-center justify-between flex-wrap gap-4">

                        <div class="text-sm text-slate-500 font-medium">
                            {{ $subjectRankings->firstItem() }}–{{ $subjectRankings->lastItem() }}
                            of
                            {{ $subjectRankings->total() }} students
                        </div>

                        <div class="flex items-center gap-2">

                            {{-- PREV --}}
                            @if($subjectRankings->onFirstPage())

                                <button disabled class="px-4 py-2 text-xs font-bold text-slate-300 bg-slate-50 rounded-xl border border-slate-200 cursor-not-allowed">
                                    ← Prev
                                </button>

                            @else

                                <a href="{{ $subjectRankings->previousPageUrl() }}"
                                   class="px-4 py-2 text-xs font-bold text-slate-700 bg-white rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 transition-all">
                                    ← Prev
                                </a>

                            @endif

                            {{-- PAGES --}}
                            @foreach($subjectRankings->getUrlRange(1, $subjectRankings->lastPage()) as $page => $url)

                                @if($page == $subjectRankings->currentPage())

                                    <button class="px-4 py-2 text-xs font-black text-white bg-gradient-to-r from-indigo-600 to-violet-600 rounded-xl shadow-md">
                                        {{ $page }}
                                    </button>

                                @else

                                    <a href="{{ $url }}"
                                       class="px-4 py-2 text-xs font-bold text-slate-700 bg-white rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 transition-all">
                                        {{ $page }}
                                    </a>

                                @endif

                            @endforeach

                            {{-- NEXT --}}
                            @if($subjectRankings->hasMorePages())

                                <a href="{{ $subjectRankings->nextPageUrl() }}"
                                   class="px-4 py-2 text-xs font-bold text-slate-700 bg-white rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 transition-all">
                                    Next →
                                </a>

                            @else

                                <button disabled class="px-4 py-2 text-xs font-bold text-slate-300 bg-slate-50 rounded-xl border border-slate-200 cursor-not-allowed">
                                    Next →
                                </button>

                            @endif

                        </div>

                    </div>

                    @endif

                </div>

            @endforeach

        @endif

    </div>
</div>
@endsection