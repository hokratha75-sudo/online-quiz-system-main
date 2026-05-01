@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter text-slate-900 bg-slate-50/30 min-h-screen">
    
    <!-- Hero Metrics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="card-standard p-6 group transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Students</span>
                <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-200 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="far fa-user-graduate text-xs"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 tabular-nums">{{ number_format($totalStudents) }}</h3>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded uppercase tracking-tighter">Verified</span>
            </div>
        </div>
        
        <div class="card-standard p-6 group transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Avg Score</span>
                <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-200 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="far fa-chart-line text-xs"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 tabular-nums">{{ round($avgScore, 1) }}%</h3>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">System Average</span>
            </div>
        </div>

        <div class="card-standard p-6 group transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Pass Rate</span>
                <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-200 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                    <i class="far fa-check-double text-xs"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 tabular-nums">{{ $passRate }}%</h3>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Success Ratio</span>
            </div>
        </div>

        <div class="card-standard p-6 bg-indigo-600 border-indigo-700 shadow-lg shadow-indigo-200 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-3 opacity-10">
                <i class="fas fa-crown text-5xl text-white rotate-12"></i>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="text-[11px] font-bold text-indigo-100 uppercase tracking-widest">Top Performer</span>
                <div class="w-9 h-9 rounded-xl bg-white/20 text-white flex items-center justify-center border border-white/20 backdrop-blur-md">
                    <i class="fas fa-crown text-xs"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-white truncate relative z-10">{{ $topPerformer->user?->username ?? 'N/A' }}</h3>
            <div class="mt-2 text-[11px] font-bold text-indigo-100 uppercase tracking-tighter relative z-10">High: {{ $topPerformer?->score ?? 0 }}%</div>
        </div>
    </div>

    <!-- Performance Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="card-standard p-6">
            <h6 class="text-[11px] font-bold text-slate-900 mb-6 flex items-center gap-2 uppercase tracking-widest">
                <i class="far fa-chart-bar text-indigo-500"></i> Grade Distribution
            </h6>
            <div class="relative h-[220px]">
                <canvas id="scoreChart"></canvas>
            </div>
        </div>
        
        <div class="card-standard p-6">
            <h6 class="text-[11px] font-bold text-slate-900 mb-6 flex items-center gap-2 uppercase tracking-widest">
                <i class="far fa-graduation-cap text-indigo-500"></i> Subject Analytics
            </h6>
            <div class="relative h-[220px]">
                <canvas id="subjectChart"></canvas>
            </div>
        </div>

        <div class="card-standard p-6 text-center">
            <h6 class="text-[11px] font-bold text-slate-900 mb-6 flex items-center gap-2 justify-center uppercase tracking-widest">
                <i class="far fa-chart-pie text-indigo-500"></i> Success Ratio
            </h6>
            <div class="relative h-[180px]">
                <canvas id="passFailChart"></canvas>
            </div>
            <div class="mt-6 flex justify-center gap-4 text-[10px] font-bold uppercase tracking-tighter">
                <div class="text-emerald-600">Passed ({{ $passRate }}%)</div>
                <div class="text-rose-600">Failed ({{ 100 - $passRate }}%)</div>
            </div>
        </div>
    </div>

    <!-- Student Reports -->
    <div class="card-standard mb-10">
        <div class="card-header-standard flex flex-col md:flex-row justify-between items-center gap-4">
            <h3>Student Assessment Logs</h3>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-2.5 rounded-2xl text-[11px] font-bold transition-all flex items-center gap-2 shadow-sm uppercase tracking-widest">
                    <i class="far fa-print text-slate-400"></i> Print
                </button>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-2xl text-[11px] font-bold transition-all flex items-center gap-2 shadow-xl shadow-indigo-600/20 active:scale-[0.98] uppercase tracking-widest">
                    <i class="far fa-file-csv"></i> Export
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table-standard">
                <thead>
                    <tr>
                        <th style="width: 80px;">#ID</th>
                        <th>Student Information</th>
                        <th>Quiz Module</th>
                        <th style="width: 180px;">Performance</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 150px;">Timestamp</th>
                        <th style="width: 80px; text-align: center;">View</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        <tr class="hover:bg-slate-50 transition-all">
                            <td>
                                <span class="font-bold text-slate-400">#{{ str_pad($result->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center text-[10px] font-black border border-slate-200">
                                        {{ strtoupper(substr($result->user?->username ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-[13px] font-bold text-slate-900">{{ $result->user?->username ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-900 leading-tight">{{ $result->quiz?->title ?? '--' }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $result->quiz?->subject?->subject_name ?? 'General' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="progress-clean flex-1">
                                        <div class="progress-bar-clean {{ $result->score >= ($result->quiz?->pass_percentage ?? 60) ? 'bg-indigo-500' : 'bg-rose-500' }}" style="width: {{ $result->score }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-bold {{ $result->score >= ($result->quiz?->pass_percentage ?? 60) ? 'text-indigo-600' : 'text-rose-600' }} w-8 text-right">{{ round($result->score) }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($result->score >= ($result->quiz?->pass_percentage ?? 60))
                                    <span class="label-standard label-green">PASSED</span>
                                @else
                                    <span class="label-standard label-red">FAILED</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-[12px] font-bold text-slate-600">{{ $result->completed_at ? $result->completed_at->format('M d, Y') : 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ $result->completed_at ? $result->completed_at->format('h:i A') : '' }}</div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('quizzes.result', $result->attempt_id) }}" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm">
                                    <i class="far fa-chevron-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-16 text-center text-slate-400 font-medium uppercase tracking-widest text-xs">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($results->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 flex justify-end">
            <div class="pagination-clean">
                {{ $results->links() }}
            </div>
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
                        labels: ['Passed', 'Failed'],
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
