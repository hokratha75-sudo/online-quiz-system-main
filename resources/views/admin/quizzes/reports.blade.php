@extends('layouts.admin')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white p-3 rounded-4 h-100">
            <small class="opacity-75 text-uppercase fw-bold">Total Students</small>
            <h3 class="fw-bold mb-1">{{ number_format($totalStudents) }}</h3>
            <div class="small opacity-50"><i class="fas fa-users me-1"></i> Active Enrolled</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white p-3 rounded-4 h-100">
            <small class="opacity-75 text-uppercase fw-bold">Avg. Score</small>
            <h3 class="fw-bold mb-1">{{ round($avgScore, 1) }}%</h3>
            <div class="small opacity-50"><i class="fas fa-chart-line me-1"></i> Overall Perf.</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white p-3 rounded-4 h-100">
            <small class="opacity-75 text-uppercase fw-bold">Pass Rate</small>
            <h3 class="fw-bold mb-1">{{ $passRate }}%</h3>
            <div class="small opacity-50"><i class="fas fa-check-double me-1"></i> Success Ratio</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-white p-3 rounded-4 h-100">
            <small class="opacity-75 text-uppercase fw-bold">Top Performer</small>
            <h3 class="fw-bold mb-1 text-truncate" style="max-width: 100%;">{{ $topPerformer->user?->username ?? 'N/A' }}</h3>
            <div class="small opacity-50"><i class="fas fa-crown me-1"></i> Highest Score: {{ $topPerformer?->score ?? 0 }}%</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Score Distribution -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold mb-4 text-secondary small text-uppercase ls-1"><i class="fas fa-chart-bar me-2 text-primary"></i> Grade Distribution</h6>
            <div style="height: 300px;">
                <canvas id="scoreChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Subject Performance -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold mb-4 text-secondary small text-uppercase ls-1"><i class="fas fa-graduation-cap me-2 text-warning"></i> Subject Performance</h6>
            <div style="height: 300px;">
                <canvas id="subjectChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Pass/Fail Ratio -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-center">
            <h6 class="fw-bold mb-4 text-secondary small text-uppercase ls-1"><i class="fas fa-chart-pie me-2 text-success"></i> Pass vs Fail Ratio</h6>
            <div style="height: 250px;">
                <canvas id="passFailChart"></canvas>
            </div>
            <div class="mt-4 pt-2 border-top">
                <div class="d-flex justify-content-around small fw-bold">
                    <div class="text-success"><i class="fas fa-circle me-1"></i> Passed ({{ $passRate }}%)</div>
                    <div class="text-danger"><i class="fas fa-circle me-1"></i> Failed ({{ 100 - $passRate }}%)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filters -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-3">
    <form action="{{ route('quizzes.reports') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control rounded-3" placeholder="Search student or quiz..." value="{{ request('search') }}">
        </div>
        @if($userRole === 'admin')
        <div class="col-md-2">
            <select name="department_id" class="form-select rounded-3">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="teacher_id" class="form-select rounded-3">
                <option value="">All Teachers</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->username }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-md-3">
            <select name="subject_id" class="form-select rounded-3">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-3 flex-grow-1 fw-bold"><i class="fas fa-filter me-1"></i> Filter</button>
            <a href="{{ route('quizzes.reports') }}" class="btn btn-light rounded-3 text-secondary border shadow-sm"><i class="fas fa-undo"></i></a>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-0 py-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h5 class="fw-bold text-dark mb-1">Assessment Records</h5>
            <p class="text-muted small mb-0">Detailed list of student quiz attempts and results</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm"><i class="fas fa-print me-2"></i> Print Report</button>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold shadow-sm"><i class="fas fa-file-excel me-2"></i> Download CSV</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="reportsTable">
                <thead class="bg-light bg-opacity-50 text-muted small text-uppercase ls-1">
                    <tr>
                        <th class="ps-4 py-3"># Attempt</th>
                        <th>Student Details</th>
                        <th>Quiz Reference</th>
                        <th>Performance</th>
                        <th>Assessment</th>
                        <th>Timestamp</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        <tr class="transition-all hover-bg-light">
                            <td class="ps-4">
                                <span class="fw-bold text-muted">A-{{ str_pad($result->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold" style="width:32px; height:32px; font-size: 12px;">
                                        {{ strtoupper(substr($result->user?->username ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="fw-bold text-dark">{{ $result->user?->username ?? 'Unknown' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold small text-dark">{{ $result->quiz?->title ?? 'N/A' }}</div>
                                <div class="extra-small text-muted">{{ $result->quiz?->subject?->subject_name ?? 'General' }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1 progress" style="height: 6px; width: 60px;">
                                        <div class="progress-bar {{ $result->score >= ($result->quiz?->pass_percentage ?? 60) ? 'bg-success' : 'bg-danger' }}" style="width: {{ $result->score }}%"></div>
                                    </div>
                                    <span class="fw-bold {{ $result->score >= ($result->quiz?->pass_percentage ?? 60) ? 'text-success' : 'text-danger' }}">{{ round($result->score) }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($result->score >= ($result->quiz?->pass_percentage ?? 60))
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 border border-success border-opacity-25 shadow-none"><i class="fas fa-check-circle me-1"></i> Passed</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 border border-danger border-opacity-25 shadow-none"><i class="fas fa-times-circle me-1"></i> Failed</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                <div>{{ $result->completed_at ? $result->completed_at->format('d M, Y') : 'N/A' }}</div>
                                <div class="extra-small opacity-75">{{ $result->completed_at ? $result->completed_at->format('H:i A') : '' }}</div>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('quizzes.result', $result->attempt_id) }}" class="btn btn-icon btn-light rounded-circle shadow-sm">
                                    <i class="fas fa-arrow-right text-primary"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mb-5">
    {{ $results->links() }}
</div>

<div id="chart-data" 
    data-scores='@json(array_values($scoreDistribution))'
    data-passrate='@json($passRate)'
    data-subjects='@json($subjectPerformance)'>
</div>

<style>
    .extra-small { font-size: 0.7rem; }
    .ls-1 { letter-spacing: 0.5px; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
    @media print {
        .sidebar, .topbar, form, button, .pagination { display: none !important; }
        .page-content { margin: 0 !important; padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #eee !important; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataContainer = document.getElementById('chart-data');
    const scoreData = JSON.parse(dataContainer.dataset.scores);
    const passRateValue = JSON.parse(dataContainer.dataset.passrate);
    const subjectData = JSON.parse(dataContainer.dataset.subjects);

    // Common Scales Config
    const scalesConfig = {
        y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f3f5' }, ticks: { font: { size: 10 } } },
        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
    };

    // 1. Grade Distribution Chart
    const ctxScore = document.getElementById('scoreChart').getContext('2d');
    new Chart(ctxScore, {
        type: 'bar',
        data: {
            labels: ['A (90+)', 'B (80+)', 'C (70+)', 'D (60+)', 'E (50+)', 'F (<50)'],
            datasets: [{
                data: scoreData,
                backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#6b7280'],
                borderRadius: 10,
                barThickness: 25
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: scalesConfig
        }
    });

    // 2. Subject Performance Chart
    const ctxSubject = document.getElementById('subjectChart').getContext('2d');
    new Chart(ctxSubject, {
        type: 'line',
        data: {
            labels: subjectData.map(s => s.subject_name.length > 15 ? s.subject_name.substr(0, 12) + '...' : s.subject_name),
            datasets: [{
                label: 'Avg Score %',
                data: subjectData.map(s => Math.round(s.average_score)),
                borderColor: '#5b6cf9',
                backgroundColor: 'rgba(91, 108, 249, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: '#fff',
                pointBorderWidth: 3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: scalesConfig
        }
    });

    // 3. Pass vs Fail Doughnut
    const ctxPassFail = document.getElementById('passFailChart').getContext('2d');
    new Chart(ctxPassFail, {
        type: 'doughnut',
        data: {
            labels: ['Passed', 'Failed'],
            datasets: [{
                data: [passRateValue, 100 - passRateValue],
                backgroundColor: ['#10b981', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            cutout: '80%'
        }
    });

});
</script>
@endsection
