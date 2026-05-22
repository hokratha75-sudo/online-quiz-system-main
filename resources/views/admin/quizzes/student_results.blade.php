@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50/60">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 lg:py-10">

        <!-- Welcome Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
                    Student Dashboard
                </h1>
                <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-indigo-400 text-xs"></i>
                    {{ now()->format('l, F j, Y') }}
                </p>
            </div>
            <a href="{{ route('students.dashboard') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md hover:shadow-lg shadow-indigo-200">
                <i class="fas fa-play-circle text-xs"></i>
                Start New Quiz
            </a>
        </div>

        <!-- Stats Grid - 4 Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-5 mb-8">
            @php
                $stats = [
                    ['label' => 'Total Quizzes', 'value' => $totalQuizzesTaken, 'icon' => 'fas fa-clipboard-list', 'color' => 'indigo', 'trend' => '+12%'],
                    ['label' => 'Passed', 'value' => $totalPassed, 'icon' => 'fas fa-check-circle', 'color' => 'emerald', 'trend' => '+8%'],
                    ['label' => 'Average Score', 'value' => round($avgScore).'%', 'icon' => 'fas fa-chart-line', 'color' => 'amber', 'trend' => '+5%'],
                    ['label' => 'Highest Score', 'value' => $highestScore.'%', 'icon' => 'fas fa-crown', 'color' => 'purple', 'trend' => 'PB!'],
                ];
            @endphp
            @foreach($stats as $stat)
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] }}-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="{{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-base"></i>
                    </div>
                    <span class="text-[10px] font-bold text-{{ $stat['color'] }}-500 bg-{{ $stat['color'] }}-50 px-2 py-0.5 rounded-full">
                        {{ $stat['trend'] }}
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl md:text-3xl font-bold text-slate-800 tabular-nums">{{ $stat['value'] }}</span>
                    <p class="text-xs font-medium text-slate-400 mt-1">{{ $stat['label'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Two Column Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Performance Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Performance Trend</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Score progression across all attempts</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1.5 text-[10px] font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg">
                            <i class="fas fa-chart-simple text-indigo-400"></i>
                            <span>Last 7 attempts</span>
                        </div>
                        @if($passRate >= 70)
                            <span class="flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                                <i class="fas fa-arrow-up text-[9px]"></i> {{ round($passRate) }}% Pass
                            </span>
                        @endif
                    </div>
                </div>
                <div class="h-[280px] relative">
                    <canvas id="scoreTrendChart"></canvas>
                </div>
            </div>

            <!-- Grade Distribution -->
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Grade Distribution</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Performance breakdown</p>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <i class="fas fa-chart-simple text-indigo-500 text-xs"></i>
                    </div>
                </div>
                
                @php
                    $gradeConfig = [
                        'A+' => ['color' => '#4f46e5', 'bg' => 'indigo'],
                        'A' => ['color' => '#6366f1', 'bg' => 'indigo'],
                        'B+' => ['color' => '#818cf8', 'bg' => 'indigo'],
                        'B' => ['color' => '#a5b4fc', 'bg' => 'indigo'],
                        'C+' => ['color' => '#fbbf24', 'bg' => 'amber'],
                        'C' => ['color' => '#f59e0b', 'bg' => 'amber'],
                        'D' => ['color' => '#ea580c', 'bg' => 'orange'],
                        'F' => ['color' => '#ef4444', 'bg' => 'rose'],
                    ];
                    $totalGrades = array_sum($gradeDistribution);
                @endphp

                <div class="space-y-4">
                    @foreach($gradeDistribution as $grade => $count)
                    @php 
                        $percentage = $totalGrades > 0 ? round(($count / $totalGrades) * 100) : 0;
                        $config = $gradeConfig[$grade] ?? ['color' => '#cbd5e1', 'bg' => 'slate'];
                    @endphp
                    <div class="group">
                        <div class="flex justify-between items-center mb-1.5">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-[11px] font-bold text-white shadow-sm" 
                                      style="background: {{ $config['color'] }}">
                                    {{ $grade }}
                                </span>
                                <span class="text-sm font-semibold text-slate-700">{{ $count }}</span>
                                <span class="text-[10px] font-medium text-slate-400">{{ Str::plural('quiz', $count) }}</span>
                            </div>
                            <span class="text-xs font-bold text-slate-500">{{ $percentage }}%</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 group-hover:opacity-80" 
                                 style="width: {{ $percentage }}%; background: {{ $config['color'] }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Security Notice -->
                @if($totalViolations > 0)
                <div class="mt-6 p-3.5 rounded-xl bg-amber-50 border border-amber-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-amber-500 shadow-sm">
                        <i class="fas fa-shield-heart text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wide">Academic Integrity</p>
                        <p class="text-xs font-medium text-amber-700">{{ $totalViolations }} incident{{ $totalViolations !== 1 ? 's' : '' }} flagged</p>
                    </div>
                    <i class="fas fa-info-circle text-amber-400 text-xs cursor-help" title="Contact your instructor for details"></i>
                </div>
                @else
                <div class="mt-6 p-3.5 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-emerald-500 shadow-sm">
                        <i class="fas fa-shield-check text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide">Clean Record</p>
                        <p class="text-xs font-medium text-emerald-700">No integrity violations detected</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Subject Performance Section -->
        @if(count($subjectPerformance) > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-layer-group text-indigo-500 text-sm"></i>
                        Subject Performance
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Average scores by subject area</p>
                </div>
                <span class="text-[11px] font-medium text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full">
                    {{ count($subjectPerformance) }} subjects
                </span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($subjectPerformance as $sp)
                @php
                    // Determine grade and color
                    $avgScore = $sp['avg'];
                    if($avgScore >= 95) { $subGrade = 'A+'; $gradeColor = '#4f46e5'; $bgClass = 'indigo'; }
                    elseif($avgScore >= 90) { $subGrade = 'A'; $gradeColor = '#6366f1'; $bgClass = 'indigo'; }
                    elseif($avgScore >= 85) { $subGrade = 'B+'; $gradeColor = '#818cf8'; $bgClass = 'indigo'; }
                    elseif($avgScore >= 80) { $subGrade = 'B'; $gradeColor = '#a5b4fc'; $bgClass = 'indigo'; }
                    elseif($avgScore >= 75) { $subGrade = 'C+'; $gradeColor = '#fbbf24'; $bgClass = 'amber'; }
                    elseif($avgScore >= 70) { $subGrade = 'C'; $gradeColor = '#f59e0b'; $bgClass = 'amber'; }
                    elseif($avgScore >= 60) { $subGrade = 'D'; $gradeColor = '#ea580c'; $bgClass = 'orange'; }
                    else { $subGrade = 'F'; $gradeColor = '#ef4444'; $bgClass = 'rose'; }
                @endphp
                <div class="bg-white rounded-xl border border-slate-100 p-5 hover:shadow-md transition-all duration-200 group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-2 h-2 rounded-full" style="background: {{ $gradeColor }}"></div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $sp['count'] }} attempts</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 truncate">{{ $sp['name'] }}</h4>
                        </div>
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold text-white shadow-md" style="background: {{ $gradeColor }}">
                            {{ $subGrade }}
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-slate-50 rounded-lg p-2.5">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Average</p>
                            <p class="text-lg font-bold text-slate-800 tabular-nums">{{ round($avgScore) }}%</p>
                        </div>
                        <div class="bg-{{ $bgClass }}-50/40 rounded-lg p-2.5">
                            <p class="text-[9px] font-bold text-{{ $bgClass }}-400 uppercase tracking-wide">Highest</p>
                            <p class="text-lg font-bold text-{{ $bgClass }}-600 tabular-nums">{{ $sp['best'] }}%</p>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="pt-1">
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500" 
                                 style="width: {{ $avgScore }}%; background: {{ $gradeColor }}"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Assessment History Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-scroll text-indigo-500 text-sm"></i>
                        Assessment History
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Recent quiz attempts and results</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" placeholder="Search quizzes..." 
                               class="pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-300 focus:ring-1 focus:ring-indigo-200 transition-all w-40 sm:w-48">
                        <i class="fas fa-search absolute left-2.5 top-2 text-slate-400 text-[11px]"></i>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Quiz / Subject</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Grade</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Completed</th>
                            <th class="px-6 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($results as $result)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800 text-sm">{{ $result->quiz?->title ?? 'Untitled Assessment' }}</div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <i class="fas fa-book text-[8px] text-slate-400"></i>
                                    <span class="text-[10px] font-medium text-slate-400">{{ $result->quiz?->subject?->subject_name ?? 'General Subject' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($result->is_published === false)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-bold uppercase tracking-wide border border-amber-100">
                                        <i class="fas fa-hourglass-half text-[9px]"></i> Pending
                                    </span>
                                @elseif($result->passed)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold uppercase tracking-wide border border-emerald-100">
                                        <i class="fas fa-check-circle text-[9px]"></i> Passed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-bold uppercase tracking-wide border border-rose-100">
                                        <i class="fas fa-times-circle text-[9px]"></i> Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-lg font-bold tabular-nums {{ $result->passed ? 'text-emerald-600' : 'text-rose-500' }}">
                                    {{ round($result->score) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $score = $result->score;
                                    if($score >= 95) $letter = 'A+';
                                    elseif($score >= 90) $letter = 'A';
                                    elseif($score >= 85) $letter = 'B+';
                                    elseif($score >= 80) $letter = 'B';
                                    elseif($score >= 75) $letter = 'C+';
                                    elseif($score >= 70) $letter = 'C';
                                    elseif($score >= 60) $letter = 'D';
                                    else $letter = 'F';
                                    
                                    $gradeColors = ['A+' => '#4f46e5', 'A' => '#6366f1', 'B+' => '#818cf8', 'B' => '#a5b4fc', 
                                                    'C+' => '#fbbf24', 'C' => '#f59e0b', 'D' => '#ea580c', 'F' => '#ef4444'];
                                    $gradeBg = $gradeColors[$letter] ?? '#cbd5e1';
                                @endphp
                                <span class="inline-flex w-8 h-8 rounded-lg items-center justify-center text-[11px] font-bold text-white shadow-sm" 
                                      style="background: {{ $gradeBg }}">{{ $letter }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-medium text-slate-600">{{ $result->completed_at ? $result->completed_at->format('M d, Y') : '—' }}</div>
                                <div class="text-[9px] font-medium text-slate-400 mt-0.5">{{ $result->completed_at ? $result->completed_at->format('g:i A') : '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('students.quizzes.result', $result->attempt_id) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all"
                                   title="View Details">
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clipboard-list text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-500 mb-3">No quiz attempts yet</p>
                                <a href="{{ route('students.dashboard') }}" 
                                   class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all">
                                    <i class="fas fa-play-circle text-xs"></i>
                                    Start Your First Quiz
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($results->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex justify-between items-center">
                <div class="text-xs text-slate-500">
                    Showing {{ $results->firstItem() ?? 0 }} to {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} results
                </div>
                <div class="flex gap-1">
                    {{ $results->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Hidden Data for Chart -->
<div id="chart-data" 
     data-labels='@json(array_column($scoreTrend, "label"))' 
     data-scores='@json(array_column($scoreTrend, "score"))'>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartCanvas = document.getElementById('scoreTrendChart');
    if (!chartCanvas) return;
    
    const dataElement = document.getElementById('chart-data');
    let labels = [], scores = [];
    
    try {
        labels = JSON.parse(dataElement.dataset.labels || '[]');
        scores = JSON.parse(dataElement.dataset.scores || '[]');
    } catch(e) {
        console.warn('Failed to parse chart data:', e);
    }

    if (labels.length === 0 || scores.length === 0) {
        // Show empty state
        const container = chartCanvas.parentElement;
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full gap-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                    <i class="fas fa-chart-line text-slate-300 text-lg"></i>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-slate-500">No data available</p>
                    <p class="text-xs text-slate-400 mt-1">Complete quizzes to see your progress</p>
                </div>
            </div>
        `;
        return;
    }

    const ctx = chartCanvas.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.12)');
    gradient.addColorStop(0.5, 'rgba(79, 70, 229, 0.04)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Score',
                data: scores,
                borderColor: '#4f46e5',
                borderWidth: 2.5,
                backgroundColor: gradient,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#4f46e5',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2.5,
                spanGaps: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#f1f5f9',
                    bodyColor: '#cbd5e1',
                    bodyFont: { size: 11, weight: '500' },
                    titleFont: { size: 12, weight: 'bold' },
                    padding: { top: 8, left: 12, right: 12, bottom: 8 },
                    displayColors: false,
                    callbacks: {
                        label: (context) => `Score: ${context.raw}%`
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    grid: {
                        color: '#e2e8f0',
                        drawBorder: false,
                        lineWidth: 0.5
                    },
                    ticks: {
                        stepSize: 25,
                        color: '#94a3b8',
                        font: { size: 10, weight: '500' },
                        callback: (value) => `${value}%`
                    },
                    title: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10, weight: '500' },
                        maxRotation: 30,
                        autoSkip: true
                    }
                }
            },
            elements: {
                line: {
                    borderJoin: 'round',
                    borderCap: 'round'
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>

<style>
    /* Custom pagination styling */
    .pagination {
        display: flex;
        gap: 0.25rem;
    }
    .pagination .page-item .page-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 0.5rem;
        color: #475569;
        background: white;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .pagination .page-item.active .page-link {
        background: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }
    .pagination .page-item .page-link:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Hide number input spinners */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        opacity: 0;
    }
</style>
@endsection