@extends('layouts.admin')

@section('topbar-title', 'Assessment Directory')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Master Data Quizzes</h1>
            <p class="text-sm text-slate-500 mt-1">Manage and track all assessments, midterms, and final exams. Assessment Cloud v2.0.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-sync-alt text-slate-400 text-xs"></i> Refresh
            </button>
            <a href="{{ route('quizzes.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-file-excel text-emerald-500 text-xs"></i> Export
            </a>
            <a href="{{ route('quizzes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-md shadow-indigo-500/20">
                <i class="fas fa-plus text-xs"></i> Create Quiz
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-blue-50 border border-emerald-200 text-blue-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-3 shadow-sm">
        <i class="fas fa-check-circle text-blue-500"></i>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-circle text-rose-500 text-base"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <!-- Advanced Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($quizzes as $item)
        <div class="group bg-white rounded-[32px] border border-slate-200/60 p-2 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
            <div class="bg-slate-50 rounded-[28px] p-6 lg:p-8 h-full flex flex-col border border-white shadow-inner relative overflow-hidden">
                <!-- Status & Time Badge -->
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-slate-600">
                        @if($item->status == 'published')
                            <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-emerald-600">Active</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                <span class="text-slate-500">Draft</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-600 font-semibold text-[11px] bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                        <i class="far fa-clock text-slate-400"></i>
                        <span>{{ $item->time_limit ?? 30 }}m</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="mb-8 min-h-[90px]">
                    <div class="text-xs font-semibold text-indigo-600 tracking-wide mb-1.5">{{ $item->subject?->subject_name ?? 'General Quiz' }}</div>
                    <h3 class="text-lg font-bold text-slate-900 leading-tight group-hover:text-indigo-600 transition-colors">{{ $item->title }}</h3>
                    <p class="text-sm font-medium text-slate-500 mt-2 line-clamp-2 leading-relaxed">{{ $item->description ?: 'No briefing provided for this module.' }}</p>
                </div>

                <!-- Stats -->
                <div class="mt-auto pt-8 border-t border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <span class="text-lg font-bold text-slate-900 tabular-nums leading-none">{{ $item->questions_count ?? 0 }}</span>
                            <span class="text-[11px] font-medium text-slate-500 mt-1">Questions</span>
                        </div>
                        <div class="w-px h-6 bg-slate-200"></div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold text-slate-900 tabular-nums leading-none">{{ $item->attempts_count ?? 0 }}</span>
                            <span class="text-[11px] font-medium text-slate-500 mt-1">Attempts</span>
                        </div>
                    </div>

                    <!-- Compact Actions -->
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('quizzes.show', $item->id) }}" class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-lg transition-all" title="View Statistics">
                            <i class="fas fa-chart-line text-xs"></i>
                        </a>
                        <a href="{{ route('quizzes.edit', $item->id) }}" class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-lg transition-all" title="Edit Content">
                            <i class="fas fa-pencil-alt text-xs"></i>
                        </a>
                        <button type="button" class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-100 hover:shadow-lg transition-all btn-delete-quiz" 
                                data-id="{{ $item->id }}" data-title="{{ $item->title }}" title="Remove">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-slate-50 rounded-[40px] border-2 border-dashed border-slate-200 p-20 text-center">
            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-sm">
                <i class="fas fa-inbox text-slate-300 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight mb-2">No Quizzes Found</h3>
            <p class="text-slate-500 font-medium max-w-sm mx-auto mb-10 text-sm">You haven't added any quizzes yet. Start by creating your first quiz.</p>
            <a href="{{ route('quizzes.create') }}" class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3.5 rounded-xl font-semibold shadow-md transition-all">
                <i class="fas fa-plus"></i> Create Quiz
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    // Live Search functionality
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let query = this.value.toLowerCase();
        let rows = document.querySelectorAll('#dataTable tbody .table-row');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        let checked = this.checked;
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = checked;
        });
    });

    // Bulk Delete
    function deleteSelected() {
        let selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one quiz to delete.');
            return;
        }

        if (confirm('Are you absolutely sure you want to delete the ' + selected.length + ' selected quiz(zes)? All data will be irrecoverable.')) {
            let form = document.getElementById('deleteForm');
            form.action = '{{ route("quizzes.bulkDelete") }}';
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            selected.forEach(function(item) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = item.value;
                form.appendChild(input);
            });
            form.submit();
        }
    }

    // Single Delete Custom Event Listener
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-quiz')) {
            const btn = e.target.closest('.btn-delete-quiz');
            const id = btn.dataset.id;
            const title = btn.dataset.title;

            if (confirm('Are you certain you want to delete "' + title + '"? This will permanently remove all questions and student results.')) {
                const form = document.getElementById('deleteForm');
                form.action = '/quizzes/' + id;
                form.innerHTML = '';
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);

                form.submit();
            }
        }
    });
</script>
@endsection
