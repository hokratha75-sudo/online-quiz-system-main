{{-- index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Courses')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Welcome Section --}}
    <div class="welcome-section mb-4">
        <div class="welcome-content">
            <div class="welcome-text">
                <div class="welcome-greeting">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Course Management
                    <span class="date-badge">
                        <i class="far fa-calendar-alt me-1"></i>
                        {{ now()->format('l, F j, Y') }}
                    </span>
                </div>
                
                <h1 class="welcome-title">
                    {{ $dashboardTitle ?? 'Course Catalog' }}
                    @if($subjects->total() > 0)
                        <span class="course-count">({{ $subjects->total() }} courses)</span>
                    @endif
                </h1>
            </div>
            
            <div class="welcome-stats">
                <div class="stat-circle">
                    <div class="stat-number">{{ $subjects->total() }}</div>
                    <div class="stat-label">Total Courses</div>
                </div>
                @if(Auth::user()->role_id != 1)
                <div class="stat-circle">
                    <div class="stat-number">{{ $enrolledCount ?? 0 }}</div>
                    <div class="stat-label">Enrolled</div>
                </div>
                <div class="stat-circle">
                    <div class="stat-number">{{ $completedCount ?? 0 }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Search and Filter Bar --}}
    <div class="search-filter-section mb-4">
        <div class="search-bar-wrapper">
            <form action="{{ route('courses.index') }}" method="GET" class="search-form">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input 
                        type="text" 
                        name="search" 
                        class="search-input"
                        placeholder="Search course name or code..."
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                    @if(request('search'))
                        <a href="{{ route('courses.index') }}" class="clear-search">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    @endif
                    <button type="submit" class="search-btn">Search</button>
                </div>
            </form>
            
            <div class="filter-actions">
                @if(Auth::user()->role_id == 1)
                <a href="{{ route('admin.subjects.index', ['add' => 1]) }}" class="action-btn primary">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add Course
                </a>
                @endif
                
                <div class="dropdown">
                    <button class="action-btn secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i>
                        Filter
                        @if(request('status') || request('semester'))
                            <span class="filter-badge">●</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end filter-dropdown">
                        <div class="dropdown-header">Filter Courses</div>
                        <div class="dropdown-divider"></div>
                        <form method="GET" action="{{ route('courses.index') }}">
                            <div class="px-3 py-2">
                                <label class="filter-label">Status</label>
                                <select name="status" class="filter-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="px-3 py-2">
                                <label class="filter-label">Semester</label>
                                <select name="semester" class="filter-select">
                                    <option value="">All Semesters</option>
                                    <option value="spring" {{ request('semester') == 'spring' ? 'selected' : '' }}>Spring</option>
                                    <option value="summer" {{ request('semester') == 'summer' ? 'selected' : '' }}>Summer</option>
                                    <option value="fall" {{ request('semester') == 'fall' ? 'selected' : '' }}>Fall</option>
                                </select>
                            </div>
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <div class="px-3 py-2 d-flex gap-2">
                                <button type="submit" class="filter-apply-btn">Apply</button>
                                @if(request('status') || request('semester'))
                                    <a href="{{ route('courses.index', request()->except(['status', 'semester'])) }}" class="filter-clear-btn">Clear</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="dropdown">
                    <button class="action-btn secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-sort-amount-down-alt me-2"></i>
                        Sort
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('courses.index', array_merge(request()->all(), ['sort' => 'name'])) }}"><i class="fas fa-sort-alpha-down me-2"></i>Name (A-Z)</a></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', array_merge(request()->all(), ['sort' => 'name_desc'])) }}"><i class="fas fa-sort-alpha-up-alt me-2"></i>Name (Z-A)</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', array_merge(request()->all(), ['sort' => 'newest'])) }}"><i class="fas fa-clock me-2"></i>Newest First</a></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index', array_merge(request()->all(), ['sort' => 'oldest'])) }}"><i class="fas fa-history me-2"></i>Oldest First</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Filters Display --}}
    @if(request('search') || request('status') || request('semester'))
    <div class="active-filters mb-4">
        <div class="filters-list">
            <span class="filters-label">Active filters:</span>
            @if(request('search'))
                <span class="filter-chip">
                    <i class="fas fa-search me-1"></i>
                    "{{ request('search') }}"
                    <a href="{{ route('courses.index', request()->except('search')) }}" class="remove-chip">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            @endif
            @if(request('status'))
                <span class="filter-chip">
                    <i class="fas fa-tag me-1"></i>
                    {{ ucfirst(request('status')) }}
                    <a href="{{ route('courses.index', request()->except('status')) }}" class="remove-chip">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            @endif
            @if(request('semester'))
                <span class="filter-chip">
                    <i class="fas fa-calendar me-1"></i>
                    {{ ucfirst(request('semester')) }} Semester
                    <a href="{{ route('courses.index', request()->except('semester')) }}" class="remove-chip">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            @endif
        </div>
    </div>
    @endif

    {{-- Results Info --}}
    <div class="results-info mb-3">
        <div class="results-text">
            @if($subjects->total() > 0)
                Showing <strong>{{ $subjects->firstItem() ?? 0 }}</strong> to <strong>{{ $subjects->lastItem() ?? 0 }}</strong> 
                of <strong>{{ $subjects->total() }}</strong> courses
            @else
                No courses found
            @endif
        </div>
    </div>

    {{-- Courses Grid --}}
    <div class="courses-grid">
        <div class="row g-4">
            @forelse($subjects as $index => $course)
            <div class="col-md-6 col-xl-4">
                <div class="course-card">
                    {{-- Course Header --}}
                    <div class="card-header-section">
                        <div class="course-code">{{ $course->code ?? 'CRS-'.str_pad($course->id, 3, '0', STR_PAD_LEFT) }}</div>
                        <div class="course-status {{ strtolower($course->status ?? 'active') }}">
                            <span class="status-dot"></span>
                            {{ $course->status ?? 'Active' }}
                        </div>
                    </div>
                    
                    {{-- Course Icon --}}
                    <div class="card-icon-section">
                        <div class="course-icon-circle">
                            <i class="fas {{ $course->icon ?? 'fa-book' }}"></i>
                        </div>
                    </div>
                    
                    {{-- Course Title --}}
                    <h3 class="course-title">{{ $course->subject_name }}</h3>
                    
                    {{-- Course Description --}}
                    @if($course->description)
                        <p class="course-description">{{ Str::limit($course->description, 80) }}</p>
                    @endif
                    
                    {{-- Course Details --}}
                    <div class="course-details">
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-building"></i>
                                Department
                            </div>
                            <div class="detail-value">
                                {{ $course->major->department->department_name ?? $course->department->department_name ?? 'General' }}
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-graduation-cap"></i>
                                Major
                            </div>
                            <div class="detail-value">
                                {{ $course->major->name ?? $course->major_name ?? 'General' }}
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-layer-group"></i>
                                Credits
                            </div>
                            <div class="detail-value">
                                <span class="credit-badge">{{ $course->credits ?? 3 }} Credits</span>
                            </div>
                        </div>
                        @if($course->semester)
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-calendar-alt"></i>
                                Semester
                            </div>
                            <div class="detail-value">
                                <span class="semester-badge">{{ ucfirst($course->semester) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Course Stats --}}
                    <div class="course-stats">
                        <div class="stat-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <div class="stat-number">{{ $course->classes->sum('students_count') ?? 0 }}</div>
                                <div class="stat-label">Students</div>
                            </div>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <i class="fas fa-puzzle-piece"></i>
                            <div>
                                <div class="stat-number">{{ $course->quizzes->count() ?? 0 }}</div>
                                <div class="stat-label">Quizzes</div>
                            </div>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <i class="fas fa-hourglass-half"></i>
                            <div>
                                <div class="stat-number">{{ $course->duration ?? 'N/A' }}</div>
                                <div class="stat-label">Hours</div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Card Footer --}}
                    <div class="card-footer-section">
                        @if(Auth::user()->role_id == 1)
                            <div class="admin-actions">
                                <a href="{{ route('admin.subjects.index', ['edit' => $course->id]) }}" class="icon-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.subjects.destroy', $course->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn delete" title="Delete" onclick="return confirm('Delete this course?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                        
                        <a href="{{ route('courses.show', $course->id) }}" class="view-btn">
                            View Course
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>No courses found</h3>
                    <p>We couldn't find any courses matching your search criteria.</p>
                    @if(request('search') || request('status') || request('semester'))
                        <a href="{{ route('courses.index') }}" class="empty-action-btn">
                            <i class="fas fa-redo-alt me-2"></i>Clear all filters
                        </a>
                    @endif
                    @if(Auth::user()->role_id == 1)
                        <a href="{{ route('admin.subjects.index', ['add' => 1]) }}" class="empty-action-btn primary mt-2">
                            <i class="fas fa-plus-circle me-2"></i>Add your first course
                        </a>
                    @endif
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    @if($subjects->hasPages())
    <div class="pagination-section mt-5">
        {{ $subjects->appends(request()->query())->links() }}
    </div>
    @endif

<div class="mt-8">
    {{ $subjects->links() }}
</div>

<style>
/* ============================================
   STUDENT DASHBOARD INSPIRED STYLES
============================================ */

/* Welcome Section */
.welcome-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.welcome-section::before {
    content: '✨';
    position: absolute;
    top: -20px;
    right: -20px;
    font-size: 150px;
    opacity: 0.1;
    pointer-events: none;
}

.welcome-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.welcome-text {
    flex: 1;
}

.welcome-greeting {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    color: white;
    margin-bottom: 1rem;
}

.date-badge {
    margin-left: 1rem;
    font-size: 0.75rem;
    opacity: 0.9;
}

.welcome-title {
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.course-count {
    font-size: 1.25rem;
    font-weight: 400;
    opacity: 0.9;
}

.welcome-message {
    color: rgba(255,255,255,0.95);
    font-size: 0.95rem;
    line-height: 1.5;
    margin: 0;
}

.welcome-stats {
    display: flex;
    gap: 1.5rem;
}

.stat-circle {
    text-align: center;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    padding: 1rem 1.5rem;
    border-radius: 20px;
    min-width: 100px;
}

.stat-circle .stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    line-height: 1;
}

.stat-circle .stat-label {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.8);
    margin-top: 0.25rem;
}

/* Search and Filter Section */
.search-filter-section {
    background: white;
    border-radius: 20px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: 1px solid #e9ecef;
}

.search-bar-wrapper {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.search-form {
    flex: 1;
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 1rem;
    color: #adb5bd;
    font-size: 0.9rem;
}

.search-input {
    flex: 1;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.2s;
    background: #f8f9fa;
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}

.clear-search {
    position: absolute;
    right: 6rem;
    color: #adb5bd;
    text-decoration: none;
}

.clear-search:hover {
    color: #dc2626;
}

.search-btn {
    margin-left: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.search-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102,126,234,0.3);
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
}

.action-btn {
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.action-btn.primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.action-btn.primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102,126,234,0.3);
}

.action-btn.secondary {
    background: #f8f9fa;
    color: #495057;
    border: 1px solid #e9ecef;
}

.action-btn.secondary:hover {
    background: #e9ecef;
}

.filter-badge {
    color: #667eea;
    margin-left: 0.25rem;
    font-size: 0.7rem;
}

/* Filter Dropdown */
.filter-dropdown {
    padding: 0;
    border-radius: 16px;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    min-width: 250px;
}

.dropdown-header {
    padding: 1rem;
    font-weight: 700;
    background: #f8f9fa;
    border-radius: 16px 16px 0 0;
}

.filter-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
}

.filter-select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.85rem;
}

.filter-apply-btn,
.filter-clear-btn {
    flex: 1;
    padding: 0.5rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
}

.filter-apply-btn {
    background: #667eea;
    color: white;
    border: none;
}

.filter-clear-btn {
    background: #f8f9fa;
    color: #6c757d;
    text-decoration: none;
    display: inline-block;
}

/* Active Filters */
.active-filters {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 0.75rem 1rem;
}

.filters-list {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.filters-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    color: #495057;
    border: 1px solid #dee2e6;
}

.remove-chip {
    color: #adb5bd;
    text-decoration: none;
}

.remove-chip:hover {
    color: #dc2626;
}

/* Results Info */
.results-info {
    padding: 0.5rem 0;
}

.results-text {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Course Cards */
.courses-grid {
    margin-top: 1rem;
}

.course-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.1);
}

.card-header-section {
    padding: 1.25rem 1.25rem 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.course-code {
    font-family: 'Courier New', monospace;
    font-size: 0.7rem;
    font-weight: 700;
    color: #667eea;
    background: #f0f0ff;
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
}

.course-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
}

.course-status.active {
    background: #d1fae5;
    color: #059669;
}

.course-status.upcoming {
    background: #fed7aa;
    color: #c2410c;
}

.course-status.completed {
    background: #e2e8f0;
    color: #475569;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.card-icon-section {
    text-align: center;
    padding: 1.25rem;
}

.course-icon-circle {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #f0f0ff, #e9ecef);
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #667eea;
}

.course-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a1a2e;
    padding: 0 1.25rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.course-description {
    font-size: 0.8rem;
    color: #6c757d;
    padding: 0 1.25rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.course-details {
    background: #f8f9fa;
    margin: 0 1.25rem;
    padding: 1rem;
    border-radius: 12px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.75rem;
}

.detail-row:last-child {
    margin-bottom: 0;
}

.detail-label {
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-label i {
    width: 14px;
    font-size: 0.7rem;
}

.detail-value {
    font-weight: 600;
    color: #495057;
}

.credit-badge,
.semester-badge {
    background: white;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
}

.course-stats {
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 1rem 1.25rem;
    gap: 0.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
}

.stat-item i {
    font-size: 1.1rem;
    color: #667eea;
}

.stat-item .stat-number {
    font-weight: 700;
    font-size: 0.9rem;
    color: #1a1a2e;
    line-height: 1.2;
}

.stat-item .stat-label {
    font-size: 0.6rem;
    color: #6c757d;
}

.stat-divider {
    width: 1px;
    height: 30px;
    background: #dee2e6;
}

.card-footer-section {
    padding: 1rem 1.25rem 1.25rem;
    display: flex;
    gap: 0.75rem;
    margin-top: auto;
}

.admin-actions {
    display: flex;
    gap: 0.5rem;
}

.icon-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    transition: all 0.2s;
    cursor: pointer;
}

.icon-btn:hover {
    background: #e9ecef;
    color: #667eea;
}

.icon-btn.delete:hover {
    background: #fee2e2;
    color: #dc2626;
}

.view-btn {
    flex: 1;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.2s;
}

.view-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102,126,234,0.3);
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 24px;
    border: 2px dashed #dee2e6;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 2.5rem;
    color: #adb5bd;
}

.empty-state h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 1rem;
}

.empty-action-btn {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    background: #f8f9fa;
    color: #667eea;
    text-decoration: none;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s;
}

.empty-action-btn.primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.empty-action-btn:hover {
    transform: translateY(-1px);
}

/* Pagination */
.pagination-section {
    display: flex;
    justify-content: center;
}

.pagination-section .pagination {
    gap: 0.5rem;
}

.pagination-section .page-link {
    border-radius: 10px;
    border: 1px solid #e9ecef;
    color: #495057;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.pagination-section .page-link:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

.pagination-section .active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-section {
        padding: 1.5rem;
    }
    
    .welcome-title {
        font-size: 1.5rem;
    }
    
    .welcome-stats {
        width: 100%;
        justify-content: space-around;
    }
    
    .stat-circle {
        padding: 0.75rem 1rem;
        min-width: 80px;
    }
    
    .stat-circle .stat-number {
        font-size: 1.5rem;
    }
    
    .search-bar-wrapper {
        flex-direction: column;
    }
    
    .filter-actions {
        justify-content: flex-end;
    }
    
    .course-stats {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .stat-divider {
        display: none;
    }
    
    .stat-item {
        width: 100%;
    }
}
</style>

<script>
// Auto-search with debounce (optional)
let searchTimeout;
document.querySelector('.search-input')?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        this.closest('form').submit();
    }, 500);
});
</script>
@endsection