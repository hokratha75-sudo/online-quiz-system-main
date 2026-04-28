@extends('layouts.admin')

@section('topbar-title', 'Academic Structure')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-8">
        <div>
            <h1 class="text-2xl md:text-[28px] font-bold text-slate-900 tracking-tight">Departments Directory</h1>
            <p class="text-[14px] font-medium text-slate-500 mt-1.5">Manage administrative departments and institutional faculties.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-100">
                <i class="fas fa-sync-alt text-slate-400"></i> Refresh
            </button>
            <a href="{{ route('admin.departments.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-100">
                <i class="fas fa-file-excel text-emerald-500"></i> Export
            </a>
            <button data-bs-toggle="modal" data-bs-target="#addDeptModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="fas fa-plus text-indigo-200 text-xs"></i> New Department
            </button>
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

    @if($errors->any())
    <div class="mb-6 bg-white border border-rose-100 rounded-2xl p-3.5 flex items-center justify-between shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-full bg-rose-50 flex items-center justify-center shrink-0 border border-rose-100/50">
                <i class="fas fa-exclamation-triangle text-rose-500 text-xs"></i>
            </div>
            <div>
                <h4 class="text-[13px] font-bold text-slate-900 leading-tight">Action Failed</h4>
                <p class="text-[11px] font-medium text-slate-500">{{ $errors->first() }}</p>
            </div>
        </div>
        <button type="button" class="text-slate-400 hover:text-slate-600 px-2" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white rounded-[20px] border border-slate-100/70 shadow-[0_2px_5px_rgba(0,0,0,0.02)] flex flex-col overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <h3 class="text-xs font-bold text-slate-900 tracking-widest uppercase">Active Departments</h3>
                <span class="px-2.5 py-1 rounded-md bg-white border border-slate-100 text-indigo-600 text-[10px] font-bold tracking-widest uppercase shadow-sm tabular-nums">{{ $departments->total() }} Records</span>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button class="bg-white hover:bg-rose-50 text-rose-600 border border-slate-100 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm" onclick="deleteSelected()">
                    <i class="fas fa-trash-alt text-[10px]"></i> Delete Selected
                </button>
                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-indigo-600 text-[10px]"></i>
                    <input type="text" id="tableSearch" placeholder="Search indices..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-900 uppercase tracking-widest focus:outline-none focus:border-indigo-500 transition-all shadow-sm">
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="deptTable">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200/70">
                        <th class="px-5 py-4 w-12 text-center">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 cursor-pointer transition-colors shadow-sm">
                        </th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left w-24">Code</th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left">Department Details</th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Majors</th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Classes</th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Subjects</th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($departments as $dept)
                    <tr class="table-row hover:bg-slate-50/50 transition-colors group">
                        <td class="px-5 py-4 text-center">
                            <input type="checkbox" class="row-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 cursor-pointer shadow-sm transition-colors" value="{{ $dept->id }}" data-name="{{ $dept->department_name }}" data-code="{{ $dept->code }}" data-description="{{ $dept->description }}">
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 tracking-wide">{{ $dept->code ?? 'DPT-' . str_pad($dept->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.departments.show', $dept->id) }}" class="flex items-center gap-3.5 transition-all group-hover:translate-x-1">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50/80 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100/50 shadow-sm">
                                    <i class="fas fa-building text-sm"></i>
                                </div>
                                <div class="flex flex-col">
                                    <h4 class="text-sm font-semibold text-slate-900 tracking-tight">{{ $dept->department_name }}</h4>
                                    <span class="text-xs text-slate-500 font-medium mt-0.5 truncate max-w-[250px]">{{ $dept->description ?: 'No description provided' }}</span>
                                </div>
                            </a>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($dept->majors_count ?? 0) > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                {{ $dept->majors_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($dept->classes_count ?? 0) > 0 ? 'bg-sky-50 text-sky-700 border border-sky-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                {{ $dept->classes_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($dept->subjects_count ?? 0) > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                {{ $dept->subjects_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 transition-opacity opacity-70 group-hover:opacity-100">
                                <button onclick="editSingleRow(this)" data-id="{{ $dept->id }}" data-name="{{ $dept->department_name }}" data-code="{{ $dept->code }}" data-description="{{ $dept->description }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors tooltip-trigger" title="Edit">
                                    <i class="far fa-edit text-[13px]"></i>
                                </button>
                                <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors btn-delete" 
                                    title="Delete" 
                                    data-id="{{ $dept->id }}" 
                                    data-title="{{ $dept->department_name }}">
                                    <i class="far fa-trash-alt text-[13px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="p-12 text-center flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-building text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-base font-semibold text-slate-800 tracking-tight">No Departments Found</h3>
                                <p class="text-sm text-slate-500 mt-1 max-w-sm">There are currently no departments. Click "New Department" to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        @if($departments->hasPages())
        <div class="px-8 py-5 border-t border-slate-50 bg-slate-50/30 flex items-center justify-between">
            <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest tabular-nums italic italic">
                Mapping Node: {{ $departments->firstItem() ?? 0 }} - {{ $departments->lastItem() ?? 0 }} of {{ $departments->total() }} Authorized Units
            </span>
            <div class="flex justify-end custom-pagination">
                {{ $departments->withQueryString()->links() }}
            </div>
        </div>
        @else
        <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/30 flex justify-between">
            <span class="text-sm text-slate-500">
                Displaying all <span class="font-medium text-slate-700">{{ $departments->total() }}</span> entries
            </span>
        </div>
        @endif
        
<style>
/* Clean Custom Pagination styles to fix default Tailwind pagination UI issues */
.custom-pagination nav > div:first-child { display: none; }
.custom-pagination nav > div:last-child { display: flex; justify-content: flex-end; }
.custom-pagination nav { background: transparent !important; }
.custom-pagination nav p { display: none; }
.custom-pagination nav .relative.inline-flex { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 0.5rem; }
</style>
    </div>
</div>

<!-- Add/Edit Department Modal -->
<div class="modal fade" id="addDeptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-[32px] border-0 shadow-2xl overflow-hidden">
            <div class="bg-indigo-600 px-8 py-6 flex items-center justify-between">
                <h5 class="text-xl font-bold text-white tracking-tight flex items-center gap-3" id="modalTitle">
                    <i class="fas fa-building text-indigo-200"></i> Add Department
                </h5>
                <button type="button" class="text-indigo-200 hover:text-white transition-colors" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.departments.store') }}" method="POST" id="deptForm" class="p-6">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Department Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="department_name" id="deptName" required placeholder="e.g. Faculty of Science"
                               class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Code <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <input type="text" name="code" id="deptCode" placeholder="e.g. DPT001"
                               class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <textarea name="description" id="deptDescription" rows="3" placeholder="Brief summary of the department..."
                                  class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-sm resize-none"></textarea>
                    </div>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <button type="button" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btnSubmit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-sm">Save Department</button>
                </div>
            </form>
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
        let rows = document.querySelectorAll('#deptTable tbody .table-row');
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
            alert('Please select at least one department to delete.');
            return;
        }

        window.premiumConfirm(
            '<span class="font-bold text-slate-700">You are about to delete ' + selected.length + ' department(s).</span><br>All associated majors and classes may be affected. This action cannot be undone.', 
            function() {
                let form = document.getElementById('deleteForm');
                form.action = '{{ route("admin.departments.bulkDelete") }}';
                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                selected.forEach(function(item) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = item.value;
                    form.appendChild(input);
                });
                form.submit();
            },
            'Delete Multiple Departments?'
        );
    }

    function editSingleRow(btn) {
        let id = btn.getAttribute('data-id');
        let name = btn.getAttribute('data-name');
        let code = btn.getAttribute('data-code');
        let description = btn.getAttribute('data-description');
        
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit text-blue-200"></i> Edit Department';
        document.getElementById('deptForm').action = '/admin/departments/' + id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('deptName').value = name;
        document.getElementById('deptCode').value = code !== 'null' ? code : '';
        document.getElementById('deptDescription').value = description !== 'null' ? description : '';
        document.getElementById('btnSubmit').innerText = 'Update Department';
        
        var modal = new bootstrap.Modal(document.getElementById('addDeptModal'));
        modal.show();
    }

    // Modal Reset handling
    var myModalEl = document.getElementById('addDeptModal');
    myModalEl.addEventListener('hidden.bs.modal', function (event) {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-building text-blue-200"></i> Add Department';
        document.getElementById('deptForm').action = '{{ route("admin.departments.store") }}';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('deptName').value = '';
        document.getElementById('deptCode').value = '';
        document.getElementById('deptDescription').value = '';
        document.getElementById('btnSubmit').innerText = 'Save Department';
    });

    // Single Delete Custom Event Listener
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete')) {
            const btn = e.target.closest('.btn-delete');
            const id = btn.dataset.id;
            const title = btn.dataset.title;

            window.premiumConfirm(
                'You are about to delete <strong class="text-slate-800">"' + title + '"</strong>.<br>Are you sure? This action cannot be undone.', 
                function() {
                    const form = document.getElementById('deleteForm');
                    form.action = '/admin/departments/' + id;
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
                },
                'Delete Department?'
            );
        }
    });
</script>
@endsection
