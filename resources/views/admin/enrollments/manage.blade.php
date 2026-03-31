@extends('layouts.admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="bg-white border-bottom py-5 px-4 mb-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-dark display-6 mb-0">{{ $department->department_name }}</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-dark px-4 py-2 fw-bold shadow-sm rounded-3">
                    Back to List
                </a>
                <button type="button" class="btn btn-dark fw-bold px-4 py-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#enrolUsersModal">
                    Enrolment Controller
                </button>
            </div>
        </div>

        <!-- Functional Navigation Tabs (Only showing active/relevant ones) -->
        <ul class="nav nav-tabs border-bottom-0 custom-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#"><i class="fas fa-users me-2"></i>Participants</a>
            </li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="bg-light bg-opacity-50 p-4">
        <!-- Unified Search & Stats Bar -->
        <div class="d-flex justify-content-between align-items-end mb-4 bg-white p-4 rounded-4 shadow-sm">
            <div class="flex-grow-1 me-4">
                <label class="form-label text-muted small fw-bold text-uppercase">Search Participants</label>
                <div class="input-group input-group-lg border rounded-pill overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0 ps-4"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 ps-2" id="participantSearch" placeholder="Type name, email or username to filter...">
                </div>
            </div>
            <div class="text-end">
                <div class="h2 fw-bold mb-0 text-primary" id="participantCountDisplay">{{ $participantCount }}</div>
                <div class="text-muted small fw-bold text-uppercase">Participants found</div>
            </div>
        </div>

        <!-- Participants Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden border-top border-4 border-primary">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-participants-table">
                    <thead class="bg-light">
                        <tr class="text-muted small border-bottom-0">
                            <th class="ps-4 py-3" style="width: 40px;">
                                <div class="form-check m-0">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th class="ps-0 py-3">First name / Last name</th>
                            <th class="py-3">Username</th>
                            <th class="py-3">Email address</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Last access</th>
                            <th class="pe-4 text-end py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody id="participantsTableBody">
                        @forelse($enrolledUsers as $user)
                        <tr class="participant-row">
                            <td class="ps-4">
                                <div class="form-check m-0">
                                    <input class="form-check-input" type="checkbox">
                                </div>
                            </td>
                            <td class="ps-0 searchable-cell">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 38px; height: 38px;">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="rounded-circle w-100 h-100 object-fit-cover" alt="">
                                        @else
                                            {{ strtoupper(substr($user->username, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="fw-bold text-dark">{{ $user->username }}</div>
                                </div>
                            </td>
                            <td class="searchable-cell">{{ strtolower($user->username) }}</td>
                            <td class="searchable-cell text-muted">{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ (int)$user->role_id === 2 ? 'bg-warning' : 'bg-info' }} bg-opacity-10 {{ (int)$user->role_id === 2 ? 'text-warning' : 'text-info' }} fw-bold px-3 py-1 rounded-pill small">
                                    {{ $user->role->role_name ?? 'Student' }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                            <td class="pe-4 text-end">
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1 rounded-pill small">Active</span>
                            </td>
                        </tr>
                        @empty
                        <tr id="noResultsRow">
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-users-slash fs-1 text-muted opacity-25 mb-3"></i>
                                <p class="text-muted">No participants found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Enrol Users Modal -->
<div class="modal fade" id="enrolUsersModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg overflow-hidden rounded-4" style="max-height: 90vh;">
            <div class="modal-header bg-dark text-white border-0 px-4 py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-users-cog me-2"></i>Assignment Hub: {{ $department->department_name }}</h5>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 bg-light">
                <form action="{{ route('admin.enrollments.update', $department->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- People Column -->
                        <div class="col-lg-8">
                            <div class="card h-100 border-0 shadow-sm rounded-4">
                                <div class="card-header bg-white border-0 pt-3 px-3 pb-0">
                                    <h6 class="fw-bold mb-2 small text-uppercase text-primary"><i class="fas fa-user-plus me-2"></i>Assign Teachers & Students</h6>
                                    <div class="input-group input-group-sm mb-2 border rounded-pill overflow-hidden bg-white shadow-none">
                                        <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted small"></i></span>
                                        <input type="text" class="form-control border-0 bg-transparent" id="userSearchInModal" placeholder="Find people...">
                                    </div>
                                </div>
                                <div class="card-body px-3 pb-3 pt-0">
                                    <div class="list-group list-group-flush border rounded-3 overflow-auto" style="height: 350px;" id="modalUsersList">
                                        <!-- Teachers Section -->
                                        <div class="list-group-item bg-light extra-small fw-bold text-muted py-2 text-uppercase letter-spacing-1">Instructors</div>
                                        @foreach($teachers as $teacher)
                                        <div class="list-group-item list-group-item-action d-flex align-items-center py-3 modal-user-item transition-all" data-name="{{ $teacher->username }}">
                                            <div class="form-check m-0 me-3">
                                                <input class="form-check-input" type="checkbox" name="teachers[]" value="{{ $teacher->id }}" {{ in_array($teacher->id, $assignedTeachers) ? 'checked' : '' }}>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold small">{{ $teacher->username }}</div>
                                                <div class="extra-small text-muted">{{ $teacher->email }}</div>
                                            </div>
                                            <span class="badge bg-warning bg-opacity-10 text-warning extra-small">Teacher</span>
                                        </div>
                                        @endforeach

                                        <!-- Student Section -->
                                        <div class="list-group-item bg-light extra-small fw-bold text-muted py-2 text-uppercase letter-spacing-1">Student Body</div>
                                        @foreach($students as $student)
                                        <div class="list-group-item list-group-item-action d-flex align-items-center py-3 modal-user-item transition-all" data-name="{{ $student->username }}">
                                            <div class="form-check m-0 me-3">
                                                <input class="form-check-input" type="checkbox" name="students[]" value="{{ $student->id }}" {{ in_array($student->id, $enrolledStudents) ? 'checked' : '' }}>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold small">{{ $student->username }}</div>
                                                <div class="extra-small text-muted">{{ $student->email }}</div>
                                            </div>
                                            <span class="badge bg-info bg-opacity-10 text-info extra-small">Student</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subjects Column -->
                        <div class="col-lg-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4">
                                <div class="card-header bg-white border-0 pt-3 px-3 pb-0">
                                    <h6 class="fw-bold mb-2 small text-uppercase text-success"><i class="fas fa-book me-2"></i>Attached Subjects</h6>
                                    <div class="input-group input-group-sm mb-2 border rounded-pill overflow-hidden bg-white shadow-none">
                                        <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted small"></i></span>
                                        <input type="text" class="form-control border-0 bg-transparent" id="subjectSearchInModal" placeholder="Find subjects...">
                                    </div>
                                </div>
                                <div class="card-body px-3 pb-3 pt-0">
                                    <div class="list-group list-group-flush border rounded-3 overflow-auto" style="height: 350px;" id="modalSubjectsList">
                                        @foreach($subjects as $subject)
                                        <div class="list-group-item py-2 px-3 modal-subject-item transition-all" data-name="{{ $subject->subject_name }}">
                                            <div class="form-check m-0">
                                                <input class="form-check-input mt-1" type="checkbox" name="subjects[]" value="{{ $subject->id }}" id="sub_{{ $subject->id }}" {{ in_array($subject->id, $assignedSubjects) ? 'checked' : '' }}>
                                                <label class="form-check-label small d-block" for="sub_{{ $subject->id }}">
                                                    <div class="fw-bold">{{ $subject->subject_name }}</div>
                                                    <div class="extra-small text-muted">Credits: {{ $subject->credits }}</div>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-white border rounded-4 shadow-sm">
                        <div class="text-muted extra-small fw-bold"><i class="fas fa-info-circle me-1 text-primary"></i> Selection will sync on update.</div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light px-4 py-2 border rounded-pill small fw-bold" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-pill small">Update Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .custom-tabs .nav-link {
        border-radius: 0;
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 1rem 1.5rem;
        position: relative;
    }
    .custom-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #0d6efd;
    }
    
    .custom-participants-table td {
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
    }
    
    .extra-small { font-size: 0.7rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.2s ease; }
    
    .modal-user-item:hover {
        background-color: #f8fbff;
        transform: scale(1.01);
    }
</style>
@endsection

@section('scripts')
<script>
    // Real-time Dashboard Filtering
    const searchInput = document.getElementById('participantSearch');
    const tableBody = document.getElementById('participantsTableBody');
    const rows = tableBody.querySelectorAll('.participant-row');
    const countDisplay = document.getElementById('participantCountDisplay');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        let matchCount = 0;

        rows.forEach(row => {
            const textContent = Array.from(row.querySelectorAll('.searchable-cell'))
                .map(cell => cell.textContent.toLowerCase())
                .join(' ');
            
            if (textContent.includes(query)) {
                row.style.display = '';
                matchCount++;
            } else {
                row.style.display = 'none';
            }
        });

        countDisplay.textContent = matchCount;
        
        // Handle Empty State
        const emptyRow = document.getElementById('noResultsRow');
        if (matchCount === 0 && rows.length > 0) {
            if (!emptyRow) {
                const tr = document.createElement('tr');
                tr.id = 'noResultsRow';
                tr.innerHTML = '<td colspan="7" class="text-center py-5 text-muted">No matches found for "' + query + '"</td>';
                tableBody.appendChild(tr);
            }
        } else if (emptyRow) {
            emptyRow.remove();
        }
    });

    // Modal User Search
    document.getElementById('userSearchInModal').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.modal-user-item').forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            const email = (item.querySelector('.text-muted')?.textContent || '').toLowerCase();
            item.style.setProperty('display', (name.includes(query) || email.includes(query)) ? 'flex' : 'none', 'important');
        });
    });

    // Modal Subject Search
    document.getElementById('subjectSearchInModal').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.modal-subject-item').forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            item.style.setProperty('display', name.includes(query) ? 'block' : 'none', 'important');
        });
    });

    // Select All Checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.participant-row input[type="checkbox"]').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
