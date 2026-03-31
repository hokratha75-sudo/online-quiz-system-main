@extends('layouts.admin')

@section('styles')
<style>
    .page-wrapper {
        padding: 20px;
        background-color: #f4f6f9;
        min-height: calc(100vh - 50px);
    }
    .page-title-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .page-title {
        font-size: 32px;
        font-weight: 600;
        margin: 0;
        color: #343a40;
        display: inline-block;
    }
    .page-subtitle {
        color: #6c757d;
        font-size: 14px;
        margin-left: 8px;
        display: inline-block;
    }
    .breadcrumb-right {
        color: #6c757d;
        font-size: 14px;
    }
    .breadcrumb-right a {
        color: #007bff;
        text-decoration: none;
    }
    
    .card-custom {
        background: #fff;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        box-shadow: none;
    }
    .card-header-inner {
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
    }
    .card-title-custom {
        margin: 0 0 15px 0;
        font-size: 16px;
        font-weight: 600;
        color: #343a40;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-minus {
        color: #6c757d;
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
    }
    
    .btn-action-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        margin-bottom: 20px;
    }
    .btn-blue {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 4px;
        font-size: 14px;
    }
    .btn-pink {
        background-color: #e83e8c;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 4px;
        font-size: 14px;
    }
    .btn-yellow {
        background-color: #ffc107;
        color: #000;
        border: none;
        padding: 6px 14px;
        border-radius: 4px;
        font-size: 14px;
    }
    .btn-red {
        background-color: #dc3545;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .toolbar-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .show-entries {
        color: #6c757d;
        font-size: 14px;
    }
    .show-entries select {
        border: 1px solid #ced4da;
        padding: 4px;
        margin: 0 5px;
        border-radius: 4px;
    }
    .middle-buttons .btn {
        background: #f8f9fa;
        border: 1px solid #ced4da;
        color: #6c757d;
        padding: 4px 12px;
        font-size: 14px;
        margin: 0 2px;
    }
    .search-box {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #6c757d;
    }
    .search-box input {
        border: 1px solid #ced4da;
        padding: 4px 10px;
        margin-left: 8px;
        border-radius: 4px;
        outline: none;
    }
    
    .table-custom {
        width: 100%;
        margin-bottom: 0;
    }
    .table-custom th {
        border-top: none;
        border-bottom: 2px solid #dee2e6;
        padding: 12px 20px;
        color: #212529;
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
    }
    .table-custom td {
        padding: 12px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
        color: #212529;
        font-size: 14px;
    }
    
    .count-badge {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-blue { background-color: #0d6efd; }
    .badge-green { background-color: #198754; }
    .badge-cyan { background-color: #0dcaf0; }
    
    .card-footer-custom {
        padding: 15px 20px;
        background: #fff;
        border-top: none;
        color: #6c757d;
        font-size: 14px;
    }
    
    .form-check-input {
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-title-box">
        <div>
            <h2 class="page-title">Subjects</h2>
            <span class="page-subtitle">Data Subjects</span>
        </div>
        <div class="breadcrumb-right">
            <i class="fas fa-tachometer-alt text-primary"></i> <a href="{{ route('admin.dashboard') }}">Dashboard</a> / Subjects / Data Subjects
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card-custom">
        <div class="card-header-inner">
            <h3 class="card-title-custom">
                Master Data Subjects
                <button class="btn-minus"><i class="fas fa-minus"></i></button>
            </h3>
            
            <div class="btn-action-group">
                <div>
                    <button class="btn-blue me-1" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="fas fa-plus"></i> Add Data
                    </button>
                    <button class="btn-pink" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i> Reload
                    </button>
                </div>
                <div>
                    <button class="btn-yellow me-1" onclick="editSelected()">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn-red" onclick="deleteSelected()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            
            <div class="toolbar-group">
                <div class="show-entries">
                    Show 
                    <select>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select> 
                    entries
                </div>
                <div class="middle-buttons">
                    <button class="btn">Copy</button>
                    <a href="{{ route('admin.subjects.export') }}" class="btn">Excel</a>
                </div>
                <div class="search-box">
                    Search: <input type="text" id="tableSearch" placeholder="Search...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-custom" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Subject Name</th>
                        <th>Department</th>
                        <th class="text-center">Credits</th>
                        <th class="text-center">Classes</th>
                        <th class="text-center">Quizzes</th>
                        <th class="text-end"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $item)
                    <tr class="table-row">
                        <td>{{ $loop->iteration + ($subjects->currentPage() - 1) * $subjects->perPage() }}</td>
                        <td>{{ $item->code ?? 'SUB' . str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <a href="{{ route('admin.subjects.show', $item->id) }}" style="text-decoration:none; color:#007bff;">
                                <i class="fas fa-bookmark me-1"></i> {{ $item->subject_name }}
                            </a>
                        </td>
                        <td>{{ $item->major ? $item->major->name : 'N/A' }}</td>
                        <td>{{ $item->credits ?? 3 }}</td>
                        <td class="text-center">
                            <span class="count-badge badge-green">{{ $item->classes_count ?? 0 }}</span>
                        </td>
                        <td class="text-center">
                            <span class="count-badge badge-black">{{ $item->quizzes_count ?? 0 }}</span>
                        </td>
                        <td class="text-end">
                            <input type="checkbox" class="form-check-input row-checkbox" value="{{ $item->id }}" data-name="{{ $item->subject_name }}" data-department="{{ $item->department_id }}" data-major="{{ $item->major_id }}" data-classes="{{ json_encode($item->classes->pluck('id')->toArray()) }}">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No subjects found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer-custom">
            Showing {{ $subjects->firstItem() ?? 0 }} to {{ $subjects->lastItem() ?? 0 }} of {{ $subjects->total() ?? 0 }} entries
        </div>
    </div>
</div>

<!-- Add/Edit Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.subjects.store') }}" method="POST" id="mainForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" class="form-control" id="itemCode" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject Name</label>
                        <input type="text" name="subject_name" class="form-control" id="itemName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select" id="itemDept" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Major (Optional)</label>
                        <select name="major_id" class="form-select" id="itemMajor">
                            <option value="">Select Major</option>
                            @foreach($majors as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Classes (Optional)</label>
                        <select name="class_ids[]" class="form-select" id="itemClasses" multiple>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple classes.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Save</button>
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
    // Search functionality
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
            alert('Please select a row to delete.');
            return;
        }

        if (confirm('Are you sure you want to delete the ' + selected.length + ' selected subject(s)?')) {
            let form = document.getElementById('deleteForm');
            form.action = '{{ route("admin.subjects.bulkDelete") }}';
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

    // Bulk/Single Edit
    function editSelected() {
        let selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select a row to edit.');
            return;
        }
        
        if (selected.length > 1) {
            alert('Please select only one row to edit.');
            return;
        }

        let cb = selected[0];
        let id = cb.value;
        let name = cb.getAttribute('data-name');
        let dept = cb.getAttribute('data-department');
        let major = cb.getAttribute('data-major');
        let classesStr = cb.getAttribute('data-classes');
        let classArray = classesStr ? JSON.parse(classesStr) : [];
        let row = cb.closest('tr');
        let code = row.cells[1].innerText;
        
        document.getElementById('modalTitle').innerText = 'Edit Subject';
        document.getElementById('mainForm').action = '/admin/subjects/' + id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('itemCode').value = code;
        document.getElementById('itemName').value = name;
        document.getElementById('itemDept').value = dept;
        document.getElementById('itemMajor').value = major || '';
        
        let classSelect = document.getElementById('itemClasses');
        Array.from(classSelect.options).forEach(opt => {
            opt.selected = classArray.includes(parseInt(opt.value)) || classArray.includes(String(opt.value));
        });

        document.getElementById('btnSubmit').innerText = 'Update';
        
        var modal = new bootstrap.Modal(document.getElementById('addSubjectModal'));
        modal.show();
    }
    
    // Reset modal on close
    document.getElementById('addSubjectModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modalTitle').innerText = 'Add Subject';
        document.getElementById('mainForm').action = '{{ route("admin.subjects.store") }}';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('itemCode').value = '';
        document.getElementById('itemName').value = '';
        document.getElementById('itemDept').value = '';
        document.getElementById('itemMajor').value = '';
        Array.from(document.getElementById('itemClasses').options).forEach(opt => opt.selected = false);
        document.getElementById('btnSubmit').innerText = 'Save';
    });
</script>
@endsection
