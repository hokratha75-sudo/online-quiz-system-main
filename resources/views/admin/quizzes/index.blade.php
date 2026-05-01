@extends('layouts.admin')

@section('topbar-title', 'Assessment Directory')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter bg-slate-50/30 min-h-screen">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight uppercase" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">Master Data Quizzes</h1>
            <p class="text-sm text-slate-500 mt-1">Manage and track all assessments, midterms, and final exams.</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-3 rounded-2xl text-xs font-bold transition-all flex items-center gap-3 shadow-sm uppercase tracking-widest">
                <i class="far fa-rotate text-slate-400 text-sm"></i> 
                <span>Refresh</span>
            </button>
            <a href="{{ route('quizzes.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-3 rounded-2xl text-xs font-bold transition-all flex items-center gap-3 shadow-sm uppercase tracking-widest">
                <i class="far fa-file-excel text-emerald-500 text-sm"></i> 
                <span>Export</span>
            </a>
            <a href="{{ route('quizzes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-7 py-3 rounded-2xl text-xs font-bold transition-all flex items-center gap-3 shadow-xl shadow-indigo-600/20 active:scale-[0.98] group uppercase tracking-widest">
                <i class="far fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
                <span>Create Quiz</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar (Modern Style from Image) -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-12">
        <div class="flex flex-col md:flex-row items-center gap-6 w-full lg:w-auto">
            <div class="relative w-full md:w-96 group">
                <i class="far fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                <input type="text" id="quizSearch" placeholder="Search quizzes.." 
                       class="w-full h-12 pl-12 pr-5 bg-white border border-slate-200 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-slate-400 rounded-2xl shadow-sm">
            </div>
            
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <select id="subjectFilter" class="w-full h-12 pl-5 pr-12 bg-white border border-slate-200 text-[10px] font-bold text-slate-600 uppercase tracking-[0.15em] outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 appearance-none rounded-2xl cursor-pointer shadow-sm">
                        <option value="">ALL SUBJECTS</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ strtoupper($subject->subject_name) }}</option>
                        @endforeach
                    </select>
                    <i class="far fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
                
                <div class="relative w-full md:w-56">
                    <select id="statusFilter" class="w-full h-12 pl-5 pr-12 bg-white border border-slate-200 text-[10px] font-bold text-slate-600 uppercase tracking-[0.15em] outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 appearance-none rounded-2xl cursor-pointer shadow-sm">
                        <option value="">ALL STATUSES</option>
                        <option value="published">ACTIVE</option>
                        <option value="draft">DRAFT</option>
                    </select>
                    <i class="far fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
            </div>
        </div>
        
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4 py-2 bg-slate-100 rounded-full">
            <span id="quizCount">{{ $quizzes->count() }}</span> Assessments
        </div>
    </div>

    <!-- Quiz Table (Standard Clean Style) -->
    <div class="card-standard">
        <div class="card-header-standard">
            <h3>Bordered Quiz Table</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table-standard" id="quizTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Quiz Module & Subject</th>
                        <th style="width: 250px;">Engagement (Attempts)</th>
                        <th style="width: 100px;">Label</th>
                        <th style="width: 150px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $index => $item)
                    <tr class="quiz-row" data-subject="{{ $item->subject_id }}" data-status="{{ $item->status }}" data-title="{{ strtolower($item->title) }}">
                        <td>{{ $index + 1 }}.</td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900">{{ $item->title }}</span>
                                <span class="text-[11px] text-slate-400 font-medium tracking-tight uppercase">{{ $item->subject?->subject_name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $attemptRate = $item->attempts_count > 0 ? min(($item->attempts_count / 100) * 100, 100) : 0; // Simulated for demo
                                $barColor = $attemptRate > 80 ? 'bg-green-500' : ($attemptRate > 50 ? 'bg-blue-500' : ($attemptRate > 20 ? 'bg-yellow-500' : 'bg-rose-500'));
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="progress-clean flex-1">
                                    <div class="progress-bar-clean {{ $barColor }}" style="width: {{ $attemptRate }}%"></div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-500 w-8 text-right">{{ $item->attempts_count }}</span>
                            </div>
                        </td>
                        <td>
                            @if($item->status == 'published')
                                <span class="label-standard label-green">ACTIVE</span>
                            @else
                                <span class="label-standard label-yellow">DRAFT</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('quizzes.show', $item->id) }}" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm" title="View">
                                    <i class="far fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('quizzes.edit', $item->id) }}" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all shadow-sm" title="Edit">
                                    <i class="far fa-pen-to-square text-sm"></i>
                                </a>
                                <button type="button" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all shadow-sm btn-delete-quiz" 
                                        data-id="{{ $item->id }}" data-title="{{ $item->title }}" title="Delete">
                                    <i class="far fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-slate-400 font-medium">No quiz modules found in this directory.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Standard Pagination UI (Static for now to match image) -->
        <div class="p-6 border-t border-slate-100 flex justify-end">
            <div class="pagination-clean">
                <a href="#"><i class="far fa-chevron-left text-[10px]"></i></a>
                <span class="active">1</span>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#"><i class="far fa-chevron-right text-[10px]"></i></a>
            </div>
        </div>
    </div>
</div>

{{-- Single-delete hidden form --}}
<form id="singleDeleteForm" method="POST" style="display:none;" action="">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    // ─── Filter Logic ─────────────────────────────────────────────
    const searchInput = document.getElementById('quizSearch');
    const subjectFilter = document.getElementById('subjectFilter');
    const statusFilter = document.getElementById('statusFilter');
    const quizRows = document.querySelectorAll('.quiz-row');
    const countDisplay = document.getElementById('quizCount');

    function applyFilters() {
        const searchQuery = searchInput.value.toLowerCase();
        const subjectValue = subjectFilter.value;
        const statusValue = statusFilter.value;
        
        let visibleCount = 0;

        quizRows.forEach(row => {
            const title = row.getAttribute('data-title');
            const subject = row.getAttribute('data-subject');
            const status = row.getAttribute('data-status');
            
            const matchesSearch = title.includes(searchQuery);
            const matchesSubject = subjectValue === "" || subject === subjectValue;
            const matchesStatus = statusValue === "" || status === statusValue;
            
            if (matchesSearch && matchesSubject && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        countDisplay.textContent = visibleCount;
    }

    searchInput.addEventListener('keyup', applyFilters);
    subjectFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);

    // ─── Single Delete ─────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-quiz');
        if (!btn) return;

        const id    = btn.dataset.id;
        const title = btn.dataset.title;

        window.premiumConfirm(
            'Permanently remove "' + title + '"? This will also delete all student answers and results for this quiz.',
            function() {
                const form = document.getElementById('singleDeleteForm');
                form.action = '{{ url("quizzes") }}/' + id;
                const tokenInput = form.querySelector('input[name="_token"]');
                if (tokenInput) tokenInput.value = getCsrfToken();
                form.submit();
            },
            'Remove Quiz?'
        );
    });
</script>
@endsection
