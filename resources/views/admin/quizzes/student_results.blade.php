@extends('layouts.admin')

@section('styles')
<style>
    .perf-card {
        background: #fff;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        padding: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .perf-card:hover { transform: translateY(-4px); box-shadow: 0 15px 35px rgba(0,0,0,0.06); }
    .perf-card .icon-wrap {
        width: 52px; height: 52px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }
    .perf-card .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; }
    .perf-card .stat-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; }

    .streak-badge {
        background: linear-gradient(135deg, #f59e0b, #ef4444);
        color: #fff; font-weight: 800; border-radius: 50rem;
        padding: 0.4rem 1rem; font-size: 0.8rem;
        display: inline-flex; align-items: center; gap: 6px;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .grade-bar {
        height: 8px; border-radius: 4px; background: #f1f5f9;
        overflow: hidden; position: relative;
    }
    .grade-bar-fill { height: 100%; border-radius: 4px; transition: width 0.6s ease; }

    .subject-row { padding: 1rem 0; border-bottom: 1px solid #f8fafc; }
    .subject-row:last-child { border-bottom: none; }

    .result-table thead th {
        font-weight: 700; text-transform: uppercase; font-size: 0.65rem;
        letter-spacing: 0.06em; color: #94a3b8; padding: 1rem 0.75rem; border: none;
    }
    .result-table tbody td {
        padding: 1.1rem 0.75rem; border-color: #f8fafc; vertical-align: middle;
    }
    .result-table tbody tr { transition: background 0.2s; }
    .result-table tbody tr:hover { background: #fafbfd; }

    .animate-in { animation: fadeSlideUp 0.5s ease-out both; }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .chart-card {
        background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        padding: 1.5rem; border: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">

    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark">My Performance Hub</h3>
            <p class="text-muted small mb-0">Track your academic progress, scores, and growth across all quizzes.</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            @if($streak > 0)
                <span class="streak-badge"><i class="fas fa-fire"></i> {{ $streak }} Quiz Win Streak!</span>
            @endif
            <a href="{{ route('students.dashboard') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                <i class="fas fa-play me-1"></i> Take a Quiz
            </a>
        </div>
    </div>

    <!-- KPI Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-lg-2 col-md-4 col-6 animate-in" style="animation-delay: 0.05s">
            <div class="perf-card">
                <div class="icon-wrap bg-primary bg-opacity-10 text-primary mb-3"><i class="fas fa-clipboard-check"></i></div>
                <div class="stat-value text-dark">{{ $totalQuizzesTaken }}</div>
                <div class="stat-label mt-1">Total Taken</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 animate-in" style="animation-delay: 0.1s">
            <div class="perf-card">
                <div class="icon-wrap bg-success bg-opacity-10 text-success mb-3"><i class="fas fa-check-double"></i></div>
                <div class="stat-value text-success">{{ $totalPassed }}</div>
                <div class="stat-label mt-1">Passed</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 animate-in" style="animation-delay: 0.15s">
            <div class="perf-card">
                <div class="icon-wrap bg-danger bg-opacity-10 text-danger mb-3"><i class="fas fa-times-circle"></i></div>
                <div class="stat-value text-danger">{{ $totalFailed }}</div>
                <div class="stat-label mt-1">Failed</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 animate-in" style="animation-delay: 0.2s">
            <div class="perf-card">
                <div class="icon-wrap bg-warning bg-opacity-10 text-warning mb-3"><i class="fas fa-star"></i></div>
                <div class="stat-value text-dark">{{ $avgScore }}%</div>
                <div class="stat-label mt-1">Avg Score</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 animate-in" style="animation-delay: 0.25s">
            <div class="perf-card">
                <div class="icon-wrap bg-info bg-opacity-10 text-info mb-3"><i class="fas fa-trophy"></i></div>
                <div class="stat-value text-dark">{{ $highestScore }}%</div>
                <div class="stat-label mt-1">Best Score</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 animate-in" style="animation-delay: 0.3s">
            <div class="perf-card">
                <div class="icon-wrap mb-3" style="background: #fef3c7; color: #d97706;"><i class="fas fa-percentage"></i></div>
                <div class="stat-value text-dark">{{ $passRate }}%</div>
                <div class="stat-label mt-1">Pass Rate</div>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Row -->
    <div class="row g-4 mb-4">
        <!-- Score Trend Chart -->
        <div class="col-lg-8 animate-in" style="animation-delay: 0.35s">
            <div class="chart-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-chart-line text-primary me-2"></i>Score Progression</h6>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-1 small">Last {{ count($scoreTrend) }} quizzes</span>
                </div>
                <div style="height: 260px;">
                    <canvas id="scoreTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Grade Distribution -->
        <div class="col-lg-4 animate-in" style="animation-delay: 0.4s">
            <div class="chart-card h-100">
                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-chart-pie text-primary me-2"></i>Grade Distribution</h6>
                @php
                    $gradeColors = [
                        'A+' => '#10b981', 'A' => '#34d399', 'B+' => '#60a5fa', 'B' => '#93c5fd',
                        'C+' => '#fbbf24', 'C' => '#fcd34d', 'D' => '#f97316', 'F' => '#ef4444',
                    ];
                    $maxGrade = max(1, max($gradeDistribution));
                @endphp
                @foreach($gradeDistribution as $grade => $count)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="fw-bold text-dark" style="width: 28px; font-size: 0.8rem;">{{ $grade }}</span>
                        <div class="grade-bar flex-grow-1">
                            <div class="grade-bar-fill" style="width: {{ ($count / $maxGrade) * 100 }}%; background: {{ $gradeColors[$grade] }};"></div>
                        </div>
                        <span class="text-muted small fw-bold" style="width: 22px; text-align: right;">{{ $count }}</span>
                    </div>
                @endforeach

                @if($totalViolations > 0)
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                            <i class="fas fa-shield-alt small"></i>
                        </div>
                        <div>
                            <div class="small fw-bold text-dark">Security Violations</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $totalViolations }} total across all exams</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Per-Subject Performance -->
    @if(count($subjectPerformance) > 0)
    <div class="row g-4 mb-4">
        <div class="col-12 animate-in" style="animation-delay: 0.45s">
            <div class="chart-card">
                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-book-open text-primary me-2"></i>Performance by Subject</h6>
                <div class="row g-3">
                    @foreach($subjectPerformance as $idx => $subj)
                    @php
                        $cardColors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'];
                        $color = $cardColors[$idx % count($cardColors)];
                    @endphp
                    <div class="col-md-4 col-sm-6">
                        <div class="p-3 rounded-4 border bg-white shadow-sm">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width:42px;height:42px;background:{{ $color }};">
                                    <i class="fas fa-book small"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate small">{{ $subj['name'] }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $subj['count'] }} quiz{{ $subj['count'] > 1 ? 'zes' : '' }}</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-end mt-2 pt-2 border-top">
                                <div>
                                    <div class="text-muted" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">Avg Score</div>
                                    <div class="fw-bold text-dark">{{ $subj['avg'] }}%</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">Best</div>
                                    <div class="fw-bold text-success">{{ $subj['best'] }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Results Table -->
    <div class="animate-in" style="animation-delay: 0.5s">
        <div class="chart-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-list-ul text-primary me-2"></i>All Quiz Results</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-bold">{{ $totalQuizzesTaken }} Total</span>
            </div>
            <div class="table-responsive">
                <table class="table result-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Quiz</th>
                            <th>Subject</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Violations</th>
                            <th>Date</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $result)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark">{{ $result->quiz?->title ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                        {{ $result->quiz?->subject?->subject_name ?? 'General' }}
                                    </span>
                                </td>
                                <td>
                                    @if($result->is_published === false)
                                        <span class="text-warning fw-bold"><i class="fas fa-hourglass-half me-1"></i>Pending</span>
                                    @else
                                        <span class="fw-bold fs-5 {{ $result->passed ? 'text-success' : 'text-danger' }}">
                                            {{ round($result->score) }}%
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($result->is_published === false)
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3">Review</span>
                                    @elseif($result->passed)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Passed</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">Failed</span>
                                    @endif
                                </td>
                                <td>
                                    @php $v = $result->attempt?->violations ?? 0; @endphp
                                    @if($v > 0)
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2" style="font-size: 0.7rem;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $v }}
                                        </span>
                                    @else
                                        <span class="text-success small"><i class="fas fa-check-circle"></i></span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $result->completed_at ? $result->completed_at->format('M d, Y') : 'N/A' }}
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $result->completed_at ? $result->completed_at->format('h:i A') : '' }}</div>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('students.quizzes.result', $result->attempt_id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size: 0.75rem;">
                                        <i class="fas fa-eye me-1"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-3"><i class="fas fa-clipboard-list fa-3x text-muted opacity-25"></i></div>
                                    <h6 class="text-muted fw-bold">No quizzes completed yet</h6>
                                    <p class="text-muted small mb-3">Start taking quizzes to see your performance data here.</p>
                                    <a href="{{ route('students.dashboard') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">Browse Quizzes</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($results->hasPages())
                <div class="pt-3 border-top mt-3 d-flex justify-content-center">
                    {{ $results->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Chart Data -->
<div id="score-trend-data"
     data-labels='@json(array_column($scoreTrend, "label"))'
     data-scores='@json(array_column($scoreTrend, "score"))'
     data-dates='@json(array_column($scoreTrend, "date"))'>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('scoreTrendChart');
    if (!ctx) return;

    const dataEl = document.getElementById('score-trend-data');
    const labels = JSON.parse(dataEl.dataset.labels || '[]');
    const scores = JSON.parse(dataEl.dataset.scores || '[]');

    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Score %',
                data: scores,
                borderColor: '#4f46e5',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff', titleColor: '#111', bodyColor: '#666',
                    borderColor: '#e5e7eb', borderWidth: 1, padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) { return 'Score: ' + context.raw + '%'; }
                    }
                }
            },
            scales: {
                y: {
                    min: 0, max: 100,
                    grid: { color: '#f3f4f6', drawBorder: false },
                    ticks: { color: '#9ca3af', font: { size: 11, weight: '600' }, stepSize: 20 }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#9ca3af', font: { size: 10, weight: '600' }, maxRotation: 30 }
                }
            }
        }
    });
});
</script>
@endsection
