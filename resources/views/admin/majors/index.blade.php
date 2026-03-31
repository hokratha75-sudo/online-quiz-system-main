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
    .item-name {
        color: #007bff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
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
    .badge-yellow { background-color: #ffc107; color: #000; }
    .badge-black { background-color: #212529; color: #fff; }
    
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
            <h2 class="page-title">{{ ucfirst($tab) }}</h2>
            <span class="page-subtitle">Data {{ ucfirst($tab) }}</span>
        </div>
        <div class="breadcrumb-right">
            <i class="fas fa-tachometer-alt text-primary"></i> <a href="{{ route('admin.dashboard') }}">Dashboard</a> / {{ ucfirst($tab) }} / Data {{ ucfirst($tab) }}
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
                Master Data {{ ucfirst($tab) }}
                <button class="btn-minus"><i class="fas fa-minus"></i></button>
            </h3>
            
            <div class="btn-action-group">
                <div>
                    @if($tab == 'majors')
                        <button class="btn-blue me-1" data-bs-toggle="modal" data-bs-target="#addMajorModal"><i class="fas fa-plus"></i> Add Data</button>
                    @elseif($tab == 'classes')
                        <button class="btn-blue me-1" data-bs-toggle="modal" data-bs-target="#addClassModal"><i class="fas fa-plus"></i> Add Data</button>
                    @elseif($tab == 'subjects')
                        <button class="btn-blue me-1" data-bs-toggle="modal" data-bs-target="#addSubjectModal"><i class="fas fa-plus"></i> Add Data</button>
                    @elseif($tab == 'departments')
                        <button class="btn-blue me-1" data-bs-toggle="modal" data-bs-target="#addDeptModal"><i class="fas fa-plus"></i> Add Data</button>
                    @endif
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
                    <a href="{{ route('admin.majors.export', ['tab' => $tab]) }}" class="btn">Excel</a>
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
                        @if($tab == 'majors')
                            <th>Major</th>
                            <th>Department</th>
                            <th class="text-center">Total Classes</th>
                            <th class="text-center">Total Courses</th>
                        @elseif($tab == 'classes')
                            <th>Class</th>
                            <th>Major</th>
                            <th>Year</th>
                            <th class="text-center">Total Students</th>
                            <th class="text-center">Total Courses</th>
                        @elseif($tab == 'subjects')
                            <th>Course</th>
                            <th>Major</th>
                            <th>Credits</th>
                            <th class="text-center">Total Classes</th>
                            <th class="text-center">Total Quizzes</th>
                        @elseif($tab == 'departments')
                            <th>Department</th>
                            <th class="text-center">Total Majors</th>
                            <th class="text-center">Total Classes</th>
                            <th class="text-center">Total Courses</th>
                        @endif
                        <th class="text-end"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr class="table-row">
                        <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                        <td>{{ $item->code }}</td>
                        
                        @if($tab == 'majors')
                            <td>
                                <a href="{{ route('admin.majors.show', $item->id) }}" class="item-name">
                                    <i class="fas fa-bookmark me-1"></i> {{ $item->name }}
                                </a>
                            </td>
                            <td>{{ $item->department->department_name ?? 'N/A' }}</td>
                            <td class="text-center">
                                <span class="count-badge badge-green">{{ $item->classes_count ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="count-badge badge-cyan">{{ $item->subjects_count ?? 0 }}</span>
                            </td>
                        @elseif($tab == 'classes')
                            <td>
                                <a href="{{ route('admin.classes.show', $item->id) }}" class="item-name">
                                    <i class="fas fa-bookmark me-1"></i> {{ $item->name }}
                                </a>
                            </td>
                            <td>{{ $item->major->name ?? 'N/A' }}</td>
                            <td>{{ $item->academic_year ?? 'N/A' }}</td>
                            <td class="text-center">
                                <span class="count-badge badge-yellow">{{ $item->students_count ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="count-badge badge-cyan">{{ $item->subjects_count ?? 0 }}</span>
                            </td>
                        @elseif($tab == 'subjects')
                            <td>
                                <a href="{{ route('admin.subjects.show', $item->id) }}" class="item-name">
                                    {{ $item->subject_name }}
                                </a>
                            </td>
                            <td>{{ $item->major->name ?? 'N/A' }}</td>
                            <td>{{ $item->credits ?? 3 }}</td>
                            <td class="text-center">
                                <span class="count-badge badge-green">{{ $item->classes_count ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="count-badge badge-black">{{ $item->quizzes_count ?? 0 }}</span>
                            </td>
                        @elseif($tab == 'departments')
                            <td>
                                <a href="{{ route('admin.departments.show', $item->id) }}" class="item-name">
                                    <i class="fas fa-bookmark me-1"></i> {{ $item->department_name }}
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="count-badge badge-blue">{{ $item->majors_count ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="count-badge badge-green">{{ $item->classes_count ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="count-badge badge-cyan">{{ $item->subjects_count ?? 0 }}</span>
                            </td>
                        @endif
                        
                        <td class="text-end">
                            <input type="checkbox" class="form-check-input row-checkbox" 
                                   value="{{ $item->id }}" 
                                   data-name="{{ $item->name ?? $item->department_name ?? $item->subject_name }}"
                                   data-dept="{{ $item->department_id ?? '' }}"
                                   data-major="{{ $item->major_id ?? '' }}">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No data found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer-custom">
            Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() ?? 0 }} entries
        </div>
    </div>
</div>

<!-- Add/Edit Major Modal -->
<div class="modal fade" id="addMajorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="majorModalTitle">Add Major</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.majors.store') }}" method="POST" id="majorForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="majorFormMethod">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" class="form-control" id="majorCode" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Major Name</label>
                        <input type="text" name="name" class="form-control" id="majorName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select" id="majorDept" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="majorBtnSubmit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add/Edit Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="classModalTitle">Add Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.classes.store') }}" method="POST" id="classForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="classFormMethod">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" class="form-control" id="classCode" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class Name</label>
                        <input type="text" name="name" class="form-control" id="className" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Major</label>
                        <select name="major_id" class="form-select" id="classMajor" required>
                            <option value="">Select Major</option>
                            @foreach($majors_all as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="classBtnSubmit">Save</button>
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
    let currentTab = '{{ $tab }}';

    // Reset modals
    var majorModal = document.getElementById('addMajorModal');
    if(majorModal) {
        majorModal.addEventListener('show.bs.modal', function (event) {
            if(event.relatedTarget) { // Clicked from Add Data button
                document.getElementById('majorForm').reset();
                document.getElementById('majorForm').action = '/admin/majors';
                document.getElementById('majorFormMethod').value = 'POST';
                document.getElementById('majorModalTitle').innerText = 'Add Major';
                document.getElementById('majorBtnSubmit').innerText = 'Save';
            }
        });
    }

    var classModal = document.getElementById('addClassModal');
    if(classModal) {
        classModal.addEventListener('show.bs.modal', function (event) {
            if(event.relatedTarget) {
                document.getElementById('classForm').reset();
                document.getElementById('classForm').action = '/admin/classes';
                document.getElementById('classFormMethod').value = 'POST';
                document.getElementById('classModalTitle').innerText = 'Add Class';
                document.getElementById('classBtnSubmit').innerText = 'Save';
            }
        });
    }

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

        if (confirm('Are you sure you want to delete the ' + selected.length + ' selected item(s)?')) {
            let form = document.getElementById('deleteForm');
            if (currentTab === 'majors') form.action = '{{ route("admin.majors.bulkDelete") }}';
            if (currentTab === 'classes') form.action = '{{ route("admin.classes.bulkDelete") }}';
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

    // Edit
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
        
        if (currentTab === 'majors') {
            let row = cb.closest('tr');
            let code = row.cells[1].innerText;
            let dept = cb.getAttribute('data-dept');
            
            document.getElementById('majorModalTitle').innerText = 'Edit Major';
            document.getElementById('majorForm').action = '/admin/majors/' + id;
            document.getElementById('majorFormMethod').value = 'PUT';
            document.getElementById('majorCode').value = code;
            document.getElementById('majorName').value = name;
            document.getElementById('majorDept').value = dept;
            document.getElementById('majorBtnSubmit').innerText = 'Update Update';
            
            var modal = new bootstrap.Modal(document.getElementById('addMajorModal'));
            modal.show();
        } 
        else if (currentTab === 'classes') {
            let row = cb.closest('tr');
            let code = row.cells[1].innerText;
            let major = cb.getAttribute('data-major');
            
            document.getElementById('classModalTitle').innerText = 'Edit Class';
            document.getElementById('classForm').action = '/admin/classes/' + id;
            document.getElementById('classFormMethod').value = 'PUT';
            document.getElementById('classCode').value = code;
            document.getElementById('className').value = name;
            document.getElementById('classMajor').value = major;
            document.getElementById('classBtnSubmit').innerText = 'Update Update';
            
            var modal = new bootstrap.Modal(document.getElementById('addClassModal'));
            modal.show();
        }
    }
</script>
@endsection
