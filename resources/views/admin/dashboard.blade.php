@extends('layouts.admin')

@section('content')
<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #4f46e5;
    }
    
    /* Card hover effects */
    .stat-card {
        transition: all 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    
    /* Tab Button Styles - Segmented Control */
    .tab-btn {
        background: transparent;
        color: #64748b;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .tab-btn.active {
        background: white;
        color: #4f46e5;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    .tab-btn.active i {
        color: #4f46e5;
    }
    .tab-btn:not(.active):hover {
        background: rgba(255, 255, 255, 0.6);
        color: #1e293b;
    }
    .tab-btn:not(.active):hover i {
        color: #4f46e5;
    }
    
    /* Tab content animation */
    .tab-content {
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Quiz card styles */
    .quiz-card {
        transition: all 0.25s ease;
    }
    .quiz-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -8px rgba(0, 0, 0, 0.1);
    }
    
    /* History row hover */
    .history-row {
        transition: background-color 0.2s ease;
    }
    .history-row:hover {
        background-color: #f8fafc;
    }
</style>

<div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 font-inter text-slate-900 custom-scrollbar">

    @php
        // Quizzes routes are not namespaced under "admin." or "teacher." —
        // only students use the "students." prefix. Use no prefix for admin/teacher.
        $routePrefix = ($userRole === 'student') ? 'students.' : '';
    @endphp

    <!-- Header Section -->
    <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                @if($userRole === 'admin')
                    Admin Control Center
                @elseif($userRole === 'teacher')
                    Teacher Workspace
                @else
                    Student Dashboard
                @endif
            </h1>
            <div class="flex items-center gap-3 mt-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600">
                    <i class="fas fa-user-circle text-xs"></i>
                    {{ ucfirst($userRole) }}
                </span>
                <span class="text-xs text-slate-400">{{ now()->format('l, F j, Y') }}</span>
            </div>
        </div>
        
        @if($userRole === 'admin' || $userRole === 'teacher')
        <div class="flex items-center gap-3">
            <a href="{{ route($routePrefix . 'quizzes.create', [], false) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md hover:shadow-lg active:scale-95">
                <i class="fas fa-plus text-xs"></i>
                <span>Create Quiz</span>
            </a>
            @if($userRole === 'admin')
            <a href="{{ route('admin.settings.index', [], false) }}" 
               class="w-10 h-10 flex items-center justify-center bg-white hover:bg-slate-50 text-slate-400 hover:text-indigo-600 rounded-xl border border-slate-200 transition-all">
                <i class="fas fa-sliders-h text-sm"></i>
            </a>
            @endif
        </div>
        @endif
    </header>

    <!-- ========== ADMIN VIEW ========== -->
    @if ($userRole === 'admin')
    
    <!-- Admin Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        @php
            $adminStats = [
                ['label' => 'Total Students', 'value' => $totalUsers ?? 0, 'icon' => 'fa-user-graduate', 'color' => 'indigo'],
                ['label' => 'Teachers', 'value' => $totalTeachers ?? 0, 'icon' => 'fa-chalkboard-user', 'color' => 'emerald'],
                ['label' => 'Quizzes', 'value' => $totalQuizzes ?? 0, 'icon' => 'fa-layer-group', 'color' => 'amber'],
                ['label' => 'Departments', 'value' => $totalDepartments ?? 0, 'icon' => 'fa-building', 'color' => 'rose'],
                ['label' => 'Questions', 'value' => $totalBank ?? 0, 'icon' => 'fa-database', 'color' => 'blue'],
            ];
        @endphp
        @foreach($adminStats as $stat)
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm stat-card">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-bold text-{{ $stat['color'] }}-500 uppercase tracking-wide">{{ $stat['label'] }}</span>
                <div class="w-8 h-8 rounded-lg bg-{{ $stat['color'] }}-50 flex items-center justify-center">
                    <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ number_format($stat['value']) }}</p>
        </div>
        @endforeach
    </div>

    <!-- Admin Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Weekly Activity Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Platform Activity</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Weekly quiz attempts</p>
                </div>
                <span class="flex items-center gap-1.5 text-[10px] font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                    <i class="fas fa-chart-line text-[9px]"></i> Live
                </span>
            </div>
            <div class="h-[260px]">
                @php
                    $weeklyLabels = isset($weeklyActivity['labels']) ? $weeklyActivity['labels'] : [];
                    $weeklyAttempts = isset($weeklyActivity['attempts']) ? $weeklyActivity['attempts'] : [];
                    $hasActivity = !empty($weeklyAttempts) && is_array($weeklyAttempts) && array_sum($weeklyAttempts) > 0;
                @endphp

                @if(!$hasActivity)
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-3xl text-slate-200 mb-2"></i>
                        <p class="text-xs text-slate-400">No activity data available</p>
                    </div>
                </div>
                @endif

                <canvas 
                    id="activityChart"
                    style="display: {{ $hasActivity ? 'block' : 'none' }}">
                </canvas>
            </div>
        </div>

        <!-- Student Distribution & Recent Content -->
        <div class="space-y-5">
            <!-- Gender Distribution -->
            <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-800">Student Demographics</h3>
                    <i class="fas fa-users text-slate-300 text-sm"></i>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-32 h-32">
                        <canvas id="studentGenderChart"></canvas>
                    </div>
                </div>
                <div class="flex justify-center gap-6 mt-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-medium text-slate-600">Male: {{ number_format($studentGenderStats['Male'] ?? 0) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                        <span class="text-xs font-medium text-slate-600">Female: {{ number_format($studentGenderStats['Female'] ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Content -->
            <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-800">Recent Content</h3>
                    <i class="fas fa-clock text-slate-300 text-xs"></i>
                </div>
                <div class="space-y-3">
                    @php $recentItems = isset($recentQuizzes) && is_array($recentQuizzes) ? array_slice($recentQuizzes, 0, 3) : []; @endphp
                    @forelse($recentItems as $quiz)
                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer" 
                         onclick="window.location='{{ route($routePrefix . 'quizzes.show', $quiz['id'] ?? '#', false) }}'">
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                            @if(!empty($quiz['thumbnail']))
                                <img src="{{ asset('storage/' . $quiz['thumbnail']) }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <i class="fas fa-file-alt text-indigo-500 text-sm"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-700 truncate">{{ $quiz['title'] ?? 'Untitled' }}</p>
                            <p class="text-[9px] text-slate-400">{{ $quiz['creator_name'] ?? 'System' }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-slate-300 text-[10px]"></i>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-4">No recent content</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- ========== TEACHER VIEW ========== -->
    @if ($userRole === 'teacher')
    
    <!-- Teacher Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @php
            $teacherStats = [
                ['label' => 'My Quizzes', 'value' => $totalQuizzes ?? 0, 'icon' => 'fa-layer-group', 'color' => 'indigo'],
                ['label' => 'Total Attempts', 'value' => $totalAttempts ?? 0, 'icon' => 'fa-users', 'color' => 'emerald'],
                ['label' => 'Avg Score', 'value' => isset($avgScore) ? round($avgScore, 1).'%' : '0%', 'icon' => 'fa-chart-line', 'color' => 'amber'],
            ];
        @endphp
        @foreach($teacherStats as $stat)
        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm stat-card">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-slate-500">{{ $stat['label'] }}</span>
                <div class="w-9 h-9 rounded-lg bg-{{ $stat['color'] }}-50 flex items-center justify-center">
                    <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $stat['value'] }}</p>
        </div>
        @endforeach
        
        <!-- Top Performer Highlight -->
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl p-5 shadow-lg stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider">Top Performer</span>
                <i class="fas fa-crown text-amber-300 text-sm"></i>
            </div>
            <p class="text-base font-bold text-white mt-2 truncate">{{ isset($topPerformer) && $topPerformer && $topPerformer->user ? $topPerformer->user->username : 'N/A' }}</p>
            <p class="text-xs text-indigo-200 mt-1">Score: {{ isset($topPerformer) && $topPerformer ? ($topPerformer->score ?? 0) : 0 }}%</p>
        </div>
    </div>

    <!-- Recent Submissions Table -->
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-white">
            <h3 class="text-sm font-bold text-slate-800">Recent Submissions</h3>
            <p class="text-xs text-slate-400 mt-0.5">Student quiz attempts awaiting review</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Student</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Quiz</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Score</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php $recent = isset($recentAttempts) && is_array($recentAttempts) ? $recentAttempts : []; @endphp
                    @forelse($recent as $attempt)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 text-xs font-bold">
                                    {{ strtoupper(substr($attempt['student_name'] ?? 'U', 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ $attempt['student_name'] ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $attempt['quiz_title'] ?? 'N/A' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-bold {{ ($attempt['score'] ?? 0) >= 60 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ round($attempt['score'] ?? 0) }}%
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">
                            {{ !empty($attempt['completed_at']) ? \Carbon\Carbon::parse($attempt['completed_at'])->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route($routePrefix . 'quizzes.result', $attempt['id'] ?? 0, false) }}" class="w-7 h-7 inline-flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-colors">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <i class="fas fa-inbox text-2xl text-slate-300 mb-2 block"></i>
                            <p class="text-xs text-slate-400">No recent submissions</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- ========== STUDENT VIEW ========== -->
    @if ($userRole === 'student')
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-50 via-white to-indigo-50 rounded-2xl p-6 mb-8 border border-indigo-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Welcome back, {{ $username ?? 'Student' }}! 👋
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Ready to continue your learning journey? You have {{ isset($availableQuizzes) ? count($availableQuizzes) : 0 }} quiz{{ (isset($availableQuizzes) && count($availableQuizzes) != 1) ? 'zes' : '' }} waiting for you.
                </p>
            </div>
            @if(($streak ?? 0) > 0)
            <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 rounded-xl border border-amber-100">
                <i class="fas fa-fire text-amber-500 text-lg"></i>
                <div>
                    <p class="text-xs font-bold text-amber-600">{{ $streak }} Day Streak!</p>
                    <p class="text-[9px] text-amber-500">Keep it up! 🔥</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Student Stats Summary -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @php
            $studentStats = [
                ['label' => 'Quizzes Taken', 'value' => $totalAttempts ?? 0, 'icon' => 'fa-clipboard-list', 'bg' => 'indigo-50', 'text' => 'indigo-600'],
                ['label' => 'Passed', 'value' => $totalPassed ?? 0, 'icon' => 'fa-check-circle', 'bg' => 'emerald-50', 'text' => 'emerald-600'],
                ['label' => 'Average Score', 'value' => isset($avgScore) ? round($avgScore).'%' : '0%', 'icon' => 'fa-chart-simple', 'bg' => 'amber-50', 'text' => 'amber-600'],
                ['label' => 'Best Score', 'value' => ($highestScore ?? 0) . '%', 'icon' => 'fa-trophy', 'bg' => 'amber-50', 'text' => 'amber-600'],
            ];
        @endphp
        @foreach($studentStats as $stat)
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm stat-card">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 rounded-lg bg-{{ $stat['bg'] }} flex items-center justify-center">
                    <i class="fas {{ $stat['icon'] }} text-{{ $stat['text'] }} text-sm"></i>
                </div>
                <span class="text-xl font-bold text-slate-800">{{ $stat['value'] }}</span>
            </div>
            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- IMPROVED SEGMENTED TAB BUTTONS -->
    <div class="mb-6">
        <div class="bg-slate-100 rounded-xl p-1 inline-flex gap-1 shadow-sm">
            <button 
                id="tab-available" 
                class="tab-btn active px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center gap-2"
                onclick="switchTab('available')">
                <i class="fas fa-play-circle text-xs"></i>
                <span>Available Quizzes</span>
                @if(isset($availableQuizzes) && count($availableQuizzes) > 0)
                <span class="ml-1 px-2 py-0.5 text-[10px] font-bold bg-indigo-100 text-indigo-700 rounded-full">{{ count($availableQuizzes) }}</span>
                @endif
            </button>
            <button 
                id="tab-history" 
                class="tab-btn px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2"
                onclick="switchTab('history')">
                <i class="fas fa-history text-xs"></i>
                <span>History & Results</span>
                @if(isset($quizHistory) && count($quizHistory) > 0)
                <span class="ml-1 px-2 py-0.5 text-[10px] font-bold bg-slate-200 text-slate-600 rounded-full">{{ count($quizHistory) }}</span>
                @endif
            </button>
        </div>
    </div>

    <!-- Available Quizzes Tab Content -->
    <div id="available-tab" class="tab-content">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Available Quizzes List -->
            <div class="lg:col-span-2">
                @if(isset($availableQuizzes) && count($availableQuizzes) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($availableQuizzes as $quiz)
                    <a href="{{ route('students.quizzes.take', $quiz->id, false) }}" 
                       class="quiz-card group bg-white rounded-xl border border-slate-100 p-5 hover:shadow-md transition-all no-underline block">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-medium bg-emerald-50 text-emerald-600">
                                        <i class="fas fa-hourglass-half text-[8px]"></i> Ready
                                    </span>
                                    @if(($quiz->attempts_count ?? 0) > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-medium bg-amber-50 text-amber-600">
                                        <i class="fas fa-redo-alt text-[8px]"></i> Retake
                                    </span>
                                    @endif
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $quiz->title }}</h4>
                                <p class="text-[10px] font-medium text-slate-400 mt-1">{{ $quiz->subject->subject_name ?? 'General' }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                                <i class="fas fa-arrow-right text-indigo-500 group-hover:text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 pt-3 border-t border-slate-50">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-list-ul text-[9px] text-slate-400"></i>
                                <span class="text-[10px] font-medium text-slate-500">{{ $quiz->questions_count ?? 0 }} questions</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-clock text-[9px] text-slate-400"></i>
                                <span class="text-[10px] font-medium text-slate-500">{{ $quiz->time_limit ?? 30 }} min</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="bg-slate-50/50 rounded-xl border border-dashed border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-500 mb-1">All caught up! 🎉</p>
                    <p class="text-xs text-slate-400">No new quizzes available at the moment. Check back later!</p>
                </div>
                @endif
            </div>

            <!-- Student Profile Card -->
            <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm h-fit">
                <div class="text-center">
                    <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center text-2xl font-bold mx-auto shadow-lg">
                        {{ strtoupper(substr($username ?? 'S', 0, 1)) }}
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mt-3">{{ $username ?? 'Student' }}</h3>
                    <p class="text-[10px] font-medium text-indigo-500 uppercase tracking-wide">Student</p>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mt-5 pt-4 border-t border-slate-100">
                    <div class="bg-slate-50 rounded-lg p-2 text-center">
                        <p class="text-xs font-bold text-slate-700">{{ $totalAttempts ?? 0 }}</p>
                        <p class="text-[8px] font-medium text-slate-400 uppercase">Attempts</p>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-2 text-center">
                        <p class="text-xs font-bold text-indigo-600">{{ isset($avgScore) ? round($avgScore) : 0 }}%</p>
                        <p class="text-[8px] font-medium text-indigo-400 uppercase">Average</p>
                    </div>
                </div>
                
                @if(($totalPassed ?? 0) > 0)
                <div class="mt-4 p-2.5 bg-emerald-50 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-certificate text-emerald-500 text-sm"></i>
                    <span class="text-xs font-bold text-emerald-600">{{ $totalPassed }} Quiz{{ $totalPassed != 1 ? 'zes' : '' }} Passed!</span>
                </div>
                @endif
                
                @if(($highestScore ?? 0) >= 90)
                <div class="mt-2 p-2.5 bg-amber-50 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-star text-amber-500 text-sm"></i>
                    <span class="text-xs font-bold text-amber-600">Excellent!</span>
                </div>
                @endif
                
                <a href="{{ route('students.results', [], false) }}" 
                   class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-indigo-600 text-white rounded-lg text-xs font-semibold transition-all text-center">
                    <i class="fas fa-chart-line text-xs"></i>
                    View Full History
                </a>
            </div>
        </div>
    </div>

    <!-- History Tab Content -->
    <div id="history-tab" class="tab-content" style="display: none;">
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Quiz History</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Your completed quiz attempts and scores</p>
                    </div>
                    @if(isset($quizHistory) && count($quizHistory) > 0)
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-medium text-indigo-500 bg-indigo-50 px-2 py-1 rounded-full">{{ count($quizHistory) }} records</span>
                        <span class="text-[10px] font-medium text-emerald-500 bg-emerald-50 px-2 py-1 rounded-full">
                            Passed: {{ $totalPassed ?? 0 }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            
            @if(isset($quizHistory) && count($quizHistory) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Quiz</th>
                            <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Subject</th>
                            <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Score</th>
                            <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Grade</th>
                            <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Completed</th>
                            <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($quizHistory as $attempt)
                        <tr class="history-row">
                            <td class="px-5 py-3">
                                <div class="font-semibold text-slate-800 text-sm">{{ $attempt->quiz->title ?? 'Unknown Quiz' }}</div>
                            </td>
                            <td class="px-5 py-3 text-xs text-slate-500">
                                {{ $attempt->quiz->subject->subject_name ?? 'General' }}
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-bold {{ ($attempt->score ?? 0) >= 60 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ round($attempt->score ?? 0) }}%
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $score = $attempt->score ?? 0;
                                    if($score >= 90) $letter = 'A';
                                    elseif($score >= 80) $letter = 'B';
                                    elseif($score >= 70) $letter = 'C';
                                    elseif($score >= 60) $letter = 'D';
                                    else $letter = 'F';
                                    
                                    $gradeColor = match($letter) {
                                        'A' => 'bg-emerald-100 text-emerald-700',
                                        'B' => 'bg-blue-100 text-blue-700',
                                        'C' => 'bg-amber-100 text-amber-700',
                                        'D' => 'bg-orange-100 text-orange-700',
                                        default => 'bg-rose-100 text-rose-700',
                                    };
                                @endphp
                                <span class="inline-flex w-7 h-7 rounded-lg items-center justify-center text-xs font-bold {{ $gradeColor }}">
                                    {{ $letter }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-xs text-slate-500">
                                {{ $attempt->completed_at ? \Carbon\Carbon::parse($attempt->completed_at)->format('M d, Y') : '—' }}
                                <div class="text-[9px] text-slate-400">{{ $attempt->completed_at ? \Carbon\Carbon::parse($attempt->completed_at)->format('g:i A') : '' }}</div>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('students.quizzes.result', $attempt->id, false) }}" 
                                   class="w-7 h-7 inline-flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-colors"
                                   title="View Details">
                                    <i class="fas fa-eye text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clipboard-list text-2xl text-slate-300"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">No quiz history yet</p>
                <p class="text-xs text-slate-400 mt-1">Complete your first quiz to see results here!</p>
                <button onclick="switchTab('available')" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-play-circle text-xs"></i> Browse Available Quizzes
                </button>
            </div>
            @endif
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const availableTab = document.getElementById('available-tab');
            const historyTab = document.getElementById('history-tab');
            const tabAvailable = document.getElementById('tab-available');
            const tabHistory = document.getElementById('tab-history');
            
            if (tab === 'available') {
                availableTab.style.display = 'block';
                historyTab.style.display = 'none';
                tabAvailable.classList.add('active');
                tabHistory.classList.remove('active');
                tabAvailable.classList.add('text-indigo-600', 'font-semibold');
                tabAvailable.classList.remove('text-slate-500', 'font-medium');
                tabHistory.classList.remove('text-indigo-600', 'font-semibold');
                tabHistory.classList.add('text-slate-500', 'font-medium');
            } else {
                availableTab.style.display = 'none';
                historyTab.style.display = 'block';
                tabHistory.classList.add('active');
                tabAvailable.classList.remove('active');
                tabHistory.classList.add('text-indigo-600', 'font-semibold');
                tabHistory.classList.remove('text-slate-500', 'font-medium');
                tabAvailable.classList.remove('text-indigo-600', 'font-semibold');
                tabAvailable.classList.add('text-slate-500', 'font-medium');
            }
            
            localStorage.setItem('studentDashboardTab', tab);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const savedTab = localStorage.getItem('studentDashboardTab');
            if (savedTab === 'history') {
                switchTab('history');
            }
        });
    </script>
    @endif

</div>

<!-- Chart Scripts (Only load when needed) -->
@if($userRole === 'admin' && isset($weeklyActivity['labels']) && !empty($weeklyActivity['labels']) && isset($weeklyActivity['attempts']) && is_array($weeklyActivity['attempts']) && array_sum($weeklyActivity['attempts']) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Weekly Activity Chart
    const activityCtx = document.getElementById('activityChart');
    if (activityCtx) {
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: @json($weeklyActivity['labels']),
                datasets: [{
                    label: 'Attempts',
                    data: @json($weeklyActivity['attempts']),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79,70,229,.05)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { stepSize: 1, font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    }

    // Gender Chart
    const genderCtx = document.getElementById('studentGenderChart');
    if (genderCtx) {
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [{{ $studentGenderStats['Male'] ?? 0 }}, {{ $studentGenderStats['Female'] ?? 0 }}],
                    backgroundColor: ['#3b82f6', '#eab308'],
                    borderWidth: 0,
                    cutout: '65%'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endif

@if($userRole === 'student')
<script>
document.querySelectorAll('a[href*="/take/"]').forEach(link => {
    link.addEventListener('mouseenter', () => {
        const prefetch = document.createElement('link');
        prefetch.rel = 'prefetch';
        prefetch.href = link.href;
        document.head.appendChild(prefetch);
    }, { once: true });
});
</script>
@endif

@endsection