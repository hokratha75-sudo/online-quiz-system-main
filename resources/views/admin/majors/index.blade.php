@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter text-slate-900">

    <!-- Header Section -->
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-8">
        <div>
            <h1 class="text-2xl md:text-[28px] font-bold text-slate-900 tracking-tight">{{ ucfirst($tab) }} Index</h1>
            <p class="text-[14px] font-medium text-slate-500 mt-1.5">Manage administrative {{ $tab }} and institutional faculties.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md border border-neutral-300 bg-transparent px-4 py-2 text-sm font-medium tracking-wide text-neutral-600 transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:border-neutral-700 dark:text-neutral-300 dark:focus-visible:outline-white">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <a href="{{ route('admin.majors.export', ['tab' => $tab]) }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md border border-neutral-300 bg-transparent px-4 py-2 text-sm font-medium tracking-wide text-neutral-600 transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:border-neutral-700 dark:text-neutral-300 dark:focus-visible:outline-white">
                <i class="fas fa-file-excel text-emerald-500"></i> Export
            </a>
            @if($tab == 'majors')
                <button data-bs-toggle="modal" data-bs-target="#addMajorModal" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium tracking-wide text-white transition hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
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
        <button type="button" class="text-slate-400 hover:text-slate-600 px-2" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
    @endif

    <!-- Data Table Card -->
    <div class="w-full overflow-hidden rounded-md border border-neutral-300 dark:border-neutral-700 mb-8">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-neutral-200 flex flex-col md:flex-row items-center justify-between gap-4 bg-neutral-50 text-neutral-900">
            <div class="flex items-center gap-4">
                <h3 class="text-xs font-bold tracking-widest uppercase">Active {{ ucfirst($tab) }}</h3>
                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                    {{ $items->total() }} Nodes Recorded
                </span>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button onclick="deleteSelected()" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-medium tracking-wide text-rose-600 transition-colors hover:bg-rose-100 hover:border-rose-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                    <i class="fas fa-trash-alt text-[10px]"></i> Remove Selected
                </button>
                <div class="relative w-full sm:w-64">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-neutral-400" aria-hidden="true">
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/><path d="M21 21l-6 -6"/>
                    </svg>
                    <input type="text" id="tableSearch" placeholder="Search indices..." 
                           class="w-full rounded-md border border-neutral-300 bg-white py-2 pl-10 pr-2 text-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:cursor-not-allowed disabled:opacity-75" />
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
                <tbody class="divide-y divide-neutral-200">
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
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ ($item->classes_count ?? 0) > 0 ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10' : 'bg-neutral-100 text-neutral-600' }}">
                                    {{ $item->classes_count ?? 0 }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ ($item->subjects_count ?? 0) > 0 ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10' : 'bg-neutral-100 text-neutral-600' }}">
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
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" 
                                        onclick="editRecord({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ $item->code ?? '' }}', '{{ $item->department_id ?? '' }}', '{{ $item->major_id ?? '' }}')" 
                                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md px-2 py-2 text-sm font-medium tracking-wide text-neutral-600 transition hover:bg-neutral-100 hover:text-neutral-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <input type="checkbox" class="row-checkbox h-4 w-4 rounded border-neutral-300 text-indigo-600 focus:ring-indigo-600" value="{{ $item->id }}" data-name="{{ $item->name }}">
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-24 text-center uppercase text-[10px] font-bold text-slate-300 tracking-widest">Zero nodes detected</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-8 py-5 border-t border-slate-50 bg-slate-50/20 flex flex-col md:flex-row items-center justify-between gap-4">
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
        <div class="modal-content rounded-[32px] border-0 shadow-2xl overflow-hidden">
            <div class="bg-indigo-600 px-8 py-6 flex items-center justify-between">
                <h5 class="text-xl font-bold text-white tracking-tight flex items-center gap-3" id="majorModalTitle">
                    <i class="fas fa-bookmark text-indigo-200"></i> Add Major
                </h5>
                <button type="button" class="text-indigo-200 hover:text-white transition-colors" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.majors.store') }}" method="POST" id="majorForm" class="p-6">
                @csrf
                <input type="hidden" name="_method" value="POST" id="majorFormMethod">
                
                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="form-label font-semibold text-slate-700">Major Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" id="majorCode" required placeholder="e.g. CS-01" 
                                   class="form-control uppercase">
                        </div>
                        <div>
                            <label class="form-label font-semibold text-slate-700">Major Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="majorName" required placeholder="e.g. Computer Science" 
                                   class="form-control">
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label font-semibold text-slate-700">Department Alignment <span class="text-rose-500">*</span></label>
                        <select name="department_id" id="majorDept" required 
                                class="form-select cursor-pointer">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="majorBtnSubmit" class="btn btn-primary">Save Major</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-[32px] border-0 shadow-2xl overflow-hidden">
            <div class="bg-indigo-600 px-8 py-6 flex items-center justify-between">
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

    // Search Vector
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#dataTable tbody .table-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
        });
    });

    // Selection Core
    document.getElementById('selectAll').addEventListener('change', function() {
        const state = this.checked;
        document.querySelectorAll('.row-checkbox').forEach(cb => { cb.checked = state; });
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
