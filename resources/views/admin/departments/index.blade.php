@extends('layouts.admin')

@section('topbar-title', 'Academic Structure')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-semibold text-slate-900 tracking-tight">Departments Directory</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Manage institutional departments and faculties.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center gap-2 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <i class="fas fa-sync-alt text-xs text-slate-400"></i> Refresh
            </button>
            <a href="{{ route('admin.departments.export') }}" class="bg-white hover:bg-blue-50 text-blue-600 border border-slate-200/80 hover:border-blue-200 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center gap-2 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <i class="fas fa-file-excel text-xs"></i> Export
            </a>
            <button data-bs-toggle="modal" data-bs-target="#addDeptModal" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center gap-2 shadow-sm">
                <i class="fas fa-plus text-xs"></i> New Department
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-3 shadow-sm">
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
                <h3 class="text-base font-semibold text-slate-900 tracking-tight">Active Departments</h3>
                <span class="px-2 py-0.5 rounded-full bg-slate-200/60 text-slate-600 text-[11px] font-bold tracking-wide">{{ $departments->total() }} items</span>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button class="bg-white hover:bg-slate-50 text-rose-600 border border-slate-200 px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-[0_1px_2px_rgba(0,0,0,0.02)]" onclick="deleteSelected()">
                    <i class="fas fa-trash-alt text-xs"></i> Bulk Delete
                </button>
                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="tableSearch" placeholder="Search by name..." 
                           class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200/70 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="deptTable">
                <thead>
                    <tr class="bg-white border-b border-slate-100/80">
                        <th class="px-5 py-4 w-12 text-center">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-20">Code</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Department</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Majors</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Classes</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Courses</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80 bg-white">
                    @forelse($departments as $dept)
                    <tr class="table-row hover:bg-slate-50/50 transition-colors group">
                        <td class="px-5 py-4 text-center">
                            <input type="checkbox" class="row-checkbox w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500 cursor-pointer" value="{{ $dept->id }}" data-name="{{ $dept->department_name }}" data-code="{{ $dept->code }}" data-description="{{ $dept->description }}">
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[13px] font-mono text-slate-500 font-medium">{{ $dept->code ?? 'DPT' . str_pad($dept->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.departments.show', $dept->id) }}" class="flex items-center gap-3 group-hover:text-blue-600 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100/50">
                                    <i class="fas fa-building text-xs"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-slate-800 group-hover:text-blue-600">{{ $dept->department_name }}</h4>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($dept->majors_count ?? 0) > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                {{ $dept->majors_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($dept->classes_count ?? 0) > 0 ? 'bg-blue-50 text-blue-700 border border-blue-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                {{ $dept->classes_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ ($dept->subjects_count ?? 0) > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                {{ $dept->subjects_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 transition-opacity opacity-70 group-hover:opacity-100">
                                <button onclick="editSingleRow(this)" data-id="{{ $dept->id }}" data-name="{{ $dept->department_name }}" data-code="{{ $dept->code }}" data-description="{{ $dept->description }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors tooltip-trigger" title="Edit">
                                    <i class="far fa-edit text-sm"></i>
                                </button>
                                <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors btn-delete" 
                                    title="Delete" 
                                    data-id="{{ $dept->id }}" 
                                    data-title="{{ $dept->department_name }}">
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
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
            <span class="text-sm text-slate-500">
                Showing <span class="font-medium text-slate-700">{{ $departments->firstItem() ?? 0 }}</span> to <span class="font-medium text-slate-700">{{ $departments->lastItem() ?? 0 }}</span> of <span class="font-medium text-slate-700">{{ $departments->total() }}</span> entries
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
        <div class="modal-content border-0 shadow-lg rounded-xl overflow-hidden">
            <div class="bg-blue-600 px-6 py-4 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-white tracking-tight flex items-center gap-2" id="modalTitle">
                    <i class="fas fa-building text-blue-200"></i> Add Department
                </h5>
                <button type="button" class="text-blue-200 hover:text-white transition-colors" data-bs-dismiss="modal" aria-label="Close">
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

        if (confirm('Are you definitely sure you want to delete the ' + selected.length + ' selected department(s)? This may affect associated majors.')) {
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
        }
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

            if (confirm('Are you certain you want to delete "' + title + '"? Associated data might be impacted.')) {
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
            }
        }
    });
</script>
@endsection
