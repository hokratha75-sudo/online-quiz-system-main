@extends('layouts.admin')

@section('topbar-title', 'Departments Management')

@section('content')
@if(session('success'))
    <div class="fixed top-4 right-4 z-[9999] success-alert w-[360px] bg-white border border-emerald-100 rounded-2xl p-3.5 flex items-center justify-between shadow-xl overflow-hidden transition-all duration-300">
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
        <button type="button" class="group relative w-10 h-10 rounded-full bg-green-100 border border-green-600 border border-green-500 text-green-600 hover:bg-green-200 focus:outline-none" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-sm "></i>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="fixed top-4 right-4 z-[9999] error-alert w-[360px] bg-white border border-rose-100 rounded-2xl p-3.5 flex items-center justify-between shadow-xl overflow-hidden transition-all duration-300">
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
        <button type="button" class="group relative w-10 h-10 rounded-full bg-red-100 border border-red-500 text-red-600 hover:bg-red-200  focus:outline-none " onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
    @endif
<div class="max-w-[1600px] py-4 px-5 md:p-8 font-sans text-slate-800 bg-gradient-to-br from-slate-50 via-white to-slate-100/50 min-h-[calc(100vh-530px)]">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight leading-none">Departments Management</h1>
            <p class="text-sm font-medium text-slate-400 mt-2">Manage administrative departments and institutional faculties.</p>
        </div>
        <div class="flex items-center justify-end gap-3">
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-sync-alt text-slate-400"></i> Refresh
            </button>
            <a href="{{ route('admin.departments.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm no-underline">
                <i class="fas fa-file-excel text-emerald-500"></i> Export
            </a>
            <button data-bs-toggle="modal" data-bs-target="#addDeptModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg shadow-indigo-600/20 active:scale-[0.98] border-none">
                <i class="fas fa-plus text-white/80"></i> New Department
            </button>
        </div>
    </div>

    

    <!-- Data Table Card -->
    <div class="bg-white rounded-[20px] border border-slate-100/70 shadow-[0_2px_5px_rgba(0,0,0,0.02)] flex flex-col overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <h3 class="text-xs font-bold text-slate-900 tracking-widest uppercase">Active Departments</h3>
                <span class="px-2.5 py-1 rounded-full bg-white border border-slate-100 text-indigo-600 text-[10px] font-bold tracking-widest uppercase shadow-sm tabular-nums">{{ $departments->total() }} Records</span>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button class="bg-white hover:bg-rose-200 text-rose-600 border border-slate-100 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm" onclick="deleteSelected()">
                    <i class="fas fa-trash-alt text-[10px]"></i> Delete Selected
                </button>
                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-indigo-600 text-[10px]"></i>
                    <input type="text" id="tableSearch" placeholder="Search indices..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium placeholder:text-slate-300 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-hidden">
            <div class="h-[calc(100vh-400px)]  overflow-y-auto custom-scrollbar">
            <table class="w-full text-left border-collapse" id="deptTable">
                <thead>
                    <tr class="bg-slate-100 sticky top-0 z-10 shadow-sm">
                        <th scope="col" class="p-3 w-12 text-center">#</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider pl-3 w-40">Code</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider pl-3">Department Details</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Majors</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Classes</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Subjects</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center sticky z-10">
                            Actions
                        </th>
                        <th class="px-4 py-3 w-12 text-right ">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 cursor-pointer transition-colors shadow-sm">
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white" id="tableBody">
                    @forelse($departments as $dept)
                    <tr class="table-row hover:bg-slate-50/50 transition-colors group">
                        <td class="p-4 text-center">
                            {{ $loop->iteration + ($departments->currentPage() - 1) * $departments->perPage() }}
                        </td>
                        <td class="px-2 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 tracking-wide">{{ $dept->code ?? 'DPT-' . str_pad($dept->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-2 py-4">
                            <a href="{{ route('admin.departments.show', $dept->id) }}" class="flex items-center gap-3.5 transition-all group-hover:translate-x-1 no-underline">
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
                            <div class="flex items-center justify-end gap-1.5 transition-opacity opacity-100 group-hover:opacity-100">
                                <button onclick="editSingleRow(this)" data-id="{{ $dept->id }}" data-name="{{ $dept->department_name }}" data-code="{{ $dept->code }}" data-description="{{ $dept->description }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 transition-colors tooltip-trigger border-none" title="Edit">
                                    <i class="far fa-edit text-[13px]"></i>
                                </button>
                                <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center text-white bg-rose-700 hover:bg-rose-700 transition-colors btn-delete border-none" 
                                title="Delete" 
                                data-id="{{ $dept->id }}" 
                                data-title="{{ $dept->department_name }}">
                                <i class="far fa-trash-alt text-[13px]"></i>
                            </button>
                        </div>
                    </td>
                    <td class="p-4 text-right">
                        <input type="checkbox" class="row-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 cursor-pointer shadow-sm transition-colors" value="{{ $dept->id }}">
                    </td>
                    </tr>
                    @empty
                    <tr id="emptyStateRow">
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

                    <!-- Hidden row shown only when search returns zero results -->
                    <tr id="noSearchResultsRow" style="display: none;">
                        <td colspan="7">
                            <div class="p-12 text-center flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-search text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-base font-semibold text-slate-800 tracking-tight">No Matching Departments</h3>
                                <p class="text-sm text-slate-500 mt-1 max-w-sm">Try a different keyword or clear your search.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        <!-- Footer -->
        @if($departments->hasPages())
        <div class="px-4 py-3    border-t border-slate-50 bg-slate-100/80 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest tabular-nums">
                Mapping Node: {{ $departments->firstItem() ?? 0 }} - {{ $departments->lastItem() ?? 0 }} of {{ $departments->total() }} Authorized Units
            </span>
            <div class="custom-pagination flex items-center">
                {{ $departments->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @else
        <div class="p-4 border-t border-slate-50 bg-slate-100/80 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest tabular-nums ">
                Displaying all <span class="font-medium text-slate-700">{{ $departments->total() }}</span> entries
            </span>
        </div>
        @endif
        
<style>
/* layout fix */
.custom-pagination nav {
    background: transparent !important;
}

.custom-pagination nav > div:first-child {
    display: none;
}

.custom-pagination nav > div:last-child {
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

/* remove text */
.custom-pagination nav p {
    display: none;
}

/* reset pagination */
.custom-pagination .pagination {
    margin: 0 !important;
}

/* base button */
.custom-pagination .page-link {
    padding: 7px 10px !important;
    font-size: 15px;
    line-height: 1.2;

    border: 1px solid #e5e7eb !important;

    box-shadow: none !important;
    outline: none !important;
}

/* hover */
.custom-pagination .page-link:hover {
    background: #eef2ff;
}

/* active */
.custom-pagination .page-item.active .page-link {
    background: #4f46e5;
    color: white;
    border: 1px solid #4f46e5 !important;
    box-shadow: none !important;
}

/* click/focus fix (IMPORTANT) */
.custom-pagination .page-link:focus,
.custom-pagination .page-link:focus-visible {
    outline: none !important;
    box-shadow: none !important;
}

/* remove bootstrap weird inline wrapper shadow */
.custom-pagination nav .relative.inline-flex {
    box-shadow: none !important;
}
</style>    
<style>
    
    /* Custom Scrollbar for sleek aesthetic */
    .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4f46e5; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
</style>
    </div>
</div>

<!-- Add/Edit Department Modal -->
<div class="modal fade" id="addDeptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-[#5f60ef] to-[#9a4ce7] px-6 py-4 flex items-center justify-between">
                <h5 class="text-xl font-bold text-white tracking-tight flex items-center gap-3" id="modalTitle">
                    <i class="fas fa-building text-white text-sm"></i>
                    <span class="text-lg">Add Department</span>
                </h5>
                <button type="button" class="group relative w-10 h-10 rounded-full bg-blue-100 border-none text-red-600 hover:bg-blue-200 focus:outline-none" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form action="{{ route('admin.departments.store') }}" method="POST" id="deptForm" class="divide-y divide-slate-100">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                
                <div class="space-y-5 p-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Department Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="department_name" id="deptName" required placeholder="e.g. Faculty of Science"
                               class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Code <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <input type="text" name="code" id="deptCode" placeholder="e.g. DPT001"
                               class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none uppercase">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <textarea name="description" id="deptDescription" rows="3" placeholder="Brief summary of the department..."
                                  class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none"></textarea>
                    </div>
                </div>
                
                <div class=" flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-100 p-6">
                    <button type="button" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btnSubmit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-sm border-none">Save Department</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    document.addEventListener("DOMContentLoaded", function () {

    // SUCCESS ALERT
    const successAlert = document.querySelector(".success-alert");

    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.add("opacity-0", "translate-x-5");

            setTimeout(() => {
                successAlert.remove();
            }, 300);

        }, 5000);
    }

    // ERROR ALERT
    const errorAlert = document.querySelector(".error-alert");

    if (errorAlert) {
        setTimeout(() => {
            errorAlert.classList.add("opacity-0", "translate-x-5");

            setTimeout(() => {
                errorAlert.remove();
            }, 300);

        }, 5000);
    }

});
    // Live Search functionality with "no results" handling
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let query = this.value.toLowerCase().trim();
        let rows = document.querySelectorAll('#tableBody .table-row');
        let visibleCount = 0;

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        let noResultsRow = document.getElementById('noSearchResultsRow');
        let emptyStateRow = document.getElementById('emptyStateRow');

        // If there are no department rows at all (initial empty state)
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

    // Select all – only visible rows
    document.getElementById('selectAll').addEventListener('change', function() {
        let checked = this.checked;
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            let row = cb.closest('.table-row');
            if (row && row.style.display !== 'none') {
                cb.checked = checked;
            }
        });
    });

    // Bulk Delete
function deleteSelected() {

    let selected = document.querySelectorAll('.row-checkbox:checked');

    if (selected.length === 0) {
        alert('Please select at least one department.');
        return;
    }

    let confirmDelete = confirm(
        `Are you sure you want to delete ${selected.length} department(s)?`
    );

    if (confirmDelete) {

        let form = document.getElementById('deleteForm');

        form.action = '{{ route("admin.departments.bulkDelete") }}';

        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        `;

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

        const confirmDelete = confirm(
            `Are you sure you want to delete "${title}" ?`
        );

        if (confirmDelete) {

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