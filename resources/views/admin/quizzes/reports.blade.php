@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-4 md:p-6 lg:p-10 font-inter text-slate-900">
    
    <!-- Hero Metrics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white rounded-[24px] border border-slate-50 p-8 shadow-sm group hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-6">
                <span class="text-sm font-semibold text-slate-600">Total Students</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fas fa-user-graduate text-sm"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 tabular-nums tracking-tight">{{ number_format($totalStudents) }}</h3>
            <div class="mt-2 text-xs font-medium text-slate-400">Total Registered Nodes</div>
        </div>
        
        <div class="bg-white rounded-[24px] border border-slate-50 p-8 shadow-sm group hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-6">
                <span class="text-sm font-semibold text-slate-600">Avg Score</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="fas fa-chart-line text-sm"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 tabular-nums tracking-tight">{{ round($avgScore, 1) }}%</h3>
            <div class="mt-2 text-xs font-medium text-slate-400">Overall Performance</div>
        </div>

        <div class="bg-white rounded-[24px] border border-slate-50 p-8 shadow-sm group hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-6">
                <span class="text-sm font-semibold text-slate-600">Pass Rate</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shadow-sm group-hover:bg-rose-600 group-hover:text-white transition-colors">
                    <i class="fas fa-check-double text-sm"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 tabular-nums tracking-tight">{{ $passRate }}%</h3>
            <div class="mt-2 text-xs font-medium text-slate-400">Institutional Success</div>
        </div>

        <div class="bg-indigo-600 rounded-[24px] p-8 shadow-xl shadow-indigo-600/20 group relative overflow-hidden transition-all hover:-translate-y-1">
            <div class="absolute top-0 right-0 p-3 opacity-10">
                <i class="fas fa-crown text-6xl text-white rotate-12"></i>
            </div>
            <div class="flex justify-between items-start mb-6 relative z-10">
                <span class="text-sm font-semibold text-indigo-100">Top Performer</span>
                <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center border border-white/20 backdrop-blur-md">
                    <i class="fas fa-crown text-sm"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-white truncate relative z-10 tracking-tight leading-none">{{ $topPerformer->user?->username ?? 'N/A' }}</h3>
            <div class="mt-2 text-xs font-medium text-indigo-100/80 relative z-10 leading-none">Highest Score: {{ $topPerformer?->score ?? 0 }}%</div>
        </div>
    </div>

    <!-- Analytical Framework -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Performance Distribution -->
        <div class="bg-white rounded-[32px] border border-slate-50 p-8 shadow-sm">
            <h6 class="text-sm font-bold text-slate-900 mb-8 flex items-center gap-2">
                <i class="fas fa-chart-bar text-indigo-500"></i> Grade Distribution
            </h6>
            <div class="relative h-[250px]">
                <canvas id="scoreChart"></canvas>
            </div>
        </div>
        
        <!-- Subject Vectors -->
        <div class="bg-white rounded-[32px] border border-slate-50 p-8 shadow-sm">
            <h6 class="text-sm font-bold text-slate-900 mb-8 flex items-center gap-2">
                <i class="fas fa-graduation-cap text-indigo-500"></i> Subject Performance
            </h6>
            <div class="relative h-[250px]">
                <canvas id="subjectChart"></canvas>
            </div>
        </div>

        <!-- Logic Ratio -->
        <div class="bg-white rounded-[32px] border border-slate-50 p-8 shadow-sm text-center">
            <h6 class="text-sm font-bold text-slate-900 mb-8 flex items-center gap-2 justify-center">
                <i class="fas fa-chart-pie text-indigo-500"></i> Pass/Fail Ratio
            </h6>
            <div class="relative h-[200px]">
                <canvas id="passFailChart"></canvas>
            </div>
            <div class="mt-10 pt-6 border-t border-slate-50">
                <div class="flex justify-around text-xs font-semibold text-slate-600">
                    <div class="text-emerald-600"><i class="fas fa-circle me-1.5 text-[8px]"></i> Passed ({{ $passRate }}%)</div>
                    <div class="text-rose-600"><i class="fas fa-circle me-1.5 text-[8px]"></i> Failed ({{ 100 - $passRate }}%)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Matrix -->
    <div class="bg-white rounded-[24px] border border-slate-50 p-6 mb-10 shadow-sm">
        <form action="{{ route('quizzes.reports') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 w-full items-center">
            <div class="md:col-span-3 relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-xs"></i>
                <input type="text" name="search" class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-10 pr-4 py-3 text-sm font-medium text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-400" placeholder="Search records..." value="{{ request('search') }}">
            </div>
            @if($userRole === 'admin')
            <div class="md:col-span-2">
                <select name="department_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all outline-none">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="teacher_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all outline-none">
                    <option value="">All Teachers</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->username }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="md:col-span-3">
                <select name="subject_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all outline-none">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="flex-grow h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95 flex items-center justify-center">
                    <i class="fas fa-sync-alt me-2"></i> Filter
                </button>
                <a href="{{ route('quizzes.reports') }}" class="w-12 h-12 bg-white border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-200 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-95">
                    <i class="fas fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Archival Table Node -->
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden mb-10">
        <div class="px-10 py-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6 bg-slate-50/10">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Student Assessment Logs</h3>
                <p class="text-xs font-medium text-slate-500 mt-1">Complete history of all completed quizzes</p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="window.print()" class="h-10 px-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold flex items-center transition-all shadow-sm active:scale-95">
                    <i class="fas fa-print me-2 text-indigo-200"></i> Print
                </button>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="h-10 px-5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 rounded-xl text-xs font-semibold flex items-center transition-all shadow-sm active:scale-95">
                    <i class="fas fa-file-excel me-2 text-emerald-500"></i> Export CSV
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="ps-8 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Attempt ID</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Quiz Title</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Completed At</th>
                        <th class="pe-8 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Review</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($results as $result)
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="ps-8 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 tracking-wide">#{{ str_pad($result->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold uppercase border border-indigo-100 shrink-0">
                                        {{ strtoupper(substr($result->user?->username ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="text-sm font-semibold text-slate-900 tracking-tight">{{ $result->user?->username ?? 'Unknown' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900 leading-tight truncate max-w-[200px]">{{ $result->quiz?->title ?? '--' }}</div>
                                <div class="text-xs font-medium text-slate-500 mt-0.5">{{ $result->quiz?->subject?->subject_name ?? 'General Quiz' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-1.5 rounded-full bg-slate-100 overflow-hidden shrink-0">
                                        <div class="h-full {{ $result->score >= ($result->quiz?->pass_percentage ?? 60) ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ $result->score }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold tabular-nums {{ $result->score >= ($result->quiz?->pass_percentage ?? 60) ? 'text-emerald-600' : 'text-rose-600' }}">{{ round($result->score) }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($result->score >= ($result->quiz?->pass_percentage ?? 60))
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100"><i class="fas fa-check-circle me-1.5 text-emerald-500"></i> Passed</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100"><i class="fas fa-times-circle me-1.5 text-rose-500"></i> Failed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-medium text-slate-900 whitespace-nowrap">{{ $result->completed_at ? $result->completed_at->format('M d, Y') : 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5 whitespace-nowrap">{{ $result->completed_at ? $result->completed_at->format('h:i A') : '' }}</div>
                            </td>
                            <td class="pe-8 py-4 text-right">
                                <a href="{{ route('quizzes.result', $result->attempt_id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all shadow-sm active:scale-95" title="Review Attempt">
                                    <i class="fas fa-arrow-right text-[13px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-16 text-center text-sm font-medium text-slate-500">No diagnostic logs found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($results->hasPages())
        <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/20">
            {{ $results->links() }}
        </div>
        @endif
    </div>

</div>

<div id="chart-data" 
    data-scores='@json(array_values($scoreDistribution))'
    data-passrate='@json($passRate)'
    data-subjects='@json($subjectPerformance)'>
</div>

<style>
    @media print {
        .sidebar, .topbar, form, button, .pagination, .no-print { display: none !important; }
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

    // Modern Chart Defaults
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.size = 10;
    Chart.defaults.font.weight = 'bold';

    const scalesConfig = {
        y: { 
            beginAtZero: true, 
            grid: { borderDash: [5, 5], color: '#f1f5f9', drawBorder: false }, 
            ticks: { padding: 10 } 
        },
        x: { 
            grid: { display: false }, 
            ticks: { padding: 10 } 
        }
    };

    // 1. Grade Distribution Chart
    const ctxScore = document.getElementById('scoreChart').getContext('2d');
    new Chart(ctxScore, {
        type: 'bar',
        data: {
            labels: ['A (90+)', 'B (80+)', 'C (70+)', 'D (60+)', 'E (50+)', 'F (<50)'],
            datasets: [{
                data: scoreData,
                backgroundColor: ['#10b981', '#4f46e5', '#8b5cf6', '#f59e0b', '#fb7185', '#94a3b8'],
                borderRadius: 8,
                barThickness: 20
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
            labels: subjectData.map(s => s.subject_name.length > 20 ? s.subject_name.substr(0, 17) + '...' : s.subject_name),
            datasets: [{
                label: 'Performance %',
                data: subjectData.map(s => Math.round(s.average_score)),
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.05)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2
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
            labels: ['Authorized', 'Rejected'],
            datasets: [{
                data: [passRateValue, 100 - passRateValue],
                backgroundColor: ['#10b981', '#fb7185'],
                borderWidth: 0,
                hoverOffset: 12
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            cutout: '75%'
        }
    });
});
</script>
@endsection
