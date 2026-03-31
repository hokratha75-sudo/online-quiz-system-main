@extends('layouts.admin')

@section('content')
<!-- Extremely Clean, Vercel/Linear Inspired SaaS aesthetic -->
<div class="max-w-[1400px] mx-auto px-6 py-8 md:px-10 lg:py-10 font-inter">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-semibold text-slate-900 tracking-tight">Overview</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Welcome back, {{ $username }}. Here's your platform summary.</p>
        </div>
        @if($userRole === 'admin' || $userRole === 'teacher')
        <div class="flex gap-3">
            <a href="{{ route('quizzes.create') ?? '#' }}" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center gap-2 shadow-[0_1px_2px_rgba(0,0,0,0.05)]">
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
            <div class="bg-white rounded-[16px] border border-slate-200/70 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col justify-between group">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-semibold text-slate-500 tracking-wide">Students</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-user-graduate text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-semibold tracking-tight text-slate-900">{{ number_format($totalUsers) }}</h3>
                <div class="mt-2 text-[11px] font-medium text-emerald-600 flex items-center gap-1"><i class="fas fa-arrow-up"></i> Active Users</div>
            </div>
            <div class="bg-white rounded-[16px] border border-slate-200/70 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col justify-between group">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-semibold text-slate-500 tracking-wide">Teachers</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-chalkboard-teacher text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-semibold tracking-tight text-slate-900">{{ number_format($totalTeachers) }}</h3>
                <div class="mt-2 text-[11px] font-medium text-slate-400">Registered Staff</div>
            </div>
            <div class="bg-white rounded-[16px] border border-slate-200/70 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col justify-between group">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-semibold text-slate-500 tracking-wide">Quizzes</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-layer-group text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-semibold tracking-tight text-slate-900">{{ number_format($totalQuizzes) }}</h3>
                <div class="mt-2 text-[11px] font-medium text-slate-400">Total Assessments</div>
            </div>
            <div class="bg-white rounded-[16px] border border-slate-200/70 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col justify-between group">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-semibold text-slate-500 tracking-wide">Departments</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-building text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-semibold tracking-tight text-slate-900">{{ number_format($totalDepartments) }}</h3>
                <div class="mt-2 text-[11px] font-medium text-slate-400">Academic Divisions</div>
            </div>
            <div class="bg-white rounded-[16px] border border-slate-200/70 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col justify-between group sm:col-span-2 lg:col-span-1">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-semibold text-slate-500 tracking-wide">Bank Items</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-database text-sm"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-semibold tracking-tight text-slate-900">{{ number_format($totalBank) }}</h3>
                <div class="mt-2 text-[11px] font-medium text-indigo-600 flex items-center gap-1">Reusable items</div>
            </div>
        </div>

        <!-- Admin Layout Structure -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8">
            <!-- Analytics Chart -->
            <div class="lg:col-span-2 bg-white rounded-[20px] border border-slate-200/70 p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Engagement Trend</h3>
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
            <div class="bg-white rounded-[20px] border border-slate-200/70 p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col h-full">
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
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-base font-semibold text-slate-900 tracking-tight">Recent Activity Feed</h3>
                <a href="{{ route('quizzes.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">View All</a>
            </div>
            <div class="divide-y divide-slate-100/80">
                @forelse ($recentQuizzes as $quiz)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50 transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200/50">
                                <i class="fas fa-file-alt text-[10px]"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-slate-800">{{ $quiz['title'] }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Created by <span class="font-medium text-slate-700">{{ $quiz['creator_name'] }}</span></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-md border {{ $quiz['status'] == 'published' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
                                {{ ucfirst($quiz['status']) }}
                            </span>
                            <span class="text-xs text-slate-400 group-hover:text-slate-600 transition-colors hidden sm:block w-20 text-right">{{ date('M d, Y', strtotime($quiz['created_at'])) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-slate-500">No recent activity detected.</div>
                @endforelse
            </div>
        </div>

    @elseif($userRole === 'teacher')
        <!-- Teacher View -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="bg-white rounded-[16px] border border-slate-200/70 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-medium text-slate-500 tracking-wide">My Quizzes</span>
                    <i class="fas fa-book text-slate-400 text-xs"></i>
                </div>
                <h3 class="text-3xl font-semibold tracking-tight text-slate-900">{{ $myQuizzes }}</h3>
                <div class="mt-2 text-[11px] font-medium text-amber-600">{{ $draftQuizzes }} in Draft</div>
            </div>
            <div class="bg-white rounded-[16px] border border-slate-200/70 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-medium text-slate-500 tracking-wide">Total Attempts</span>
                    <i class="fas fa-users text-slate-400 text-xs"></i>
                </div>
                <h3 class="text-3xl font-semibold tracking-tight text-slate-900">{{ $totalAttempts }}</h3>
                <div class="mt-2 text-[11px] font-medium text-slate-400">Submissions received</div>
            </div>
            <div class="bg-white rounded-[16px] border border-slate-200/70 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-medium text-slate-500 tracking-wide">Avg Score</span>
                    <i class="fas fa-chart-bar text-slate-400 text-xs"></i>
                </div>
                <h3 class="text-3xl font-semibold tracking-tight text-slate-900">{{ $avgScore }}%</h3>
                <div class="mt-2 text-[11px] font-medium text-slate-400">Class Performance</div>
            </div>
            <a href="{{ route('questions.bank') }}" class="group bg-slate-900 rounded-[16px] border border-slate-800 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.1)] flex flex-col justify-between hover:bg-slate-800 transition-all cursor-pointer">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-medium text-slate-400 tracking-wide group-hover:text-slate-300">Question Bank</span>
                    <i class="fas fa-arrow-right text-slate-500 text-xs group-hover:translate-x-1 transition-transform"></i>
                </div>
                <h3 class="text-lg font-semibold tracking-tight text-white mb-1">Manage Bank</h3>
                <div class="mt-auto text-[11px] font-medium text-slate-400">Build reusable content</div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 min-h-[400px]">
            <!-- Chart Context -->
            <div class="lg:col-span-2 bg-white rounded-[20px] border border-slate-200/70 p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col">
                <h3 class="text-base font-semibold text-slate-900 tracking-tight mb-6">Student Activity</h3>
                <div class="w-full relative flex-grow min-h-[250px]">
                    <canvas id="analyticsChart"></canvas>
                </div>
            </div>
            
            <!-- Recent Subs -->
            <div class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Recent Submissions</h3>
                </div>
                <div class="divide-y divide-slate-100/80 overflow-y-auto w-full custom-scrollbar" style="max-height: 380px;">
                    @forelse ($recentAttempts as $attempt)
                        <div class="px-5 py-4 hover:bg-slate-50/50 transition-colors">
                            <div class="flex justify-between items-start mb-1.5">
                                <h4 class="text-sm font-semibold text-slate-800">{{ $attempt['student_name'] }}</h4>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $attempt['passed'] ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $attempt['score'] }}%
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 truncate mb-1 border-l-2 border-slate-200 pl-2 ml-1">Quiz: {{ $attempt['quiz_title'] }}</p>
                            <p class="text-[10px] text-slate-400 ml-3">{{ \Carbon\Carbon::parse($attempt['started_at'])->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center text-sm text-slate-500">No submissions yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

    @elseif($userRole === 'student')

        <!-- Minimalist Student Dashboard -->
        <div class="bg-white rounded-[20px] border border-slate-200/70 p-8 shadow-[0_2px_10px_rgba(0,0,0,0.02)] mb-8 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-slate-50 rounded-full blur-3xl -mr-20 -mt-20"></div>
            
            <div class="relative z-10 max-w-xl">
                <h2 class="text-2xl font-semibold text-slate-900 tracking-tight leading-snug">You have <span class="bg-indigo-100 text-indigo-700 font-bold px-2 py-0.5 rounded-md mx-1">{{ count($studentQuizzes) }} new</span> {{ Str::plural('assessment', count($studentQuizzes)) }} waiting for you.</h2>
                <p class="text-sm text-slate-500 mt-3 font-medium leading-relaxed">Stay on top of your coursework. Review your pending tasks below and achieve greatness.</p>
            </div>

            <div class="relative z-10 flex gap-4 w-full md:w-auto mt-2 md:mt-0">
                <div class="flex-1 md:flex-initial text-center bg-slate-50 border border-slate-100 rounded-2xl p-4 min-w-[120px]">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Avg Score</p>
                    <p class="text-2xl font-bold text-slate-900 tracking-tight">{{ $avgScore }}<span class="text-sm text-slate-500">%</span></p>
                </div>
                <div class="flex-1 md:flex-initial text-center bg-slate-50 border border-slate-100 rounded-2xl p-4 min-w-[120px]">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Completed</p>
                    <p class="text-2xl font-bold text-slate-900 tracking-tight">{{ $totalAttempts }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <div class="lg:col-span-2">
                <h3 class="text-base font-semibold text-slate-900 tracking-tight mb-5">Pending Assessments</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($studentQuizzes as $quiz)
                        <a href="{{ route('students.quizzes.show', $quiz->id) }}" class="group block bg-white rounded-2xl border border-slate-200/70 p-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-indigo-200 transition-all">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">{{ $quiz->subject?->subject_name ?? 'General' }}</span>
                                @if($quiz->time_limit)
                                    <span class="text-[11px] font-medium text-slate-400 border border-slate-100 px-2 py-0.5 rounded-md bg-slate-50"><i class="far fa-clock mr-1"></i>{{ $quiz->time_limit }}m</span>
                                @endif
                            </div>
                            <h4 class="text-base font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug mb-2">{{ $quiz->title }}</h4>
                            <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed h-10">{{ $quiz->description ?: 'No description provided.' }}</p>
                            
                            <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center text-xs font-medium">
                                <span class="text-slate-400">{{ $quiz->questions()->count() }} items</span>
                                <span class="text-indigo-600 group-hover:translate-x-1 transition-transform flex items-center gap-1">View Details <i class="fas fa-arrow-right text-[10px]"></i></span>
                            </div>
                        </a>
                    @empty
                        <div class="md:col-span-2 bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-10 text-center">
                            <p class="text-sm font-medium text-slate-500">You're all caught up!</p>
                        </div>
                    @endforelse
                </div>
                
                <h3 class="text-base font-semibold text-slate-900 tracking-tight mt-10 mb-5">Performance Trend</h3>
                <div class="bg-white rounded-2xl border border-slate-200/70 p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)] h-[250px]">
                    <canvas id="analyticsChart"></canvas>
                </div>
            </div>

            <div>
                <h3 class="text-base font-semibold text-slate-900 tracking-tight mb-5">Recent Results</h3>
                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden">
                    <div class="divide-y divide-slate-100/80">
                        @forelse($recentAttempts as $attempt)
                            <div class="p-5 flex items-start gap-4 hover:bg-slate-50/50 transition-colors cursor-default">
                                <div class="w-8 h-8 rounded-full border flex items-center justify-center shrink-0 mt-0.5 {{ $attempt['passed'] ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                                    <i class="fas {{ $attempt['passed'] ? 'fa-check' : 'fa-minus' }} text-[10px]"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900 leading-tight mb-1">{{ $attempt['quiz_title'] }}</h4>
                                    <p class="text-xs font-medium {{ $attempt['passed'] ? 'text-emerald-600' : 'text-slate-500' }}">Score: {{ $attempt['score'] }}%</p>
                                    <p class="text-[10px] text-slate-400 mt-1.5">{{ date('M d, Y', strtotime($attempt['completed_at'])) }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-sm text-slate-500">No tests taken yet.</div>
                        @endforelse
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
