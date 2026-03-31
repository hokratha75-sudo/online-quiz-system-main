@extends('layouts.admin')

@section('topbar-title', 'Assessment Directory')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-semibold text-slate-900 tracking-tight">Master Data Quizzes</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Manage and track all assessments, midterms, and final exams.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center gap-2 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <i class="fas fa-sync-alt text-xs text-slate-400"></i> Refresh
            </button>
            <a href="{{ route('quizzes.export') }}" class="bg-white hover:bg-blue-50 text-blue-600 border border-slate-200/80 hover:border-emerald-200 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center gap-2 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <i class="fas fa-file-excel text-xs"></i> Export
            </a>
            <a href="{{ route('quizzes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center gap-2 shadow-sm">
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

    <!-- Data Table Card -->
    <div class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-semibold text-slate-900 tracking-tight">Active Quizzes</h3>
                <span class="px-2 py-0.5 rounded-full bg-slate-200/60 text-slate-600 text-[11px] font-bold tracking-wide">{{ count($quizzes) }} items</span>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button class="bg-white hover:bg-slate-50 text-rose-600 border border-slate-200 px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-[0_1px_2px_rgba(0,0,0,0.02)]" onclick="deleteSelected()">
                    <i class="fas fa-trash-alt text-xs"></i> Bulk Delete
                </button>
                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="tableSearch" placeholder="Search by title, subject..." 
                           class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200/70 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="dataTable">
                <thead>
                    <tr class="bg-white border-b border-slate-100/80">
                        <th class="px-5 py-4 w-12 text-center">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-20">Code</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Quiz Title</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Subject</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Items</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80 bg-white">
                    @forelse($quizzes as $item)
                    <tr class="table-row hover:bg-slate-50/50 transition-colors group">
                        <td class="px-5 py-4 text-center">
                            <input type="checkbox" class="row-checkbox w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500 cursor-pointer" value="{{ $item->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[13px] font-mono text-slate-500 font-medium">QZ{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('quizzes.show', $item->id) }}" class="flex items-center gap-3 group-hover:text-blue-600 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100/50">
                                    <i class="fas fa-book-open text-xs"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-slate-800 group-hover:text-blue-600">{{ $item->title }}</h4>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-700">{{ $item->subject->subject_name ?? 'N/A' }}</span>
                                <span class="text-[12px] text-slate-500 mt-0.5">{{ $item->subject->department->department_name ?? 'Ungrouped' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($item->questions_count ?? 0) > 0 ? 'bg-slate-100 text-slate-700 border border-slate-200/80 shadow-sm' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                {{ $item->questions_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'published')
                                <span class="px-2.5 py-1 rounded-md border border-emerald-200 bg-blue-50 text-blue-700 text-[11px] font-semibold tracking-wide inline-flex items-center gap-1">
                                    <i class="fas fa-circle text-[8px]"></i> Published
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-md border border-amber-200 bg-amber-50 text-amber-700 text-[11px] font-semibold tracking-wide inline-flex items-center gap-1">
                                    <i class="far fa-circle text-[8px]"></i> Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 transition-opacity opacity-70 group-hover:opacity-100">
                                <a href="{{ route('quizzes.edit', $item->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors tooltip-trigger" title="Edit">
                                    <i class="far fa-edit text-sm"></i>
                                </a>
                                <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors btn-delete-quiz" 
                                    title="Delete" 
                                    data-id="{{ $item->id }}" 
                                    data-title="{{ $item->title }}">
                                    <i class="far fa-trash-alt text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="p-12 text-center flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-folder-open text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-base font-semibold text-slate-800 tracking-tight">No Quizzes Active</h3>
                                <p class="text-sm text-slate-500 mt-1 max-w-sm">There are currently no quizzes registered in the database. Add your first assessment!</p>
                                <a href="{{ route('quizzes.create') }}" class="mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700">Create New Quiz &raquo;</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
            <span class="text-sm text-slate-500">
                Displaying all <span class="font-medium text-slate-700">{{ count($quizzes) }}</span> entries
            </span>
        </div>
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
