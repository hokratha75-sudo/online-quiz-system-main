@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm group hover:bg-indigo-50/50 transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Total Students</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100/50 shadow-sm">
                <i class="fas fa-user-graduate text-sm"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-slate-900 tabular-nums tracking-tight">{{ number_format($totalStudents) }}</h3>
        <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Enrolled</div>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm group hover:bg-emerald-50/50 transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Avg. Score</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/50 shadow-sm">
                <i class="fas fa-chart-line text-sm"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-slate-900 tabular-nums tracking-tight">{{ round($avgScore, 1) }}%</h3>
        <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Overall Perf.</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm group hover:bg-rose-50/50 transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <span class="text-xs font-bold text-rose-600 uppercase tracking-widest">Pass Rate</span>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100/50 shadow-sm">
                <i class="fas fa-check-double text-sm"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-slate-900 tabular-nums tracking-tight">{{ $passRate }}%</h3>
        <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Success Ratio</div>
    </div>

    <div class="bg-indigo-600 rounded-2xl p-6 shadow-xl shadow-indigo-600/20 group relative overflow-hidden transition-all duration-300 hover:scale-[1.02]">
        <div class="absolute top-0 right-0 p-3 opacity-10">
            <i class="fas fa-crown text-6xl text-white rotate-12"></i>
        </div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <span class="text-xs font-bold text-indigo-100 uppercase tracking-widest">Top Performer</span>
            <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center border border-white/20 backdrop-blur-sm">
                <i class="fas fa-crown text-sm"></i>
            </div>
        </div>
        <h3 class="text-xl font-bold text-white truncate relative z-10 tracking-tight uppercase">{{ $topPerformer->user?->username ?? 'N/A' }}</h3>
        <div class="mt-2 text-[10px] font-bold text-indigo-100/60 uppercase tracking-widest relative z-10">Yield Target: {{ $topPerformer?->score ?? 0 }}%</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Grade Distribution -->
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h6 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-6 italic"><i class="fas fa-chart-bar me-2 text-indigo-500"></i> Grade Distribution</h6>
        <div class="relative h-[250px]">
            <canvas id="scoreChart"></canvas>
        </div>
    </div>
    
    <!-- Subject Performance -->
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h6 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-6 italic"><i class="fas fa-graduation-cap me-2 text-indigo-500"></i> Subject Performance</h6>
        <div class="relative h-[250px]">
            <canvas id="subjectChart"></canvas>
        </div>
    </div>

    <!-- Pass/Fail Ratio -->
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm text-center">
        <h6 class="text-[11px] font-black text-slate-900 uppercase tracking-widest mb-6 italic"><i class="fas fa-chart-pie me-2 text-indigo-500"></i> Pass vs Fail Ratio</h6>
        <div class="relative h-[200px]">
            <canvas id="passFailChart"></canvas>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-50 italic">
            <div class="flex justify-around text-[10px] font-black uppercase tracking-widest">
                <div class="text-emerald-600"><i class="fas fa-circle me-1 text-[8px]"></i> Passed ({{ $passRate }}%)</div>
                <div class="text-rose-600"><i class="fas fa-circle me-1 text-[8px]"></i> Failed ({{ 100 - $passRate }}%)</div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filters: Modern Compact -->
<div class="bg-white rounded-2xl border border-slate-100 p-4 mb-8 shadow-sm flex items-center italic">
    <form action="{{ route('quizzes.reports') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 w-full items-center">
        <div class="md:col-span-3 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-indigo-500 text-xs"></i>
            <input type="text" name="search" class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-9 pr-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-900 focus:outline-none focus:border-indigo-500 transition-all placeholder:text-slate-300" placeholder="IDENTIFY SECTOR/SUBJECT..." value="{{ request('search') }}">
        </div>
        @if($userRole === 'admin')
        <div class="md:col-span-2">
            <select name="department_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-900 focus:outline-none focus:border-indigo-500 transition-all">
                <option value="">ALL DEPTS</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ strtoupper($dept->department_name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <select name="teacher_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-900 focus:outline-none focus:border-indigo-500 transition-all">
                <option value="">ALL STAFF</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ strtoupper($teacher->username) }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="md:col-span-3">
            <select name="subject_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-900 focus:outline-none focus:border-indigo-500 transition-all">
                <option value="">ALL SUBJECTS</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ strtoupper($subject->subject_name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2 flex gap-2">
            <button type="submit" class="flex-grow h-11 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-widest flex items-center justify-center transition-all shadow-lg shadow-indigo-600/20">
                <i class="fas fa-sync-alt me-1.5"></i> Sync
            </button>
            <a href="{{ route('quizzes.reports') }}" class="w-10 h-10 bg-white border border-slate-100 text-slate-400 hover:text-indigo-600 rounded-xl flex items-center justify-center transition-all italic shadow-sm">
                <i class="fas fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden mb-8">
    <div class="px-8 py-6 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/30 italic">
        <div>
            <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-widest italic tracking-tighter italic">Assessment records</h3>
            <p class="text-[9px] font-black text-indigo-600 mt-1 uppercase tracking-widest italic tracking-tighter">Detailed analytics of candidate deployments</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="h-11 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-widest flex items-center transition-all shadow-lg shadow-indigo-600/20"><i class="fas fa-print me-2 text-indigo-200"></i> Print Log</button>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="h-10 px-6 bg-white border border-slate-100 text-slate-900 hover:bg-slate-50 rounded-xl text-[9px] font-black uppercase tracking-widest flex items-center transition-all italic shadow-sm"><i class="fas fa-file-excel me-2"></i> Download Export</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-white border-b border-slate-50">
                    <th class="ps-8 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Sync ID</th>
                    <th class="px-6 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Candidate</th>
                    <th class="px-6 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Vector</th>
                    <th class="px-6 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Yield</th>
                    <th class="px-6 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Status</th>
                    <th class="px-6 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Timestamp</th>
                    <th class="pe-8 py-4 text-[9px] font-black text-indigo-600 uppercase tracking-widest text-right italic">Link</th>
                </tr>
            </thead>
                <tbody class="divide-y divide-slate-50 italic">
                    @forelse($results as $result)
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="ps-8 py-4">
                                <span class="text-[10px] font-black text-slate-900 tabular-nums italic">L-{{ str_pad($result->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-lg shadow-indigo-600/20 border border-indigo-500/20 group-hover:scale-110 transition-transform tabular-nums uppercase">
                                        {{ strtoupper(substr($result->user?->username ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ $result->user?->username ?? 'Unknown' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[11px] font-black text-slate-900 uppercase italic leading-tight italic">{{ $result->quiz?->title ?? '--' }}</div>
                                <div class="text-[8px] font-black text-indigo-500 uppercase italic tracking-widest mt-0.5 italic">{{ $result->quiz?->subject?->subject_name ?? 'SYSTEM UNIT' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-1 rounded-full bg-slate-100 overflow-hidden shrink-0">
                                        <div class="h-full {{ $result->score >= ($result->quiz?->pass_percentage ?? 60) ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ $result->score }}%"></div>
                                    </div>
                                    <span class="text-xs font-black italic tabular-nums italic {{ $result->score >= ($result->quiz?->pass_percentage ?? 60) ? 'text-emerald-500' : 'text-rose-500' }}">{{ round($result->score) }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($result->score >= ($result->quiz?->pass_percentage ?? 60))
                                    <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase tracking-widest border border-emerald-100 italic transition-all group-hover:bg-emerald-100 shadow-sm"><i class="fas fa-check-circle me-1 text-[7px]"></i> Pass</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md bg-rose-50 text-rose-600 text-[8px] font-black uppercase tracking-widest border border-rose-100 italic transition-all group-hover:bg-rose-100 shadow-sm"><i class="fas fa-times-circle me-1 text-[7px]"></i> Fail</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[10px] font-black text-slate-900 italic tabular-nums italic uppercase">{{ $result->completed_at ? $result->completed_at->format('d M, Y') : 'N/A' }}</div>
                                <div class="text-[8px] font-black text-indigo-500 uppercase mt-0.5 italic tabular-nums italic">{{ $result->completed_at ? $result->completed_at->format('H:i') : '' }}</div>
                            </td>
                            <td class="pe-8 py-4 text-right">
                                <a href="{{ route('quizzes.result', $result->attempt_id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-100 text-slate-300 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm">
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-20 text-center uppercase text-[10px] font-black text-slate-400 italic tracking-widest">Zero Analysis Found</td></tr>
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
