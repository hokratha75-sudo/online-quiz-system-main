@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter text-slate-900 bg-slate-50/30 min-h-screen">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight leading-none">Question Bank</h1>
            <p class="text-sm font-medium text-slate-400 mt-2">Centralized management and reuse of student assessment questions</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('questions.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-3 rounded-2xl text-xs font-bold transition-all flex items-center gap-3 shadow-sm uppercase tracking-widest">
                <i class="fas fa-file-export text-slate-400 text-sm"></i> Export CSV
            </a>
            <a href="{{ route('quizzes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-7 py-3 rounded-2xl text-xs font-bold transition-all flex items-center gap-3 shadow-xl shadow-indigo-600/20 active:scale-[0.98] uppercase tracking-widest">
                <i class="fas fa-plus"></i> Create Question
            </a>
        </div>
    </div>

    <!-- Quick Insights: Question Composition Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @php $total = $stats['total'] ?: 1; @endphp
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Multiple Choice</span>
            <div class="flex items-end gap-2">
                <span class="text-xl font-bold text-slate-900 leading-none">{{ round(($stats['mc']/$total)*100) }}%</span>
                <span class="text-xs font-medium text-slate-400 mb-0.5">of bank</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Single Choice</span>
            <div class="flex items-end gap-2">
                <span class="text-xl font-bold text-slate-900 leading-none">{{ round(($stats['sc']/$total)*100) }}%</span>
                <span class="text-xs font-medium text-slate-400 mb-0.5">of bank</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">True / False</span>
            <div class="flex items-end gap-2">
                <span class="text-xl font-bold text-slate-900 leading-none">{{ round(($stats['tf']/$total)*100) }}%</span>
                <span class="text-xs font-medium text-slate-400 mb-0.5">of bank</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Assets</span>
            <div class="flex items-end gap-2 text-indigo-600">
                <span class="text-xl font-bold leading-none">{{ $stats['total'] }}</span>
                <span class="text-xs font-medium mb-0.5">Verified Items</span>
            </div>
        </div>
    </div>

    <!-- Controls & Filters -->
    <form action="{{ route('questions.bank') }}" method="GET" id="filterForm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-10">
            <!-- BOOTSTRAP FILTER TABS (Button Group) -->
            <div class="btn-group shadow-sm" role="group" aria-label="Question type filters">
                <input type="radio" class="btn-check" name="type" value="all" id="type_all" {{ request('type', 'all') == 'all' ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="btn btn-outline-primary px-4 py-2 text-xs font-bold" for="type_all">All Questions</label>

                <input type="radio" class="btn-check" name="type" value="multiple_choice" id="type_mc" {{ request('type') == 'multiple_choice' ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="btn btn-outline-primary px-4 py-2 text-xs font-bold" for="type_mc">Multiple Choice</label>
                
                <input type="radio" class="btn-check" name="type" value="true_false" id="type_tf" {{ request('type') == 'true_false' ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="btn btn-outline-primary px-4 py-2 text-xs font-bold" for="type_tf">True/False</label>
            </div>
 
            <div class="flex items-center gap-4">
                <!-- Search Input Group -->
                <div class="input-group shadow-sm rounded-2xl overflow-hidden w-64 lg:w-80">
                    <span class="input-group-text bg-white border-end-0 text-slate-400 ps-4">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="SEARCH CONTENT..." 
                           class="form-control border-start-0 text-[10px] font-bold text-slate-700 placeholder-slate-300 uppercase tracking-widest py-3 px-0 shadow-none focus:ring-0">
                </div>
 
                <!-- Select Dropdown -->
                <div class="relative w-full max-w-xs">
                    <select name="subject_id" onchange="this.form.submit()" class="w-full appearance-none rounded-md border border-neutral-300 bg-neutral-50 px-4 py-2 text-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75 dark:border-neutral-700 dark:bg-neutral-900/50 dark:focus-visible:outline-white">
                        <option value="">ALL SUBJECTS</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute right-4 top-1/2 size-4 -translate-y-1/2 text-neutral-600 dark:text-neutral-300" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </div>
 
                <button type="button" id="bulkDeleteBtn" style="display: none;" class="h-12 px-6 bg-rose-50 text-rose-600 border border-rose-100 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all flex items-center gap-3">
                    <i class="fas fa-trash-can text-sm"></i> Delete Selected
                </button>
            </div>
        </div>
    </form>

    <!-- Content Table -->
    <div class="w-full overflow-hidden rounded-md border border-neutral-300 dark:border-neutral-700 mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300" id="bankTable">
                <thead class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                    <tr>
                        <th scope="col" width="40" class="p-4 text-center">
                            <input type="checkbox" id="selectAll" class="form-check-input shadow-none cursor-pointer">
                        </th>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Question Content</th>
                        <th scope="col" width="140" class="p-4 text-center">Subject</th>
                        <th scope="col" width="140" class="p-4 text-center">Type</th>
                        <th scope="col" width="80" class="p-4 text-center">Points</th>
                        <th scope="col" width="130" class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                    @forelse($questions as $index => $question)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input shadow-none cursor-pointer question-checkbox" value="{{ $question->id }}">
                            </td>
                            <td class="text-slate-500 font-medium">{{ $questions->firstItem() + $index }}.</td>
                            <td>
                                <div class="max-w-[400px]">
                                    <p class="text-[13px] font-bold text-slate-900 line-clamp-1 mb-0.5">
                                        {!! strip_tags($question->content) !!}
                                    </p>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        REF: {{ $question->quiz->title ?? 'Global Bank' }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $question->quiz->subject->subject_name ?? 'N/A' }}</span>
                            </td>
                            <td class="text-center">
                                @if($question->type == 'multiple_choice')
                                    <span class="badge bg-primary rounded-pill px-3 py-2 text-[10px] tracking-widest">MULTIPLE CHOICE</span>
                                @elseif($question->type == 'single_choice')
                                    <span class="badge bg-success rounded-pill px-3 py-2 text-[10px] tracking-widest">SINGLE CHOICE</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 text-[10px] tracking-widest">TRUE / FALSE</span>
                                @endif
                            </td>
                            <td class="text-center font-bold text-slate-700">{{ $question->points }} pts</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="btn btn-sm btn-outline-secondary rounded-xl" data-bs-toggle="tooltip" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-xl btn-delete-question" data-bs-toggle="tooltip" title="Delete" data-id="{{ $question->id }}">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-400 font-medium">No results found matching your search.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                ITEMS {{ $questions->firstItem() }} - {{ $questions->lastItem() }} OF {{ $questions->total() }}
            </p>
            <div class="pagination-clean">
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Hidden Delete Forms --}}
<form id="deleteQuestionForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<form id="bulkDeleteForm" method="POST" style="display:none;" action="{{ route('questions.bulkDelete') }}">
    @csrf
</form>

<script>
    // Bulk Select Toggle
    const selectAll = document.getElementById('selectAll');
    const questionCheckboxes = document.querySelectorAll('.question-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    function updateBulkBtn() {
        const checkedCount = document.querySelectorAll('.question-checkbox:checked').length;
        bulkDeleteBtn.style.display = checkedCount > 0 ? 'flex' : 'none';
    }
    
    if(selectAll) {
        selectAll.addEventListener('change', function() {
            questionCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkBtn();
        });
    }

    questionCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBtn);
    });

    // Bulk Delete Action
    bulkDeleteBtn.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;

        window.premiumConfirm(
            'Are you sure you want to permanently remove these ' + selected.length + ' questions? This action cannot be undone.',
            function() {
                const form = document.getElementById('bulkDeleteForm');
                // Clear previous hidden inputs
                form.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());
                // Add new hidden inputs
                selected.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                form.submit();
            },
            'Bulk Delete Questions?'
        );
    });

    // Delete Interaction
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-question');
        if (!btn) return;

        window.premiumConfirm(
            'Are you sure you want to remove this question? This will not affect existing quizzes, but the question will be gone from the bank.',
            function() {
                const id = btn.dataset.id;
                const form = document.getElementById('deleteQuestionForm');
                form.action = `/questions/${id}`; 
                form.submit();
            },
            'Remove Question?'
        );
    });
</script>
@endsection
