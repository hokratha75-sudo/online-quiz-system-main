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
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-100">
                <i class="fas fa-sync-alt text-slate-400"></i> Refresh
            </button>
            <a href="{{ route('admin.majors.export', ['tab' => $tab]) }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-100">
                <i class="fas fa-file-excel text-emerald-500"></i> Export
            </a>
            @if($tab == 'majors')
                <button data-bs-toggle="modal" data-bs-target="#addMajorModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="fas fa-plus text-indigo-200 text-xs"></i> New Major
                </button>
            @elseif($tab == 'classes')
                <button data-bs-toggle="modal" data-bs-target="#addClassModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="fas fa-plus text-indigo-200 text-xs"></i> New Class
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="mb-8 bg-white border border-emerald-100 rounded-[20px] p-4 flex items-center justify-between shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden transition-all duration-300">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>
        <div class="flex items-center gap-4 ml-2">
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100/50">
                <i class="fas fa-check text-emerald-500 text-sm"></i>
            </div>
            <div>
                <h4 class="text-[14px] font-bold text-slate-900 tracking-tight leading-none mb-1">Action Successful</h4>
                <p class="text-[13px] font-medium text-slate-500">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="w-8 h-8 mr-1 rounded-full hover:bg-slate-50 flex items-center justify-center text-slate-400 transition-colors focus:outline-none" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden mb-8">
        
        <!-- Toolbar -->
        <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row items-center justify-between gap-6 bg-slate-50/10">
            <div class="flex items-center gap-4">
                <h3 class="text-xs font-bold text-slate-900 tracking-widest uppercase">Active {{ ucfirst($tab) }}</h3>
                <span class="px-2.5 py-1 rounded-md bg-white border border-slate-100 text-indigo-600 text-[10px] font-bold tracking-widest uppercase shadow-sm tabular-nums whitespace-nowrap">{{ $items->total() }} Nodes Recorded</span>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button class="bg-white hover:bg-rose-50 text-rose-600 border border-slate-100 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm whitespace-nowrap" onclick="deleteSelected()">
                    <i class="fas fa-trash-alt text-[10px]"></i> Delete Selected
                </button>
                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-indigo-600 text-[10px]"></i>
                    <input type="text" id="tableSearch" placeholder="Search indices..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-100 rounded-xl text-xs font-bold text-slate-900 uppercase tracking-widest focus:outline-none focus:border-indigo-500 transition-all shadow-sm">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="dataTable">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200/70">
                        <th class="ps-8 py-4 w-12 text-center text-slate-500">
                            #
                        </th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left w-24">Code</th>
                        @if($tab == 'majors')
                            <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left">Major Details</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left">Department</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Classes</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Subjects</th>
                        @elseif($tab == 'classes')
                            <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left">Class Details</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left">Major</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Students</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Subjects</th>
                        @endif
                        <th class="pe-8 py-4 text-right">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 cursor-pointer transition-colors shadow-sm inline-block translate-y-[2px]">
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($items as $item)
                    <tr class="table-row hover:bg-slate-50/50 transition-all group">
                        <td class="ps-8 py-4 text-xs font-semibold text-slate-400 tabular-nums text-center">{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 tracking-wide">{{ $item->code }}</span>
                        </td>
                        
                        @if($tab == 'majors')
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.majors.show', $item->id) }}" class="flex items-center gap-3.5 transition-all group-hover:translate-x-1">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50/80 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100/50 shadow-sm">
                                        <i class="fas fa-bookmark text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-slate-900 tracking-tight">{{ $item->name }}</h4>
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-xs font-medium text-slate-500">{{ $item->department->department_name ?? 'SYSTEM UNIT' }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($item->classes_count ?? 0) > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                    {{ $item->classes_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($item->subjects_count ?? 0) > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                    {{ $item->subjects_count ?? 0 }}
                                </span>
                            </td>
                        @elseif($tab == 'classes')
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3.5 transition-all group-hover:translate-x-1">
                                    <div class="w-10 h-10 rounded-xl bg-sky-50/80 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100/50 shadow-sm">
                                        <i class="fas fa-layer-group text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-slate-900 tracking-tight">{{ $item->name }}</h4>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-xs font-medium text-slate-500">{{ $item->major->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($item->students_count ?? 0) > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                    {{ $item->students_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($item->subjects_count ?? 0) > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                    {{ $item->subjects_count ?? 0 }}
                                </span>
                            </td>
                        @endif
                        
                        <td class="pe-8 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 opacity-70 group-hover:opacity-100 transition-opacity">
                                <button type="button" 
                                        onclick="editRecord({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ $item->code ?? '' }}', '{{ $item->department_id ?? '' }}', '{{ $item->major_id ?? '' }}')" 
                                        class="w-8 h-8 rounded-lg border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all flex items-center justify-center shadow-sm" title="Edit">
                                    <i class="fas fa-edit text-[13px]"></i>
                                </button>
                                <input type="checkbox" class="row-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 cursor-pointer shadow-sm ms-2" value="{{ $item->id }}" data-name="{{ $item->name }}">
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
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Major Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" id="majorCode" required placeholder="e.g. CS-01" 
                                   class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-sm uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Major Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="majorName" required placeholder="e.g. Computer Science" 
                                   class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Department Alignment <span class="text-rose-500">*</span></label>
                        <select name="department_id" id="majorDept" required 
                                class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm appearance-none cursor-pointer">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <button type="button" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="majorBtnSubmit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-sm">Save Major</button>
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
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Class Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" id="classCode" required placeholder="e.g. M1" 
                                   class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-sm uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Class Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="className" required placeholder="e.g. Morning Class" 
                                   class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Specialization Core <span class="text-rose-500">*</span></label>
                        <select name="major_id" id="classMajor" required 
                                class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm appearance-none cursor-pointer">
                            <option value="">-- Select Major --</option>
                            @foreach($majors_all as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <button type="button" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="classBtnSubmit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-sm">Save Class</button>
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

    // Data Purge Protocol
    function deleteSelected() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (!selected.length) return alert('INSTITUTIONAL ERROR: Zero nodes selected for purge.');

        if (confirm(`CRITICAL PROTOCOL: Authorize terminal purge of ${selected.length} academic nodes?`)) {
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
        }
    }

    // Modal Synchronization Logic
    function editRecord(id, name, code, dept, major) {
        if (currentTab === 'majors') {
            document.getElementById('majorModalTitle').innerText = 'Mutate Node';
            document.getElementById('majorForm').action = '/admin/majors/' + id;
            document.getElementById('majorFormMethod').value = 'PUT';
            document.getElementById('majorCode').value = code;
            document.getElementById('majorName').value = name;
            document.getElementById('majorDept').value = dept;
            document.getElementById('majorBtnSubmit').innerText = 'Authorize Mutation';
            new bootstrap.Modal(document.getElementById('addMajorModal')).show();
        } 
        else if (currentTab === 'classes') {
            document.getElementById('classModalTitle').innerText = 'Mutate Node';
            document.getElementById('classForm').action = '/admin/classes/' + id;
            document.getElementById('classFormMethod').value = 'PUT';
            document.getElementById('classCode').value = code;
            document.getElementById('className').value = name;
            document.getElementById('classMajor').value = major;
            document.getElementById('classBtnSubmit').innerText = 'Authorize Mutation';
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
            document.getElementById(prefix + 'ModalTitle').innerText = 'New ' + (prefix === 'major' ? 'Specialization' : 'Learning Unit');
            document.getElementById(prefix + 'BtnSubmit').innerText = 'Authorize Node';
        });
    });
</script>
@endsection
