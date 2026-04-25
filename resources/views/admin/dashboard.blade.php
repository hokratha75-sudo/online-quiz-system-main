@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 py-4 md:px-10 lg:py-6 font-inter text-slate-900">

    <!-- Header Section: High-Density Authority -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight leading-none">Dashboard Overview</h1>
            <p class="text-[10px] font-bold text-indigo-600 mt-2 uppercase tracking-[0.2em] leading-none opacity-80">Logged In As: {{ strtoupper($username) }} • Active Session</p>
        </div>
        @if($userRole === 'admin' || $userRole === 'teacher')
        <div class="flex items-center gap-3">
            <a href="{{ route('quizzes.create') ?? '#' }}" class="no-underline bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-600/20 active:scale-[0.98] group">
                <i class="fas fa-plus text-[10px] group-hover:rotate-90 transition-transform duration-300"></i>
                <span>Create New Quiz</span>
            </a>
            @if($userRole === 'admin')
            <a href="{{ route('admin.settings.index') }}" class="w-[52px] h-[52px] bg-white hover:bg-slate-50 text-slate-400 hover:text-indigo-600 border border-slate-100 rounded-2xl flex items-center justify-center transition-all shadow-sm active:scale-95 group" title="System Settings">
                <i class="fas fa-cog text-lg group-hover:rotate-90 transition-transform duration-500"></i>
            </a>
            @endif
        </div>
        @endif
    </header>

    @if ($userRole === 'admin')
        <!-- Admin Metrics Matrix -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-12" aria-label="Operational Metrics">
            <div class="bg-white rounded-[28px] p-8 border border-slate-50 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Total Students</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-user-graduate text-sm"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold tracking-tighter text-slate-950 tabular-nums leading-none">{{ number_format($totalUsers) }}</h3>
                <div class="mt-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">Registered Accounts</div>
            </div>

            <div class="bg-white rounded-[28px] p-8 border border-slate-50 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Total Teachers</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-chalkboard-teacher text-sm"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold tracking-tighter text-slate-950 tabular-nums leading-none">{{ number_format($totalTeachers) }}</h3>
                <div class="mt-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">Active Instructors</div>
            </div>

            <div class="bg-white rounded-[28px] p-8 border border-slate-50 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-sm font-semibold text-slate-600">Total Quizzes</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-layer-group text-sm"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums leading-none">{{ number_format($totalQuizzes) }}</h3>
                <div class="mt-2 text-xs font-medium text-slate-400">Created Assessments</div>
            </div>

            <div class="bg-white rounded-[28px] p-8 border border-slate-50 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-sm font-semibold text-slate-600">Departments</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-building text-sm"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums leading-none">{{ number_format($totalDepartments) }}</h3>
                <div class="mt-2 text-xs font-medium text-slate-400">Academic Divisions</div>
            </div>

            <div class="bg-white rounded-[28px] p-8 border border-slate-50 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-sm font-semibold text-slate-600">Question Bank</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-database text-sm"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums leading-none">{{ number_format($totalBank) }}</h3>
                <div class="mt-2 text-xs font-medium text-slate-400">Reusable Questions</div>
            </div>
        </section>

        <!-- Admin Analytical Context -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Global Activity Analytics -->
            <article class="lg:col-span-2 bg-white rounded-[32px] p-8 border border-slate-50 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-sm font-bold text-slate-900">Weekly Platform Activity</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-semibold text-slate-500">Live Updates</span>
                    </div>
                </div>
                <div class="w-full relative h-[300px]">
                    @if(empty($weeklyActivity['labels']))
                    <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-slate-400">No activity recorded yet.</div>
                    @else
                    <canvas id="activityChart"></canvas>
                    @endif
                </div>
            </article>

            <!-- Deployment Feed -->
            <aside class="bg-white rounded-[32px] p-8 border border-slate-50 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 mb-8">Recently Added Quizzes</h3>
                <div class="space-y-6">
                    @forelse($recentQuizzes as $quiz)
                    <div class="flex items-start gap-4 group cursor-pointer" onclick="window.location='{{ route('quizzes.show', $quiz['id'] ?? 0) }}'">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50/50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all border border-indigo-100/50">
                            <i class="fas fa-layer-group text-sm"></i>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-semibold text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $quiz['title'] ?? 'Untitled Quiz' }}</h4>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">Author: {{ $quiz['creator_name'] ?? 'Unknown' }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold capitalize shadow-sm border {{ strtolower($quiz['status'] ?? 'draft') === 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-600 border-slate-100' }}">
                            {{ $quiz['status'] ?? 'Draft' }}
                        </span>
                    </div>
                    @empty
                    <div class="py-12 text-center text-xs font-semibold text-slate-400 uppercase tracking-widest">No recent quizzes found.</div>
                    @endforelse
                </div>
            </aside>
        </div>
    @endif

    @if ($userRole === 'teacher')
        <!-- Teacher Specialized Matrix -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-[24px] p-8 border border-slate-50 shadow-sm group">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-sm font-semibold text-slate-600">My Quizzes</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                        <i class="fas fa-layer-group text-sm"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums leading-none">{{ number_format($totalQuizzes) }}</h3>
                <div class="mt-2 text-xs font-medium text-slate-400">Assigned Modules</div>
            </div>

            <div class="bg-white rounded-[24px] p-8 border border-slate-50 shadow-sm group">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-sm font-semibold text-emerald-600">Total Attempts</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm">
                        <i class="fas fa-sync-alt text-sm"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums leading-none">{{ number_format($totalAttempts) }}</h3>
                <div class="mt-2 text-xs font-medium text-slate-400">Student Enrollments</div>
            </div>

            <div class="bg-white rounded-[24px] p-8 border border-slate-50 shadow-sm group">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-sm font-semibold text-amber-600">Average Score</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shadow-sm">
                        <i class="fas fa-chart-line text-sm"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums leading-none">{{ round($avgScore, 1) }}%</h3>
                <div class="mt-2 text-xs font-medium text-slate-400">Class Performance</div>
            </div>

            <div class="bg-indigo-600 rounded-[24px] p-8 shadow-xl shadow-indigo-600/20 group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10"><i class="fas fa-bolt text-4xl text-white"></i></div>
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <span class="text-xs font-semibold text-indigo-100 uppercase tracking-wider">Top Student</span>
                    <div class="w-9 h-9 rounded-xl bg-white/20 text-white flex items-center justify-center border border-white/20 backdrop-blur-sm">
                        <i class="fas fa-crown text-xs"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-white truncate relative z-10 tracking-tight leading-none">{{ $topPerformer->user?->username ?? 'N/A' }}</h3>
                <div class="mt-2 text-xs font-medium text-indigo-100/80 relative z-10 leading-none">Highest Score: {{ $topPerformer?->score ?? 0 }}%</div>
            </div>
        </section>
        
        <!-- Teacher Management Node -->
        <article class="bg-white rounded-[32px] p-10 border border-slate-50 shadow-sm overflow-hidden mb-12">
            <h3 class="text-sm font-bold text-slate-900 mb-8">Recent Student Submissions</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Quiz Name</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Date Submitted</th>
                            <th class="text-right py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentAttempts as $attempt)
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold uppercase shadow-sm border border-indigo-100/50">
                                        {{ strtoupper(substr($attempt['student_name'] ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="text-sm font-semibold text-slate-900 tracking-tight">{{ $attempt['student_name'] ?? 'Unknown' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900 tracking-tight">{{ $attempt['quiz_title'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold tabular-nums {{ ($attempt['score'] ?? 0) >= 60 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                    {{ round($attempt['score'] ?? 0) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-medium text-slate-500">{{ !empty($attempt['completed_at']) ? \Carbon\Carbon::parse($attempt['completed_at'])->format('M d, Y') : 'N/A' }}</div>
                            </td>
                            <td class="text-right py-4">
                                <a href="{{ route('quizzes.result', $attempt['id'] ?? 0) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all active:scale-95 shadow-sm" title="Review">
                                    <i class="fas fa-arrow-right text-[13px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-16 text-center text-xs font-medium text-slate-400 uppercase tracking-wider">No recent submissions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    @endif

    @if ($userRole === 'student')
        <!-- Student Evolution Matrix -->
        <section class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
            <div class="lg:col-span-3">
                <header class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-bold text-slate-900">Available Quizzes</h3>
                    <p class="text-xs font-medium text-indigo-600">Quizzes waiting to be completed</p>
                </header>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($studentQuizzes as $quiz)
                    <div class="bg-white rounded-[28px] p-8 border border-slate-50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group relative overflow-hidden">
                        <div class="flex justify-between items-start mb-8 relative z-10">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                                <i class="fas fa-scroll text-lg"></i>
                            </div>
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                                {{ $quiz->questions_count ?? 0 }} Items
                            </span>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 tracking-tight mb-1 group-hover:text-indigo-600 transition-colors">{{ $quiz->title }}</h4>
                        <p class="text-xs font-medium text-slate-500 mb-8">{{ $quiz->subject?->subject_name ?? 'General Quiz' }}</p>
                        
                        <a href="{{ route('students.quizzes.take', $quiz->id) }}" class="inline-flex items-center gap-3 text-[10px] font-bold text-indigo-600 uppercase tracking-[0.2em] group-hover:gap-5 transition-all">
                            Start Quiz <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @empty
                    <div class="col-span-full py-32 bg-slate-50/50 rounded-[40px] border border-dashed border-slate-200 text-center">
                        <i class="fas fa-search text-3xl text-slate-200 mb-6"></i>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No available assessment vectors detected.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Student Profile Hub -->
            <aside class="bg-white rounded-[32px] p-10 border border-slate-100 shadow-sm flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-[32px] bg-indigo-600 text-white flex items-center justify-center text-3xl font-bold uppercase shadow-2xl shadow-indigo-600/30 border-4 border-white mb-8">
                    {{ strtoupper(substr($username, 0, 1)) }}
                </div>
                <h3 class="text-xl font-bold text-slate-950 tracking-tight uppercase mb-1">{{ $username }}</h3>
                <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mb-10">Student Profile</p>
                
                <div class="w-full grid grid-cols-2 gap-4 mb-10">
                    <div class="bg-slate-50 p-4 rounded-2xl text-center border border-slate-100">
                        <div class="text-lg font-bold text-slate-950 tabular-nums leading-none">{{ $totalAttempts }}</div>
                        <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Quizzes Taken</div>
                    </div>
                    <div class="bg-indigo-50 p-4 rounded-2xl text-center border border-indigo-100">
                        <div class="text-lg font-bold text-indigo-600 tabular-nums leading-none">{{ round($avgScore) }}%</div>
                        <div class="text-[8px] font-bold text-indigo-400 uppercase tracking-widest mt-1">Average Score</div>
                    </div>
                </div>

                <div class="w-full">
                    <a href="{{ route('students.results') }}" class="block w-full h-14 bg-slate-950 hover:bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-[10px] font-bold uppercase tracking-[0.2em] transition-all shadow-xl shadow-slate-900/10 active:scale-95">
                        Performance History
                    </a>
                </div>
            </aside>
        </section>
    @endif

</div>

@if($userRole === 'student')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // High Performance: Prefetch quiz assets on hover for student cards
        const quizCards = document.querySelectorAll('a[href*="/take/"]');
        quizCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                const link = document.createElement('link');
                link.rel = 'prefetch';
                link.href = card.href;
                document.head.appendChild(link);
                console.log('⚡ Prefetching: ' + card.href);
            }, { once: true });
        });
    });
</script>
@endif

@if(!empty($weeklyActivity['labels']))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('activityChart');
        if(!ctx) return;

        // Global Chart Defaults
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#94a3b8';

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($weeklyActivity['labels']),
                datasets: [{
                    label: 'System Syncs',
                    data: @json($weeklyActivity['attempts'] ?? []),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5], color: '#f1f5f9', drawBorder: false },
                        ticks: { font: { size: 10, weight: 'bold' }, padding: 10 }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' }, padding: 10 }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
