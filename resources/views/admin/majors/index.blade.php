@extends('layouts.admin')

@section('content')
<style>
    
    /* Custom Scrollbar for sleek aesthetic */
    .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4f46e5; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
</style>
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter text-slate-900">

    <!-- Header Section -->
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-8">
        <div>
            <h1 class="text-2xl md:text-[28px] font-bold text-slate-900 tracking-tight">{{ ucfirst($tab) }} Index</h1>
            <p class="text-[14px] font-medium text-slate-500 mt-1.5">Manage administrative {{ $tab }} and institutional faculties.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <a href="{{ route('admin.majors.export', ['tab' => $tab]) }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm no-underline">
                <i class="fas fa-file-excel text-emerald-500"></i> Export
            </a>
            @if($tab == 'majors')
                <button data-bs-toggle="modal" data-bs-target="#addMajorModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg shadow-indigo-600/20 active:scale-[0.98] border-none">
                    <i class="fas fa-plus text-xs text-indigo-200"></i> New Major
                </button>
            @elseif($tab == 'classes')
                <button data-bs-toggle="modal" data-bs-target="#addClassModal" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium tracking-wide text-white transition hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <i class="fas fa-plus text-xs text-indigo-200"></i> New Class
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-white border border-emerald-100 rounded-2xl p-3.5 flex items-center justify-between shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100/50">
                <i class="fas fa-check text-emerald-500 text-xs"></i>
            </div>
            <div>
                <h4 class="text-[13px] font-bold text-slate-900 leading-tight">Action Successful</h4>
                <p class="text-[11px] font-medium text-slate-500">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="group relative w-10 h-10 rounded-full bg-blue-50 border border-pink-200 text-pink-400 hover:bg-blue-100 hover:text-pink-500 hover:scale-110 active:scale-95 transition-all duration-200 ease-out focus:outline-none shadow-sm hover:shadow-pink-200/50" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-sm group-hover:rotate-90 transition-transform duration-200"></i>
        </button>
    </div>
    @endif

    <!-- Data Table Card -->
    <div class="w-full overflow-hidden rounded-2xl border border-neutral-300 dark:border-neutral-700 mb-8">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-neutral-200 flex flex-col md:flex-row items-center justify-between gap-4 bg-white text-neutral-900">
            <div class="flex items-center gap-4">
                <h3 class="text-xs font-bold tracking-widest uppercase">Active {{ ucfirst($tab) }}</h3>
                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                    {{ $items->total() }} Nodes Recorded
                </span>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button onclick="deleteSelected()" class="bg-white hover:bg-rose-200 text-rose-600 border border-slate-100 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm">
                    <i class="fas fa-trash-alt text-[10px]"></i> Remove Selected
                </button>
                <div class="relative w-full sm:w-64">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-neutral-400" aria-hidden="true">
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/><path d="M21 21l-6 -6"/>
                    </svg>
                    <input type="text" id="tableSearch" placeholder="Search indices..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium placeholder:text-slate-300 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none" />
                </div>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full border-collapse text-left text-sm text-neutral-600" id="dataTable">
                <thead class="border-b border-neutral-200 bg-neutral-50 text-sm text-neutral-900">
                    <tr>
                        <th scope="col" class="p-4 w-12 text-center">#</th>
                        <th scope="col" class="p-4 w-24">Code</th>
                        @if($tab == 'majors')
                            <th scope="col" class="p-4">Major Details</th>
                            <th scope="col" class="p-4">Department</th>
                            <th scope="col" class="p-4 text-center">Classes</th>
                            <th scope="col" class="p-4 text-center">Subjects</th>
                        @elseif($tab == 'classes')
                            <th scope="col" class="p-4">Class Details</th>
                            <th scope="col" class="p-4">Major</th>
                            <th scope="col" class="p-4 text-center">Students</th>
                            <th scope="col" class="p-4 text-center">Subjects</th>
                        @endif
                        <th scope="col" class="p-4 text-right">
                            <input type="checkbox" id="selectAll" class="h-4 w-4 rounded border-neutral-300 text-indigo-600 focus:ring-indigo-600">
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white" id="tableBody">
                    @forelse($items as $item)
                    <tr class="table-row hover:bg-neutral-100 transition-colors border-b border-neutral-100 group">
                        <td class="p-4 text-center">{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center rounded bg-neutral-200 px-2 py-1 text-xs font-mono font-medium text-neutral-700">{{ $item->code }}</span>
                        </td>
                        
                        @if($tab == 'majors')
                            <td class="p-4">
                                <a href="{{ route('admin.majors.show', $item->id) }}" class="flex items-center gap-2">
                                    <i class="fas fa-bookmark text-indigo-600"></i>
                                    <span class="font-medium text-neutral-900">{{ $item->name }}</span>
                                </a>
                            </td>
                            <td class="p-4">
                                <div class="text-neutral-900">{{ $item->department->department_name ?? 'SYSTEM UNIT' }}</div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($item->classes_count ?? 0) > 0 ? 'bg-sky-50 text-sky-700 border border-sky-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                    {{ $item->classes_count ?? 0 }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($item->subjects_count ?? 0) > 0 ? 'bg-sky-50 text-sky-700 border border-sky-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                    {{ $item->subjects_count ?? 0 }}
                                </span> 
                            </td>
                        @elseif($tab == 'classes')
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-layer-group text-indigo-600"></i>
                                    <span class="font-medium text-neutral-900">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="text-neutral-900">{{ $item->major->name ?? 'N/A' }}</div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ ($item->students_count ?? 0) > 0 ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10' : 'bg-neutral-100 text-neutral-600' }}">
                                    {{ $item->students_count ?? 0 }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ ($item->subjects_count ?? 0) > 0 ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10' : 'bg-neutral-100 text-neutral-600' }}">
                                    {{ $item->subjects_count ?? 0 }}
                                </span>
                            </td>
                        @endif
                        
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <button type="button" 
                                        onclick='editRecord(
                                        {{ $item->id }},
                                        @json($item->name),
                                        @json($item->code ?? ""),
                                        @json($item->department_id ?? ""),
                                        @json($item->major_id ?? "")
                                    )'
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 transition-colors tooltip-trigger border-none" title="Edit">
                                    <i class="fas fa-edit text-[13px]"></i>
                                </button>
                                <input type="checkbox" class="row-checkbox h-4 w-4 rounded border-neutral-300 text-indigo-600 focus:ring-indigo-600" value="{{ $item->id }}" data-name="{{ $item->name }}">
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyStateRow">
                        <td colspan="7">
                            <div class="p-12 text-center flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-building text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-base font-semibold text-slate-800 tracking-tight">No Majors Found</h3>
                                <p class="text-sm text-slate-500 mt-1 max-w-sm">There are currently no majors. Click "New Major" to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    <!-- Hidden row shown only when search returns zero results -->
                    <tr id="noSearchResultsRow" style="display: none;">
                        <td colspan="100%" class="py-16 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-search text-4xl text-slate-300 mb-3"></i>
                                <h3 class="text-sm font-semibold text-slate-600">No matching {{ $tab }}</h3>
                                <p class="text-xs text-slate-400 mt-1">Try a different keyword or clear your search.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-50 bg-slate-500/20 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest tabular-nums">
                Cluster Range: {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} Nodes
            </span>
            <div class="flex justify-center md:justify-end">
                {{ $items->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

</div>

<!-- Structural Modals -->
<div class="modal fade" id="addMajorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-[#5f60ef] to-[#9a4ce7] px-6 py-4 flex items-center justify-between">
                <h5 class="text-xl font-bold text-white tracking-tight flex items-center gap-3" id="majorModalTitle">
                    <i class="fas fa-bookmark text-white text-sm"></i>
                    <span class="text-lg">Add Major</span>
                </h5>
                <button type="button" class="group relative w-10 h-10 rounded-full bg-blue-50 border border-pink-200 text-pink-400 hover:bg-blue-100 hover:text-pink-500 hover:scale-110 active:scale-95 transition-all duration-200 ease-out focus:outline-none shadow-sm hover:shadow-pink-200/50" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-sm group-hover:rotate-90 transition-transform duration-200"></i>
                </button>
            </div>
            <form action="{{ route('admin.majors.store') }}" method="POST" id="majorForm" class="divide-y divide-slate-100">
                @csrf
                <input type="hidden" name="_method" value="POST" id="majorFormMethod">
                
                    <div class="grid grid-cols-1 gap-3 p-6">
                        <div>
                            <label class="form-label font-semibold text-slate-700">Major Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" id="majorCode" required placeholder="e.g. CS-01" 
                                   class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none uppercase">
                        </div>
                        <div>
                            <label class="form-label font-semibold text-slate-700">Major Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="majorName" required placeholder="e.g. Computer Science" 
                                   class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                        </div>
                        <div class="relative">
                            <label class="form-label font-semibold text-slate-700">Department Alignment <span class="text-rose-500">*</span></label>
                            <select name="department_id" id="majorDept" required 
                                    class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none appearance-none cursor-pointer">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}">{{ $d->department_name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute top-8 inset-y-0 right-3 flex items-center text-slate-400">
                                <i class="fas fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>
                
                <div class=" flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-100 p-6">
                    <button type="button" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="majorBtnSubmit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-sm border-none">Save Major</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-[#5f60ef] to-[#9a4ce7] px-6 py-4 flex items-center justify-between">
                <h5 class="text-xl font-bold text-white tracking-tight flex items-center gap-3" id="classModalTitle">
                    <i class="fas fa-layer-group text-indigo-200"></i> Add Class
                </h5>
                <button type="button" class="text-indigo-200 hover:text-white transition-colors" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.classes.store') }}" method="POST" id="classForm" class="p-6">
                @csrf
                <input type="hidden" name="_method" value="POST" id="classFormMethod">
                
                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="form-label font-semibold text-slate-700">Class Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" id="classCode" required placeholder="e.g. M1" 
                                   class="form-control uppercase">
                        </div>
                        <div>
                            <label class="form-label font-semibold text-slate-700">Class Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="className" required placeholder="e.g. Morning Class" 
                                   class="form-control">
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label font-semibold text-slate-700">Specialization Core <span class="text-rose-500">*</span></label>
                        <select name="major_id" id="classMajor" required 
                                class="form-select cursor-pointer">
                            <option value="">-- Select Major --</option>
                            @foreach($majors_all as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="classBtnSubmit" class="btn btn-primary">Save Class</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    const currentTab = '{{ $tab }}';

    // Improved Search Vector with "no results" handling
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#tableBody .table-row');
        let visibleCount = 0;

        rows.forEach(row => {
            if (!query) {
                row.style.display = '';
                visibleCount++;
            } else {
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
        });

        const noResultsRow = document.getElementById('noSearchResultsRow');
        const emptyStateRow = document.getElementById('emptyStateRow');

        // If there are no rows at all (initial empty state)
        if (rows.length === 0) {
            if (noResultsRow) noResultsRow.style.display = 'none';
            return;
        }

        if (visibleCount === 0) {
            if (noResultsRow) noResultsRow.style.display = '';
            if (emptyStateRow) emptyStateRow.style.display = 'none';
        } else {
            if (noResultsRow) noResultsRow.style.display = 'none';
            if (emptyStateRow && rows.length === 0) emptyStateRow.style.display = '';
        }
    });

    // Selection Core – only visible rows
    document.getElementById('selectAll').addEventListener('change', function() {
        const state = this.checked;
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            const row = cb.closest('.table-row');
            if (row && row.style.display !== 'none') {
                cb.checked = state;
            }
        });
    });

    // Data Removal Logic
    function deleteSelected() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (!selected.length) return alert('Please select at least one item to remove.');

        window.premiumConfirm(
            `Are you sure you want to remove these ${selected.length} items? This will also affect all associated student records and course data.`,
            function() {
                const form = document.getElementById('deleteForm');
                if (currentTab === 'majors') form.action = '{{ route("admin.majors.bulkDelete") }}';
                if (currentTab === 'classes') form.action = '{{ route("admin.classes.bulkDelete") }}';
                
                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                selected.forEach(item => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = item.value;
                    form.appendChild(input);
                });
                form.submit();
            },
            'Remove Multiple Items?'
        );
    }

    // Modal Synchronization Logic
    function editRecord(id, name, code, dept, major) {
        if (currentTab === 'majors') {
            document.getElementById('majorModalTitle').innerText = 'Edit Major Details';
            document.getElementById('majorForm').action = '/admin/majors/' + id;
            document.getElementById('majorFormMethod').value = 'PUT';
            document.getElementById('majorCode').value = code;
            document.getElementById('majorName').value = name;
            document.getElementById('majorDept').value = dept;
            document.getElementById('majorBtnSubmit').innerText = 'Save Changes';
            new bootstrap.Modal(document.getElementById('addMajorModal')).show();
        } 
        else if (currentTab === 'classes') {
            document.getElementById('classModalTitle').innerText = 'Edit Class Details';
            document.getElementById('classForm').action = '/admin/classes/' + id;
            document.getElementById('classFormMethod').value = 'PUT';
            document.getElementById('classCode').value = code;
            document.getElementById('className').value = name;
            document.getElementById('classMajor').value = major;
            document.getElementById('classBtnSubmit').innerText = 'Save Changes';
            new bootstrap.Modal(document.getElementById('addClassModal')).show();
        }
    }

    // Modal Lifecycle Hooks
    ['addMajorModal', 'addClassModal'].forEach(id => {
        document.getElementById(id).addEventListener('hidden.bs.modal', function() {
            const prefix = id.includes('Major') ? 'major' : 'class';
            const form = document.getElementById(prefix + 'Form');
            form.reset();
            form.action = '/admin/' + (prefix === 'major' ? 'majors' : 'classes');
            document.getElementById(prefix + 'FormMethod').value = 'POST';
            document.getElementById(prefix + 'ModalTitle').innerText = 'New ' + (prefix === 'major' ? 'Major' : 'Class');
            document.getElementById(prefix + 'BtnSubmit').innerText = 'Save Item';
        });
    });
</script>
@endsection