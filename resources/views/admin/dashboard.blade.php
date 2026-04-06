@extends('layouts.admin')

@section('content')
<!-- Extremely Clean, Vercel/Linear Inspired SaaS aesthetic -->
<div class="max-w-[1400px] mx-auto px-6 py-8 md:px-10 lg:py-10 font-sans">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tighter uppercase font-heading">Insights</h1>
            <p class="text-xs font-bold text-indigo-600 mt-1 uppercase tracking-widest italic tracking-tighter">System Access: {{ $username }}</p>
        </div>
        @if($userRole === 'admin' || $userRole === 'teacher')
        <div class="flex gap-3">
            <a href="{{ route('quizzes.create') ?? '#' }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-600/20">
                <i class="fas fa-plus text-xs"></i> New Quiz
            </a>
            @if($userRole === 'admin')
            <a href="{{ route('admin.settings.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <i class="fas fa-cog text-slate-400"></i>
            </a>
            @endif
        </div>
        @endif
    </div>

    @if ($userRole === 'admin')
        <!-- Admin Metrics (Minimalist Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6 mb-8">
            <div class="glass-panel rounded-[24px] p-6 flex flex-col justify-between group hover:bg-white transition-all duration-500">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Students</span>
                    <div class="w-10 h-10 rounded-xl bg-white/50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shadow-sm border border-white/50">
                        <i class="fas fa-user-graduate text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold tracking-tighter text-slate-900 tabular-nums font-heading">{{ number_format($totalUsers) }}</h3>
                <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Enrolled</div>
            </div>

            <div class="glass-panel rounded-[24px] p-6 flex flex-col justify-between group hover:bg-white transition-all duration-500">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest">Teachers</span>
                    <div class="w-10 h-10 rounded-xl bg-white/50 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300 shadow-sm border border-white/50">
                        <i class="fas fa-chalkboard-teacher text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold tracking-tighter text-slate-900 tabular-nums font-heading">{{ number_format($totalTeachers) }}</h3>
                <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Registered Staff</div>
            </div>

            <div class="glass-panel rounded-[24px] p-6 flex flex-col justify-between group hover:bg-white transition-all duration-500">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs font-bold text-amber-500 uppercase tracking-widest">Quizzes</span>
                    <div class="w-10 h-10 rounded-xl bg-white/50 text-amber-500 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300 shadow-sm border border-white/50">
                        <i class="fas fa-layer-group text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold tracking-tighter text-slate-900 tabular-nums font-heading">{{ number_format($totalQuizzes) }}</h3>
                <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Assessments</div>
            </div>

            <div class="glass-panel rounded-[24px] p-6 flex flex-col justify-between group hover:bg-white transition-all duration-500">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">Departments</span>
                    <div class="w-10 h-10 rounded-xl bg-white/50 text-rose-500 flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300 shadow-sm border border-white/50">
                        <i class="fas fa-building text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold tracking-tighter text-slate-900 tabular-nums font-heading">{{ number_format($totalDepartments) }}</h3>
                <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Academic Divisions</div>
            </div>

            <div class="glass-panel rounded-[24px] p-6 flex flex-col justify-between group hover:bg-white transition-all duration-500">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Bank Items</span>
                    <div class="w-10 h-10 rounded-xl bg-white/50 text-blue-500 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300 shadow-sm border border-white/50">
                        <i class="fas fa-database text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold tracking-tighter text-slate-900 tabular-nums font-heading">{{ number_format($totalBank) }}</h3>
                <div class="mt-2 text-[10px] font-bold text-blue-600 uppercase tracking-widest">Stored Assets</div>
            </div>
        </div>

        <!-- Admin Layout Structure -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8">
            <!-- Analytics Chart -->
            <div class="lg:col-span-2 glass-panel rounded-[24px] p-6 hover:bg-white transition-all duration-500">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tighter uppercase font-heading">Activity Analytics</h3>
                    <select class="text-sm font-medium text-slate-600 bg-transparent border-none p-0 focus:ring-0 cursor-pointer">
                        <option>Last 7 Days</option>
                    </select>
                </div>
                <div class="w-full relative h-[300px]">
                    <!-- Fallback if chart fails -->
                    @if(empty($weeklyActivity['labels']))
                    <div class="absolute inset-0 flex items-center justify-center text-sm font-medium text-slate-400">Insufficient data to display chart.</div>
                    @endif
                    <canvas id="analyticsChart"></canvas>
                </div>
            </div>

            <!-- Pending Tasks & Hierarchy -->
            <div class="glass-panel rounded-[24px] p-6 flex flex-col h-full hover:bg-white transition-all duration-500">
                <h3 class="text-base font-semibold text-slate-900 tracking-tight mb-5">Attention Required</h3>
                
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/50 mb-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $pendingReviews }} Drafts</p>
                            <p class="text-sm text-slate-500 mt-1 leading-relaxed">There are unpublished quizzes waiting for final review.</p>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-amber-500 mt-1"></span>
                    </div>
                </div>

                <h3 class="text-base font-semibold text-slate-900 tracking-tight mb-5">Depts. by Tractions</h3>
                <div class="space-y-5 flex-grow">
                    @forelse(array_slice($departmentStats, 0, 4) as $stat)
                        @php
                            $percentage = min(100, max(5, ($stat['user_count'] / max($totalUsers, 1)) * 100));
                        @endphp
                        <div>
                            <div class="flex justify-between items-baseline mb-1.5">
                                <span class="text-sm font-medium text-slate-700 truncate pr-4">{{ $stat['name'] }}</span>
                                <span class="text-xs font-medium text-slate-500 shrink-0">{{ $stat['user_count'] }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                <div class="bg-slate-900 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 text-center mt-4">No data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Admin Recent Feed -->
        <div class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tighter uppercase font-heading">Real-time Stream</h3>
                <a href="{{ route('quizzes.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">View All</a>
            </div>
            <div class="divide-y divide-slate-100/80">
                @forelse ($recentQuizzes as $quiz)
                    <div class="px-6 py-2.5 flex items-center justify-between hover:bg-slate-50/50 transition-colors group border-b border-slate-50 last:border-0">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-indigo-600/20 border border-indigo-500/20">
                                <i class="fas fa-file-alt text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $quiz['title'] }}</h4>
                                <p class="text-xs text-slate-400 mt-1 uppercase font-bold tracking-tight">Created by <span class="text-indigo-600">{{ $quiz['creator_name'] }}</span></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="px-2.5 py-1 rounded-md border text-[10px] font-bold uppercase tracking-widest {{ $quiz['status'] == 'published' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-indigo-50 border-indigo-100 text-indigo-700' }}">
                                {{ ucfirst($quiz['status']) }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest hidden sm:block w-32 text-right">{{ date('M d, Y', strtotime($quiz['created_at'])) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-slate-500">No recent activity detected.</div>
                @endforelse
            </div>
        </div>

    @elseif($userRole === 'teacher')
        <!-- Teacher View: Compact Portfolio Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5 mb-8">
            <div class="bg-white rounded-xl border border-slate-200/50 p-6 shadow-sm group hover:bg-slate-50 transition-all duration-300">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100/50 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <i class="fas fa-layer-group text-sm"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Inventory</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-none tabular-nums mt-1">{{ $myQuizzes }}</h3>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-50 text-amber-600 border border-amber-100/30 uppercase tracking-widest">{{ $draftQuizzes }} Staging</span>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200/50 p-6 shadow-sm group hover:bg-slate-50 transition-all duration-300">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/50 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <i class="fas fa-users-viewfinder text-sm"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Participation</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-none tabular-nums mt-1">{{ $totalAttempts }}</h3>
                    </div>
                </div>
                <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest flex items-center gap-1 group-hover:translate-x-1 transition-transform cursor-pointer">
                    View Submissions <i class="fas fa-chevron-right text-[8px]"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200/50 p-6 shadow-sm group hover:bg-slate-50 transition-all duration-300">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center border border-rose-100/50 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <i class="fas fa-bullseye text-sm"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-rose-600 uppercase tracking-widest">Performance Avg</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-none tabular-nums mt-1">{{ $avgScore }}%</h3>
                    </div>
                </div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Class Performance</div>
            </div>

            <a href="{{ route('questions.bank') }}" class="bg-indigo-600 rounded-xl p-6 shadow-xl shadow-indigo-600/20 group hover:bg-indigo-700 transition-all duration-300 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute right-0 bottom-0 opacity-10 group-hover:scale-110 transition-transform">
                    <i class="fas fa-database text-6xl text-white -mr-4 -mb-4"></i>
                </div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-white uppercase tracking-widest">Question Bank</h3>
                        <p class="text-[10px] font-bold text-indigo-100/60 mt-1 uppercase tracking-tight">Stored Assets</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center border border-white/20 backdrop-blur-sm">
                        <i class="fas fa-database text-sm"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Analytics Chart: Compact Height -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200/50 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-[13px] font-black text-slate-900 uppercase tracking-widest italic font-heading">Growth Trends</h3>
                        <p class="text-[10px] font-bold text-slate-400 mt-0.5">Attempt distribution frequency</p>
                    </div>
                    <div class="flex items-center gap-1.5 px-2 py-1 rounded bg-slate-950 border border-slate-900">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></div>
                        <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest italic italic">Live Sync Active</span>
                    </div>
                </div>
                <div class="w-full relative h-[260px]">
                    <canvas id="analyticsChart"></canvas>
                </div>
            </div>
            
            <!-- Recent Activity: High Density Feed -->
            <div class="bg-white rounded-xl border border-slate-200/50 shadow-sm flex flex-col overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-widest italic font-heading">Current Pulse</h3>
                    <div class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></div>
                </div>
                <div class="divide-y divide-slate-100/50 flex-grow overflow-y-auto custom-scrollbar" style="max-height: 380px;">
                    @forelse ($recentAttempts as $attempt)
                        <div class="px-6 py-3.5 hover:bg-slate-50/80 transition-all cursor-pointer group">
                            <div class="flex justify-between items-start mb-1.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[9px] font-black border border-slate-800 tabular-nums">
                                        {{ strtoupper(substr($attempt['student_name'], 0, 1)) }}
                                    </div>
                                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-tight group-hover:text-indigo-600 transition-colors italic">{{ $attempt['student_name'] }}</h4>
                                </div>
                                <span class="text-[9px] font-black px-1.5 py-0.5 rounded-md italic tabular-nums {{ $attempt['passed'] ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                    {{ $attempt['score'] }}%
                                </span>
                            </div>
                            <div class="ml-9">
                                <p class="text-[10px] font-black text-slate-900 truncate tracking-tight group-hover:text-indigo-600 transition-colors uppercase italic">{{ $attempt['quiz_title'] }}</p>
                                <p class="text-[9px] text-indigo-500 mt-0.5 font-bold tabular-nums italic uppercase">{{ \Carbon\Carbon::parse($attempt['started_at'])->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex-grow flex flex-col items-center justify-center p-8 text-center opacity-40">
                            <i class="fas fa-ghost text-slate-300 text-2xl mb-2"></i>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Feed Empty</p>
                        </div>
                    @endforelse
                </div>
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    <button class="w-full bg-white hover:bg-slate-100 text-slate-900 border border-slate-200 py-2.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all shadow-sm italic">Audit All Logs</button>
                </div>
            </div>
        </div>

    @elseif($userRole === 'student')

        <!-- Student Persona Section: Compact & High-Impact -->
        <div class="bg-indigo-600 rounded-[32px] p-8 md:p-10 shadow-2xl relative overflow-hidden mb-8">
            <div class="absolute right-0 top-0 w-80 h-80 bg-white/10 rounded-full blur-[80px] -mr-24 -mt-24"></div>
            <div class="absolute left-0 bottom-0 w-56 h-56 bg-emerald-400/10 rounded-full blur-[60px] -ml-20 -mb-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex-grow text-center md:text-left">
                    <span class="inline-block bg-white/20 text-white text-[10px] font-bold uppercase tracking-[0.2em] px-3 py-1 rounded-full mb-4 border border-white/10">Status: Synchronized</span>
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white tracking-tight leading-tight mb-2 uppercase">Hello, <span class="text-indigo-200">{{ $username }}</span>.</h2>
                    <p class="text-indigo-100 text-xs md:text-sm max-w-sm font-bold uppercase tracking-wide">You're currently in the top <span class="text-emerald-300 font-bold">15%</span> of your sector. Maintain consistent momentum.</p>
                </div>

                <div class="flex gap-3 md:gap-4 shrink-0">
                    <div class="bg-white/5 backdrop-blur-md border border-white/5 p-5 rounded-2xl min-w-[120px] text-center group hover:bg-white/10 transition-colors">
                        <p class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.15em] mb-2 italic">Performance</p>
                        <p class="text-2xl font-black text-white group-hover:scale-105 transition-transform tabular-nums italic">{{ $avgScore }}<span class="text-xs opacity-30 text-indigo-300 ml-0.5">%</span></p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/5 p-5 rounded-2xl min-w-[120px] text-center group hover:bg-white/10 transition-colors">
                        <p class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.15em] mb-2 italic">Completed</p>
                        <p class="text-2xl font-black text-white group-hover:scale-105 transition-transform tabular-nums italic">{{ $totalAttempts }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Learning Content: Compact Cards -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
                        <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight italic">Active Assessments</h3>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <span>Sort:</span>
                        <button class="text-indigo-600 hover:text-indigo-800 transition-colors">Newest <i class="fas fa-chevron-down text-[8px] ml-1"></i></button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @forelse($studentQuizzes as $quiz)
                        <a href="{{ route('students.quizzes.show', $quiz->id) }}" class="group bg-white rounded-[24px] p-1.5 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 overflow-hidden relative border border-slate-100">
                            <div class="bg-slate-50 rounded-[20px] p-5 h-full flex flex-col border border-white">
                                <div class="flex justify-between items-start mb-5">
                                    <span class="bg-white px-2.5 py-1 rounded-lg text-[9px] font-black text-slate-400 uppercase tracking-widest shadow-sm border border-slate-100/50">
                                        {{ $quiz->subject?->subject_name ?? 'Module' }}
                                    </span>
                                    <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center text-slate-300 group-hover:text-indigo-600 transition-all shadow-sm">
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </div>
                                </div>
                                <h4 class="text-[15px] font-black text-slate-900 mb-2 leading-snug group-hover:text-indigo-700 transition-colors line-clamp-1 uppercase italic tabular-nums">{{ $quiz->title }}</h4>
                                <p class="text-xs font-black text-slate-900 line-clamp-2 leading-relaxed opacity-80 mb-6 antialiased italic">{{ $quiz->description ?: 'Operational instructions for this module are standard.' }}</p>
                                
                                <div class="mt-auto pt-4 border-t border-slate-200/50 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic group-hover:text-indigo-400 transition-colors">
                                            <i class="far fa-copy text-[11px]"></i>
                                            <span>{{ $quiz->questions()->count() }} items</span>
                                        </div>
                                        @if($quiz->time_limit)
                                        <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                                        <div class="flex items-center gap-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic group-hover:text-amber-400 transition-colors">
                                            <i class="far fa-clock text-[11px]"></i>
                                            <span>{{ $quiz->time_limit }}m</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-600/20 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-play text-[8px] translate-x-0.5"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="md:col-span-2 bg-white rounded-[28px] border border-slate-100 p-12 text-center shadow-sm">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <i class="fas fa-wind text-xl"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest italic">No deployments found</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Check back later for active deployments.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Performance Sidebar -->
            <div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight mb-8">Recent Results</h3>
                <div class="bg-white border border-slate-200/60 rounded-[32px] p-2 shadow-sm overflow-hidden min-h-[500px] flex flex-col">
                    <div class="bg-slate-50 rounded-[28px] divide-y divide-slate-100 flex-grow h-full border border-white">
                        @forelse($recentAttempts as $attempt)
                            <div class="p-6 md:p-7 hover:bg-white transition-all group cursor-pointer first:rounded-t-[28px] last:rounded-b-[28px]">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="text-[10px] font-black text-indigo-600 uppercase tracking-widest italic">{{ date('M d, Y', strtotime($attempt['completed_at'])) }}</div>
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-xs font-black shadow-sm border {{ $attempt['passed'] ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                                        {{ $attempt['score'] }}%
                                    </div>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 leading-snug group-hover:text-indigo-700 transition-colors">{{ $attempt['quiz_title'] }}</h4>
                                <div class="mt-4 flex items-center gap-2">
                                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full {{ $attempt['passed'] ? 'bg-emerald-100/50 text-emerald-700' : 'bg-amber-100/50 text-amber-700' }}">
                                        {{ $attempt['passed'] ? 'PASSED' : 'RETRY' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center p-10 opacity-30 text-center grayscale">
                                <i class="fas fa-chart-line text-4xl mb-4"></i>
                                <p class="text-xs font-black uppercase tracking-widest">No stats recorded</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-8 bg-indigo-600 rounded-[32px] p-8 text-white relative overflow-hidden group cursor-pointer">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <i class="fas fa-graduation-cap text-2xl mb-4"></i>
                        <h4 class="text-lg font-bold mb-2">View Full Analytics</h4>
                        <p class="text-sm text-indigo-100 font-medium opacity-80 leading-relaxed">Detailed report on your learning progress and growth.</p>
                        <div class="mt-6 flex items-center gap-2 text-[10px] font-black uppercase tracking-widest">
                            <span>Open Dashboard</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
</div>

<!-- Chart Data Store -->
<div id="chart-data" class="hidden"
    data-labels='@json($weeklyActivity["labels"] ?? [])'
    data-quizzes='@json($weeklyActivity["quizzes"] ?? [])'
    data-attempts='@json($weeklyActivity["attempts"] ?? [])'
    data-users='@json(array_column($departmentStats ?? [], "user_count"))'
    data-dept-labels='@json(array_column($departmentStats ?? [], "name"))'>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('analyticsChart');
    if (!ctx) return;
    
    const dataContainer = document.getElementById('chart-data');
    if (!dataContainer) return;
    
    try {
        const labels = JSON.parse(dataContainer.dataset.labels || '[]');
        const quizzes = JSON.parse(dataContainer.dataset.quizzes || '[]');
        const attempts = JSON.parse(dataContainer.dataset.attempts || '[]');
        
        const context = ctx.getContext('2d');
        
        // Vibrant Indigo Gradient for modern aesthetic
        let gradient = context.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)'); // indigo-500 light
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
        
        const datasets = [];
        
        @if($userRole === 'admin')
        datasets.push({
            label: 'Quizzes Created',
            data: quizzes,
            borderColor: '#94a3b8', // slate-400
            backgroundColor: 'transparent',
            borderWidth: 2,
            pointRadius: 0,
            pointHoverRadius: 4,
            tension: 0.3
        });
        @endif

        datasets.push({
            label: '{{ $userRole == "student" ? "Attempts" : "Attempts" }}',
            data: attempts,
            backgroundColor: gradient,
            borderColor: '#6366f1', // indigo-500 vibrant contrast
            borderWidth: 3,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#6366f1', // indigo-500
            pointBorderWidth: 2,
            pointRadius: @if($userRole === 'student') 4 @else 0 @endif,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.4
        });

        new Chart(context, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: { 
                        display: false // Hide legend for extreme minimalism
                    },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#0f172a',
                        bodyColor: '#475569',
                        titleFont: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                        bodyFont: { family: "'Inter', sans-serif", size: 12, weight: '500' },
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        boxPadding: 4,
                        cornerRadius: 8,
                        shadowOffsetX: 0,
                        shadowOffsetY: 4,
                        shadowBlur: 10,
                        shadowColor: 'rgba(0,0,0,0.05)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: true, color: '#f1f5f9', drawBorder: false }, // very light slate-100
                        ticks: { precision: 0, color: '#94a3b8', font: { size: 11, family: "'Inter', sans-serif" }, padding: 10 }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 11, family: "'Inter', sans-serif" }, padding: 10 }
                    }
                }
            }
        });
    } catch(e) {
        console.error("Error drawing chart:", e);
    }
});
</script>
<style>
/* Clean scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
</style>
@endsection
