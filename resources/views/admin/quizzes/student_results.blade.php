@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-8 lg:p-10 font-inter">

    <!-- Dashboard Header & KPI Row -->
    <div class="flex flex-col lg:flex-row gap-6 mb-10">
        <!-- Profile Node -->
        <div class="lg:w-1/3">
            <div class="bg-indigo-600 rounded-[32px] p-8 text-white shadow-xl shadow-indigo-600/20 relative overflow-hidden h-full group">
                <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-1000"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        @if($streak > 0)
                            <div class="px-3 py-1 bg-white/20 border border-white/20 rounded-full text-[10px] font-bold uppercase tracking-widest flex items-center gap-1.5">
                                <i class="fas fa-fire text-amber-400"></i> {{ $streak }} DAY STREAK
                            </div>
                        @endif
                        <div class="px-3 py-1 bg-emerald-400/20 border border-emerald-400/20 rounded-full text-xs font-bold tracking-wide flex items-center gap-1.5 text-emerald-100">
                            <i class="fas fa-shield-check"></i> {{ round($passRate) }}% Pass Rate
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight">Hi, {{ explode(' ', Auth::user()->name ?: Auth::user()->username)[0] }}!</h2>
                    <p class="mt-2 text-indigo-100/80 text-sm font-medium leading-relaxed">
                        You have successfully completed <span class="text-white font-bold">{{ $totalQuizzesTaken }} quizzes</span> this semester.
                    </p>
                </div>
            </div>
        </div>

        <!-- KPI Framework -->
        <div class="lg:w-2/3 grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $kpis = [
                    ['label' => 'Quizzes Taken', 'value' => $totalQuizzesTaken, 'icon' => 'fas fa-layer-group', 'color' => 'indigo'],
                    ['label' => 'Quizzes Passed', 'value' => $totalPassed, 'icon' => 'fas fa-check-double', 'color' => 'emerald'],
                    ['label' => 'Average Score', 'value' => round($avgScore).'%', 'icon' => 'fas fa-chart-line', 'color' => 'amber'],
                    ['label' => 'Highest Score', 'value' => $highestScore.'%', 'icon' => 'fas fa-crown', 'color' => 'violet'],
                ];
            @endphp
            @foreach($kpis as $kpi)
            <div class="bg-white rounded-[24px] border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all group flex flex-col justify-between">
                <div class="w-10 h-10 rounded-xl bg-{{ $kpi['color'] }}-50 text-{{ $kpi['color'] }}-600 flex items-center justify-center border border-{{ $kpi['color'] }}-100 group-hover:bg-{{ $kpi['color'] }}-600 group-hover:text-white transition-colors">
                    <i class="{{ $kpi['icon'] }} text-sm"></i>
                </div>
                <div class="mt-6">
                    <div class="text-3xl font-bold text-slate-900 leading-none tabular-nums">{{ $kpi['value'] }}</div>
                    <div class="text-xs font-semibold text-slate-500 mt-2">{{ $kpi['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Analytics Visualizer -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[32px] border border-slate-100 p-8 shadow-sm h-full">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Performance Analytics</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Score distribution over time</p>
                    </div>
                </div>
                <div class="h-[280px]">
                    <canvas id="scoreTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Logic Distribution -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[32px] border border-slate-100 p-8 shadow-sm h-full">
                <h3 class="text-base font-bold text-slate-900 mb-8">Grade Distribution</h3>
                
                <div class="space-y-5">
                    @php
                        $gradeColors = [
                            'A+' => '#4f46e5', 'A' => '#6366f1', 'B+' => '#818cf8', 'B' => '#a5b4fc',
                            'C+' => '#fbbf24', 'C' => '#f59e0b', 'D' => '#ea580c', 'F' => '#ef4444',
                        ];
                    @endphp
                    @foreach($gradeDistribution as $grade => $count)
                        <div class="group">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[10px] font-bold text-white shadow-sm" style="background: {{ $gradeColors[$grade] ?? '#cbd5e1' }}">
                                        {{ $grade }}
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700">{{ $count }} Quiz{{ $count != 1 ? 'zes' : '' }}</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 tabular-nums uppercase">{{ round(($count / max(1, $totalQuizzesTaken)) * 100) }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100/50">
                                <div class="h-full rounded-full group-hover:opacity-80 transition-opacity" style="width: {{ ($count / max(1, array_sum($gradeDistribution))) * 100 }}%; background: {{ $gradeColors[$grade] ?? '#cbd5e1' }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($totalViolations > 0)
                <div class="mt-8 p-5 rounded-2xl bg-rose-50 border border-rose-100 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-rose-500 shadow-sm border border-rose-100">
                        <i class="fas fa-exclamation-triangle text-sm"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-rose-600 uppercase tracking-widest">Security Disruptions</div>
                        <div class="text-xs font-bold text-rose-800 uppercase tabular-nums mt-1">{{ $totalViolations }} INCIDENTS LOGGED</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Per-Subject Analytics Grid -->
    @if(count($subjectPerformance) > 0)
    <div class="mb-10">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-6 px-2 flex items-center gap-3">
            <i class="fas fa-layer-group text-indigo-500"></i> Subject Performance Matrix
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($subjectPerformance as $sp)
            @php
                $subGrade = 'F';
                if($sp['avg'] >= 95) $subGrade = 'A+';
                elseif($sp['avg'] >= 90) $subGrade = 'A';
                elseif($sp['avg'] >= 85) $subGrade = 'B+';
                elseif($sp['avg'] >= 80) $subGrade = 'B';
                elseif($sp['avg'] >= 75) $subGrade = 'C+';
                elseif($sp['avg'] >= 70) $subGrade = 'C';
                elseif($sp['avg'] >= 60) $subGrade = 'D';

                $subColor = $gradeColors[$subGrade] ?? '#ef4444';
            @endphp
            <div class="bg-white rounded-[24px] border border-slate-100 p-6 flex flex-col justify-between shadow-sm hover:shadow-md hover:border-indigo-100 transition-all group overflow-hidden relative">
                <!-- BG Glow -->
                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full opacity-10 group-hover:opacity-20 transition-opacity blur-2xl" style="background: {{ $subColor }}"></div>
                
                <div class="relative z-10 flex justify-between items-start mb-6">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $sp['count'] }} DEPLOYMENTS</div>
                        <h4 class="text-sm font-bold text-slate-900 uppercase tracking-tight truncate max-w-[180px]">{{ $sp['name'] }}</h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-bold text-white shadow-sm" style="background: {{ $subColor }}">
                        {{ $subGrade }}
                    </div>
                </div>

                <div class="relative z-10 grid grid-cols-2 gap-4">
                    <div class="bg-slate-50/50 rounded-xl p-3 border border-slate-100/50">
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Yield Avg</div>
                        <div class="text-base font-bold text-slate-800 tabular-nums leading-none">{{ $sp['avg'] }}%</div>
                    </div>
                    <div class="bg-indigo-50/30 rounded-xl p-3 border border-indigo-50">
                        <div class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest mb-1">Peak Result</div>
                        <div class="text-base font-bold text-indigo-700 tabular-nums leading-none">{{ $sp['best'] }}%</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- History Core -->
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Deployment Logs</h3>
                <p class="text-[10px] font-bold text-indigo-600 mt-1 uppercase tracking-tight">Archive of verified assessment vectors</p>
            </div>
            <a href="{{ route('students.dashboard') }}" class="h-11 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
                <i class="fas fa-plus text-[8px]"></i> New Sync Session
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="ps-8 py-3 text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Protocol / Subject</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Yield Index</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Grade</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Timestamp</th>
                        <th class="pe-8 py-3 text-[10px] font-bold text-indigo-600 uppercase tracking-widest text-right">Audit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($results as $result)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="ps-8 py-3">
                            <div class="text-sm font-bold text-slate-900 uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{{ $result->quiz?->title ?? 'Untitled Sync' }}</div>
                            <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest flex items-center gap-1.5">
                                <i class="fas fa-tag text-[8px]"></i> {{ $result->quiz?->subject?->subject_name ?? 'SYSTEM UNIT' }}
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            @if($result->is_published === false)
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 border border-amber-100 rounded-md text-[9px] font-bold uppercase tracking-widest">PENDING AUDIT</span>
                            @elseif($result->passed)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-md text-[9px] font-bold uppercase tracking-widest">SUCCESS CERTIFIED</span>
                            @else
                                <span class="px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-md text-[9px] font-bold uppercase tracking-widest">SYNC FAILED</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="text-lg font-bold tabular-nums {{ $result->passed ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ round($result->score) }}%
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $g = 'F';
                                if($result->score >= 95) $g = 'A+';
                                elseif($result->score >= 90) $g = 'A';
                                elseif($result->score >= 85) $g = 'B+';
                                elseif($result->score >= 80) $g = 'B';
                                elseif($result->score >= 75) $g = 'C+';
                                elseif($result->score >= 70) $g = 'C';
                                elseif($result->score >= 60) $g = 'D';

                                $currGradeColor = $gradeColors[$g] ?? '#cbd5e1';
                            @endphp
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[10px] font-bold text-white shadow-sm uppercase" style="background: {{ $currGradeColor }}">
                                {{ $g }}
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="text-xs font-bold text-slate-900 tabular-nums uppercase">{{ $result->completed_at ? $result->completed_at->format('M d, Y') : 'N/A' }}</div>
                            <div class="text-[10px] font-bold text-slate-400 mt-1 tabular-nums">{{ $result->completed_at ? $result->completed_at->format('h:i A') : '' }}</div>
                        </td>
                        <td class="pe-8 py-3 text-right">
                            <a href="{{ route('students.quizzes.result', $result->attempt_id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm">
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i class="fas fa-clipboard-list text-2xl"></i>
                            </div>
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Zero Deployment Records Found</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($results->hasPages())
        <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
            {{ $results->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Data for Chart -->
<div id="chart-data" 
     data-labels='@json(array_column($scoreTrend, "label"))' 
     data-scores='@json(array_column($scoreTrend, "score"))'
     data-dates='@json(array_column($scoreTrend, "date"))'>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('scoreTrendChart').getContext('2d');
    const dataEl = document.getElementById('chart-data');
    const labels = JSON.parse(dataEl.dataset.labels);
    const scores = JSON.parse(dataEl.dataset.scores);

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.15)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Protocol Performance',
                data: scores,
                borderColor: '#4f46e5',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    bodyFont: { weight: 'bold', size: 10 },
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: (ctx) => `YIELD: ${ctx.raw}%`
                    }
                }
            },
            scales: {
                y: {
                    min: 0, max: 100,
                    ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 } },
                    grid: { color: '#f1f5f9', drawBorder: false }
                },
                x: {
                    ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 } },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endsection
