<!-- Modals for Academic Structure CRUD -->

<!-- ADD DEPARTMENT -->
<div class="modal fade" id="modalAddDepartments" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.departments.store') }}" method="POST" class="modal-content border-0 shadow rounded-4">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Add New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Code</label>
                    <input type="text" name="code" class="form-control rounded-3" required placeholder="e.g. DEPT-CS">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Name</label>
                    <input type="text" name="name" class="form-control rounded-3" required placeholder="e.g. Computer Science">
                </div>
                <div class="mb-0">
                    <label class="small fw-bold mb-1">Description</label>
                    <textarea name="description" class="form-control rounded-3" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Create Department</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT DEPARTMENT -->
<div class="modal fade" id="modalEditDepartments" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditDepartment" method="POST" data-base-url="{{ url('admin/departments') }}" class="modal-content border-0 shadow rounded-4">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Code</label>
                    <input type="text" name="code" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Name</label>
                    <input type="text" name="name" class="form-control rounded-3" required>
                </div>
                <div class="mb-0">
                    <label class="small fw-bold mb-1">Description</label>
                    <textarea name="description" class="form-control rounded-3" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Update Department</button>
            </div>
        </form>
    </div>
</div>

<!-- ADD MAJOR -->
<div class="modal fade" id="modalAddMajors" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.majors.store') }}" method="POST" class="modal-content border-0 shadow rounded-4">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Add New Major</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Department</label>
                    <select name="department_id" class="form-select rounded-3" required>
                        <option value="">Select Department...</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Code</label>
                    <input type="text" name="code" class="form-control rounded-3" required placeholder="e.g. CS-SE">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Name</label>
                    <input type="text" name="name" class="form-control rounded-3" required placeholder="e.g. Software Engineering">
                </div>
                <div class="mb-0">
                    <label class="small fw-bold mb-1">Description</label>
                    <textarea name="description" class="form-control rounded-3" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Create Major</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MAJOR -->
<div class="modal fade" id="modalEditMajors" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditMajor" method="POST" data-base-url="{{ url('admin/majors') }}" class="modal-content border-0 shadow rounded-4">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit Major</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Department</label>
                    <select name="department_id" class="form-select rounded-3" required>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Code</label>
                    <input type="text" name="code" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Name</label>
                    <input type="text" name="name" class="form-control rounded-3" required>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Update Major</button>
            </div>
        </form>
    </div>
</div>

<!-- ADD CLASS -->
<div class="modal fade" id="modalAddClasses" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.classes.store') }}" method="POST" class="modal-content border-0 shadow rounded-4">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Add New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Major</label>
                    <select name="major_id" class="form-select rounded-3" required>
                        <option value="">Select Major...</option>
                        @foreach($majors_all as $major)
                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Code</label>
                    <input type="text" name="code" class="form-control rounded-3" required placeholder="e.g. CS2024-A">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Class Name</label>
                    <input type="text" name="name" class="form-control rounded-3" required placeholder="e.g. Class A">
                </div>
                <div class="mb-0">
                    <label class="small fw-bold mb-1">Academic Year</label>
                    <input type="text" name="academic_year" class="form-control rounded-3" placeholder="e.g. 2024/2025">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Create Class</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT CLASS -->
<div class="modal fade" id="modalEditClasses" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditClass" method="POST" data-base-url="{{ url('admin/classes') }}" class="modal-content border-0 shadow rounded-4">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Major</label>
                    <select name="major_id" class="form-select rounded-3" required>
                        @foreach($majors_all as $major)
                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Code</label>
                    <input type="text" name="code" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Class Name</label>
                    <input type="text" name="name" class="form-control rounded-3" required>
                </div>
                <div class="mb-0">
                    <label class="small fw-bold mb-1">Academic Year</label>
                    <input type="text" name="academic_year" class="form-control rounded-3">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Update Class</button>
            </div>
        </form>
    </div>
</div>

<!-- ADD SUBJECT -->
<div class="modal fade" id="modalAddSubjects" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.subjects.store') }}" method="POST" class="modal-content border-0 shadow rounded-4">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Add New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Major</label>
                    <select name="major_id" class="form-select rounded-3" required>
                        <option value="">Select Major...</option>
                        @foreach($majors_all as $major)
                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Code</label>
                    <input type="text" name="code" class="form-control rounded-3" required placeholder="e.g. SUBJ-101">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Subject Name</label>
                    <input type="text" name="name" class="form-control rounded-3" required placeholder="e.g. Data Structures">
                </div>
                <div class="mb-0">
                    <label class="small fw-bold mb-1">Credits</label>
                    <input type="number" name="credits" class="form-control rounded-3" required min="1" max="10" value="3">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Create Subject</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT SUBJECT -->
<div class="modal fade" id="modalEditSubjects" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditSubject" method="POST" data-base-url="{{ url('admin/subjects') }}" class="modal-content border-0 shadow rounded-4">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Major</label>
                    <select name="major_id" class="form-select rounded-3" required>
                        @foreach($majors_all as $major)
                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Code</label>
                    <input type="text" name="code" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Subject Name</label>
                    <input type="text" name="name" class="form-control rounded-3" required>
                </div>
                <div class="mb-0">
                    <label class="small fw-bold mb-1">Credits</label>
                    <input type="number" name="credits" class="form-control rounded-3" required min="1" max="10">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Update Subject</button>
            </div>
        </form>
    </div>
</div>
