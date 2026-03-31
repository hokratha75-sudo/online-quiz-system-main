@extends('layouts.admin')

@section('styles')
<style>
    :root {
        --form-bg: #ffffff;
        --input-bg: #f8fafc;
        --accent-blue: #3b82f6;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }
    .create-user-modal { background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; max-width: 800px; margin: 2rem auto; border: 1px solid var(--border-color); }
    .modal-header-custom { padding: 1.5rem 2rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
    .modal-header-custom h2 { font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0; }
    .btn-close-custom { background: none; border: none; font-size: 1.25rem; color: var(--text-muted); cursor: pointer; transition: color 0.2s; }
    .btn-close-custom:hover { color: var(--text-main); }
    
    .modal-body-custom { padding: 2rem; }
    .section-header { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 1.5rem; margin-top: 1.5rem; }
    .section-header:first-child { margin-top: 0; }
    
    .form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-label-custom { font-size: 0.875rem; font-weight: 500; color: var(--text-main); }
    .form-input-custom { padding: 0.7rem 1rem; border-radius: 8px; border: 1.5px solid var(--border-color); background: var(--input-bg); font-size: 0.875rem; transition: all 0.2s; }
    .form-input-custom:focus { outline: none; border-color: var(--accent-blue); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); background: #fff; }
    
    .photo-upload-zone { 
        grid-column: span 1; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        border: 1.5px dashed var(--border-color); 
        border-radius: 12px; 
        padding: 1.5rem; 
        cursor: pointer;
        transition: all 0.2s;
        height: 100%;
        background: var(--input-bg);
    }
    .photo-upload-zone:hover { border-color: var(--accent-blue); background: rgba(59, 130, 246, 0.02); }
    .photo-upload-zone i { font-size: 2rem; color: var(--text-muted); margin-bottom: 0.5rem; }
    .photo-upload-zone span { font-size: 0.8rem; color: var(--text-muted); text-align: center; }

    .btn-submit-custom { 
        width: 100%; 
        padding: 1rem; 
        background: var(--accent-blue); 
        color: #fff; 
        border: none; 
        border-radius: 8px; 
        font-weight: 600; 
        font-size: 1rem; 
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
        margin-top: 1rem;
    }
    .btn-submit-custom:hover { background: #2563eb; transform: translateY(-1px); }
    .btn-submit-custom:active { transform: translateY(0); }

    .required-star { color: #ef4444; margin-left: 2px; font-weight: bold; }
    
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="user-management-heading mb-4 px-2 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h4 fw-bold mb-0">Manage Accounts</h1>
        <small class="text-muted">Register a new user to the system.</small>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="create-user-modal">
    <div class="modal-header-custom">
        <h2>Create a new user</h2>
        <a href="{{ route('admin.users.index') }}" class="btn-close-custom"><i class="fas fa-times"></i></a>
    </div>
    
    <div class="modal-body-custom">
        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm py-3 px-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong class="small uppercase ls-1">Validation Errors</strong>
                </div>
                <ul class="mb-0 small ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Section: Authentication -->
            <div class="section-header">Authentication Information</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label-custom">Username <span class="required-star">*</span></label>
                    <input type="text" name="username" class="form-input-custom" placeholder="e.g. johndoe" required value="{{ old('username') }}">
                    @error('username')<small class="text-danger small">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Email <span class="required-star">*</span></label>
                    <input type="email" name="email" class="form-input-custom" placeholder="john@example.com" required value="{{ old('email') }}">
                    @error('email')<small class="text-danger small">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Password <span class="required-star">*</span></label>
                    <input type="password" name="password" class="form-input-custom" placeholder="••••••••" required>
                    @error('password')<small class="text-danger small">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Confirm Password <span class="required-star">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input-custom" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label class="form-label-custom">System Role <span class="required-star">*</span></label>
                    <select name="role_id" class="form-input-custom" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ (old('role_id') == $role->id || (isset($roleName) && $roleName == $role->role_name)) ? 'selected' : '' }}>
                                {{ ucfirst($role->role_name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Section: Personal Info -->
            <div class="section-header">Personal Information</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label-custom">First Name <span class="required-star">*</span></label>
                    <input type="text" name="first_name" class="form-input-custom" placeholder="John" required value="{{ old('first_name') }}">
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Last Name <span class="required-star">*</span></label>
                    <input type="text" name="last_name" class="form-input-custom" placeholder="Doe" required value="{{ old('last_name') }}">
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Phone</label>
                    <input type="text" name="phone" class="form-input-custom" placeholder="+1 234 567 890" value="{{ old('phone') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label-custom">Address</label>
                    <input type="text" name="address" class="form-input-custom" placeholder="123 Main St, Anytown" value="{{ old('address') }}">
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Birthday</label>
                    <input type="date" name="birthday" class="form-input-custom" value="{{ old('birthday') }}">
                </div>

                <div class="form-group">
                    <label class="form-label-custom">Sex</label>
                    <select name="sex" class="form-input-custom">
                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label-custom">Profile Photo</label>
                    <div class="photo-upload-zone" onclick="document.getElementById('photoInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span id="uploadText">Upload a photo</span>
                        <input type="file" name="profile_photo" id="photoInput" class="d-none" accept="image/*">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit-custom">Create</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Upload a photo';
        document.getElementById('uploadText').textContent = fileName;
    });
</script>
@endsection
