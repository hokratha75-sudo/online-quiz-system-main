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
    .edit-user-container { background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; max-width: 900px; margin: 2rem auto; border: 1px solid var(--border-color); }
    .header-custom { padding: 1.5rem 2rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #fff; }
    .header-custom h2 { font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0; }
    
    .body-custom { padding: 2rem; }
    .section-header { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 1.5rem; margin-top: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem; }
    .section-header:first-child { margin-top: 0; }
    
    .form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 1.5rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-label-custom { font-size: 0.875rem; font-weight: 500; color: var(--text-main); }
    .form-input-custom { padding: 0.7rem 1rem; border-radius: 8px; border: 1.5px solid var(--border-color); background: var(--input-bg); font-size: 0.875rem; transition: all 0.2s; }
    .form-input-custom:focus { outline: none; border-color: var(--accent-blue); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); background: #fff; }
    
    .current-avatar-preview { width: 100px; height: 100px; border-radius: 12px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .photo-upload-zone { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        border: 1.5px dashed var(--border-color); 
        border-radius: 12px; 
        padding: 1rem; 
        cursor: pointer;
        transition: all 0.2s;
        background: var(--input-bg);
        height: 100px;
    }
    .photo-upload-zone:hover { border-color: var(--accent-blue); background: rgba(59, 130, 246, 0.02); }
    .photo-upload-zone i { font-size: 1.5rem; color: var(--text-muted); margin-bottom: 0.2rem; }
    .photo-upload-zone span { font-size: 0.75rem; color: var(--text-muted); text-align: center; }

    .btn-submit-custom { 
        padding: 0.8rem 2rem; 
        background: var(--accent-blue); 
        color: #fff; 
        border: none; 
        border-radius: 8px; 
        font-weight: 600; 
        font-size: 0.95rem; 
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-submit-custom:hover { background: #2563eb; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
    
    .btn-cancel-custom {
        padding: 0.8rem 2rem; 
        background: #f1f5f9; 
        color: #475569; 
        border: none; 
        border-radius: 8px; 
        font-weight: 600; 
        font-size: 0.95rem; 
        text-decoration: none;
        transition: all 0.2s;
        text-align: center;
    }
    .btn-cancel-custom:hover { background: #e2e8f0; color: #1e293b; }

    .required-star { color: #ef4444; margin-left: 2px; }
    .setting-card { background: var(--input-bg); padding: 1rem; border-radius: 10px; border: 1px solid var(--border-color); }
    
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
        <h1 class="h4 fw-bold mb-0">Update Profile</h1>
        <small class="text-muted">Edit information for <strong>{{ $user->username }}</strong></small>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="edit-user-container">
    <div class="header-custom">
        <div class="d-flex align-items-center gap-3">
            @if($user->profile_photo)
                <img src="{{ asset('storage/'.$user->profile_photo) }}" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
            @else
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px;">
                    <i class="fas fa-user text-muted"></i>
                </div>
            @endif
            <h2>Edit User Account</h2>
        </div>
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">ID: #{{ $user->id }}</span>
    </div>
    
    <div class="body-custom">
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
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Section: Authentication -->
            <div class="section-header">Authentication Information</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label-custom">Username <span class="required-star">*</span></label>
                    <input type="text" name="username" class="form-input-custom" required value="{{ old('username', $user->username) }}">
                    @error('username')<small class="text-danger small">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Email <span class="required-star">*</span></label>
                    <input type="email" name="email" class="form-input-custom" required value="{{ old('email', $user->email) }}">
                    @error('email')<small class="text-danger small">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label-custom">System Role <span class="required-star">*</span></label>
                    <select name="role_id" class="form-input-custom" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->role_name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label-custom">New Password</label>
                    <input type="password" name="password" class="form-input-custom" placeholder="Leave blank to keep current">
                    @error('password')<small class="text-danger small">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Auth Method</label>
                    <select name="auth_method" class="form-input-custom">
                        <option value="manual" {{ old('auth_method', $user->auth_method) == 'manual' ? 'selected' : '' }}>Manual Accounts</option>
                        <option value="ldap" {{ old('auth_method', $user->auth_method) == 'ldap' ? 'selected' : '' }}>LDAP Authentication</option>
                    </select>
                </div>
            </div>

            <!-- Section: Personal Info -->
            <div class="section-header">Personal Information</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label-custom">First Name <span class="required-star">*</span></label>
                    <input type="text" name="first_name" class="form-input-custom" required value="{{ old('first_name', $user->first_name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Last Name <span class="required-star">*</span></label>
                    <input type="text" name="last_name" class="form-input-custom" required value="{{ old('last_name', $user->last_name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Phone</label>
                    <input type="text" name="phone" class="form-input-custom" value="{{ old('phone', $user->phone) }}">
                </div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label-custom">Address</label>
                    <input type="text" name="address" class="form-input-custom" value="{{ old('address', $user->address) }}">
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Birthday</label>
                    <input type="date" name="birthday" class="form-input-custom" value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label-custom">Sex</label>
                    <select name="sex" class="form-input-custom">
                        <option value="" {{ old('sex', $user->sex) == '' ? 'selected' : '' }}>Not Specified</option>
                        <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="form-group d-flex flex-row align-items-center gap-3" style="grid-column: span 2;">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/'.$user->profile_photo) }}" class="current-avatar-preview" id="avatarPreview">
                    @endif
                    <div class="flex-grow-1">
                        <label class="form-label-custom">Change Photo</label>
                        <div class="photo-upload-zone" onclick="document.getElementById('photoInput').click()">
                            <i class="fas fa-camera"></i>
                            <span id="uploadText">Click to upload new</span>
                            <input type="file" name="profile_photo" id="photoInput" class="d-none" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Account Settings -->
            <div class="section-header">Account Status & Policies</div>
            <div class="form-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="setting-card d-flex align-items-center gap-3">
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" name="is_suspended" id="isSuspended" {{ $user->is_suspended ? 'checked' : '' }}>
                    </div>
                    <div>
                        <label class="form-label-custom mb-0 d-block" for="isSuspended">Suspend Account</label>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">Prevents user from logging in</small>
                    </div>
                </div>
                
                <div class="setting-card d-flex align-items-center gap-3">
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" name="force_password_change" id="forcePassword" {{ $user->force_password_change ? 'checked' : '' }}>
                    </div>
                    <div>
                        <label class="form-label-custom mb-0 d-block" for="forcePassword">Force PW Change</label>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">User must change password on next visit</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-submit-custom">Save Changes</button>
                <a href="{{ route('admin.users.index') }}" class="btn-cancel-custom">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Click to upload new';
        document.getElementById('uploadText').textContent = fileName;
        
        // Optional: Preview the new image
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('avatarPreview');
                if (preview) {
                    preview.src = event.target.result;
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endsection
