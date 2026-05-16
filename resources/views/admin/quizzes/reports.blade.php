@extends('layouts.admin')
@section('topbar-title', 'Report Management')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter text-slate-900 bg-slate-50/30 min-h-screen">
    
    <!-- Hero Metrics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="card-standard p-6 group transition-all">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Students</span>
                <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-200 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fas fa-user-graduate text-xs"></i>
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
                    <i class="fas fa-chart-line text-xs"></i>
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
                    <i class="fas fa-check-double text-xs"></i>
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
                <i class="fas fa-chart-bar text-indigo-500"></i> Grade Distribution
            </h6>
            <div class="relative h-[220px]">
                <canvas id="scoreChart"></canvas>
            </div>
        </div>
        
        <div class="card-standard p-6">
            <h6 class="text-[11px] font-bold text-slate-900 mb-6 flex items-center gap-2 uppercase tracking-widest">
                <i class="fas fa-graduation-cap text-indigo-500"></i> Subject Analytics
            </h6>
            <div class="relative h-[220px]">
                <canvas id="subjectChart"></canvas>
            </div>
        </div>

        <div class="card-standard p-6 text-center">
            <h6 class="text-[11px] font-bold text-slate-900 mb-6 flex items-center gap-2 justify-center uppercase tracking-widest">
                <i class="fas fa-chart-pie text-indigo-500"></i> Success Ratio
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
                    <i class="fas fa-print text-slate-400"></i> Print
                </button>
                <button type="button" @click="$dispatch('open-export-modal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-2xl text-[11px] font-bold transition-all flex items-center gap-2 shadow-xl shadow-indigo-600/20 active:scale-[0.98] uppercase tracking-widest">
                    <i class="fas fa-file-export"></i> Export
                </button>
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
                                    <i class="fas fa-chevron-right text-[10px]"></i>
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
        <div class="px-4 py-3 border-t border-slate-50 bg-slate-100/80 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest tabular-nums">
                Showing: {{ $results->firstItem() ?? 0 }} - {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} Records
            </span>
            <div class="custom-pagination flex items-center">
                {{ $results->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @else
            @if($results->total() > 0)
            <div class="p-4 border-t border-slate-50 bg-slate-100/80 flex flex-col md:flex-row items-center justify-between gap-4">
                <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest tabular-nums">
                    Displaying all <span class="font-medium text-slate-700">{{ $results->total() }}</span> records
                </span>
            </div>
            @endif
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
        .sidebar, .topbar, form, button, .pagination, .no-print, .custom-pagination { display: none !important; }
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

<!-- Export Modal (Alpine.js Component) -->
<div 
    x-data="studentExportModal()" 
    x-show="isOpen" 
    style="display: none;"
    class="fixed inset-0 z-[100] overflow-y-auto"
    @open-export-modal.window="isOpen = true"
>
    <!-- Backdrop -->
    <div 
        x-show="isOpen"
        x-transition.opacity
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
        @click="isOpen = false"
    ></div>

    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div 
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative inline-block w-full max-w-lg p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl border border-slate-100 font-inter z-10"
        >
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Export Academic Report</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1">Configure your export parameters</p>
                </div>
                <button @click="isOpen = false" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 w-8 h-8 flex items-center justify-center rounded-xl transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('reports.export.download') }}" method="GET" class="space-y-6">
                <!-- Dropdown 1: Department -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2">Department</label>
                    <select 
                        name="department_id" 
                        x-model="departmentId"
                        @change="fetchMajors()"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all outline-none"
                    >
                        <option value="">All Departments</option>
                        @foreach(\App\Models\Department::all() as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown 2: Major -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2">Major</label>
                    <select 
                        name="major_id" 
                        x-model="majorId"
                        @change="fetchClasses()"
                        :disabled="!departmentId"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <option value="">All Majors</option>
                        <template x-for="major in majors" :key="major.id">
                            <option :value="major.id" x-text="major.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Dropdown 3: Class -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2">Class</label>
                    <select 
                        name="class_id" 
                        :disabled="!majorId"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <option value="">All Classes</option>
                        <template x-for="cls in classes" :key="cls.id">
                            <option :value="cls.id" x-text="cls.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Data Customization Checkboxes -->
                <div class="pt-4 border-t border-slate-100">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-3">Include Data</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="include_scores" value="1" checked class="w-4 h-4 text-indigo-600 bg-slate-50 border-slate-300 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">Include Quiz Scores & Averages</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="include_status" value="1" checked class="w-4 h-4 text-indigo-600 bg-slate-50 border-slate-300 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">Include Overall Passing Status</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="include_logs" value="1" class="w-4 h-4 text-indigo-600 bg-slate-50 border-slate-300 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">Include Activity/Attendance Logs</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" @click="isOpen = false" class="px-6 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all uppercase tracking-widest">
                        Cancel
                    </button>
                    <button type="submit" @click="isOpen = false" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-600/20 active:scale-95 uppercase tracking-widest">
                        <i class="fas fa-file-export"></i> Download CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function studentExportModal() {
    return {
        isOpen: false,
        departmentId: '',
        majorId: '',
        majors: [],
        classes: [],
        
        async fetchMajors() {
            this.majorId = '';
            this.classes = [];
            this.majors = [];
            
            if (!this.departmentId) return;

            try {
                const response = await fetch(`/api/departments/${this.departmentId}/majors`);
                this.majors = await response.json();
            } catch (error) {
                console.error('Error fetching majors:', error);
            }
        },

        async fetchClasses() {
            this.classes = [];
            
            if (!this.majorId) return;

            try {
                const response = await fetch(`/api/majors/${this.majorId}/classes`);
                this.classes = await response.json();
            } catch (error) {
                console.error('Error fetching classes:', error);
            }
        }
    }
}
</script>

@endsection
