@extends('layouts.admin')

@section('styles')
<style>
    :root {
        --form-bg: #ffffff;
        --input-bg: #f8fafc;
        --accent-blue: #140ca8ff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }
    .profile-card { 
        background: #fff; 
        border-radius: 20px; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.04); 
        max-width: 850px; 
        margin: 2rem auto; 
        border: 1px solid var(--border-color);
        position: relative;
    }
    .profile-header-banner { height: 90px; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); position: relative; border-radius: 20px 20px 0 0; }
    .profile-avatar-wrapper { position: absolute; bottom: -65px; left: 40px; z-index: 20; }
    .profile-avatar-main { width: 130px; height: 130px; border-radius: 28px; object-fit: cover; border: 6px solid #fff; box-shadow: 0 12px 30px rgba(0,0,0,0.12); background: #f1f5f9; }
    
    .profile-body { padding: 80px 40px 40px; }
    
    .section-header { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 2rem; margin-top: 2.5rem; display: flex; align-items: center; gap: 15px; }
    .section-header::after { content: ""; flex: 1; height: 1px; background: #f1f5f9; }
    .section-header:first-of-type { margin-top: 0; }
    
    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem 2rem; margin-bottom: 1.5rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
    .form-label-custom { font-size: 0.9rem; font-weight: 600; color: var(--text-main); }
    .form-input-custom { padding: 0.8rem 1.2rem; border-radius: 12px; border: 1.5px solid var(--border-color); background: var(--input-bg); font-size: 0.95rem; transition: all 0.2s; }
    .form-input-custom:focus { outline: none; border-color: var(--accent-blue); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); background: #fff; }
    
    .photo-action-zone { display: flex; align-items: center; gap: 1rem; margin-top: 1rem; background: #f8fafc; padding: 1.25rem; border-radius: 16px; border: 1.5px dashed #cbd5e1; cursor: pointer; transition: all 0.2s; }
    .photo-action-zone:hover { border-color: var(--accent-blue); background: #f5f8ff; }
    .photo-action-zone i { font-size: 1.5rem; color: var(--accent-blue); }
    
    .btn-save-profile { 
        padding: 0.9rem 2.5rem; 
        background: var(--accent-blue); 
        color: #fff; border: none; 
        border-radius: 14px; 
        font-weight: 700; 
        font-size: 1rem; 
        cursor: pointer; 
        transition: all 0.2s; 
        display: inline-flex; 
        align-items: center; 
        gap: 10px;
        position: relative;
        z-index: 50;
    }
    .btn-save-profile:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3); }

    .sticky-actions {
        position: sticky;
        bottom: 20px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(8px);
        padding: 1.5rem;
        margin: -1.5rem;
        margin-top: 3rem;
        border-radius: 0 0 20px 20px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
    }

    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
        .profile-body { padding: 80px 20px 20px; }
    }
</style>
@endsection
@section('topbar-title', 'Account Settings')

@section('content')

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center gap-3 p-3 mb-4">
        <i class="fas fa-check-circle fs-4 text-success"></i>
        <div class="fw-medium text-success">{{ session('success') }}</div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-4 p-3 mb-4">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="fas fa-exclamation-triangle text-danger"></i>
            <span class="fw-bold text-danger">Validation failed:</span>
        </div>
        <ul class="mb-0 ps-4 small text-danger">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="profile-card">
    <div class="profile-header-banner">
        <div class="profile-avatar-wrapper">
            @if($user->profile_photo)
                <img src="{{ asset('storage/'.$user->profile_photo) }}" class="profile-avatar-main shadow-lg border-4 border-white" id="avatarPreview">
            @else
                <div class="profile-avatar-main d-flex align-items-center justify-content-center bg-white shadow-lg">
                    <i class="fas fa-user fa-3x text-muted opacity-25"></i>
                </div>
            @endif
        </div>
    </div>
    
    <div class="profile-body">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')

            <!-- Section: Identity -->
            <div class="section-header">Personal Identity</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label-custom">First Name</label>
                    <input type="text" name="first_name" class="form-input-custom" value="{{ old('first_name', $user->first_name) }}" placeholder="e.g. Hok" required>
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Last Name</label>
                    <input type="text" name="last_name" class="form-input-custom" value="{{ old('last_name', $user->last_name) }}" placeholder="e.g. Ratha" required>
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Email Address</label>
                    <input type="email" name="email" class="form-input-custom" value="{{ old('email', $user->email) }}" placeholder="hok.ratha@example.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Phone Number</label>
                    <input type="text" name="phone" class="form-input-custom" value="{{ old('phone', $user->phone) }}" placeholder="+1 234 567 890">
                </div>
            </div>

            <!-- Section: Photo -->
            <div class="section-header">Profile Photo</div>
            <p class="text-muted small mb-3">Upload a new picture to change your public avatar. Recommended size: 512x512px.</p>
            
            <div class="photo-action-zone" onclick="document.getElementById('photoInput').click()">
                <div class="flex-shrink-0 bg-white shadow-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="fas fa-camera"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold text-dark small" id="uploadText">Choose a new profile picture</div>
                    <div class="text-muted" style="font-size: 0.75rem;">JPG, PNG or GIF (Max 2MB)</div>
                </div>
                <input type="file" name="profile_photo" id="photoInput" class="d-none" accept="image/*">
            </div>

            <!-- Section: Bio & Details -->
            <div class="section-header">Address & Details</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label-custom">Birth Date</label>
                    <input type="date" name="birthday" class="form-input-custom" value="{{ old('birthday', $user->birthday) }}">
                </div>
                <div class="form-group">
                    <label class="form-label-custom">Gender</label>
                    <select name="sex" class="form-input-custom">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="form-group col-span-2" style="grid-column: span 2;">
                    <label class="form-label-custom">Full Address</label>
                    <input type="text" name="address" class="form-input-custom" value="{{ old('address', $user->address) }}" placeholder="123 Street, City, Country">
                </div>
            </div>

            <!-- Section: Security -->
            <div class="section-header">Security</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label-custom">Change Password</label>
                    <input type="password" name="password" class="form-input-custom" placeholder="•••••••• (Leave blank to keep current)">
                    <small class="text-muted mt-1" style="font-size: 0.75rem;">Minimum 8 characters</small>
                </div>
            </div>

            <div class="mt-5 pt-4 d-flex justify-content-end border-top">
                <button type="submit" class="btn-save-profile">
                    <i class="fas fa-save"></i>
                    Update My Profile
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Login History Section -->
<div class="profile-card mt-4 mb-5">
    <div class="profile-body pt-4">
        <div class="section-header mt-0" style="margin-bottom: 1.5rem;">
            <i class="fas fa-history me-2 text-primary"></i> Recent Login History
        </div>
        <div class="table-responsive bg-white rounded-3 border">
            <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 fw-semibold border-bottom-0 py-3">Date & Time</th>
                        <th class="fw-semibold border-bottom-0 py-3">IP Address</th>
                        <th class="fw-semibold border-bottom-0 py-3">Device / Browser</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loginHistories ?? [] as $login)
                    <tr>
                        <td class="ps-4 align-middle">
                            <div class="fw-bold text-dark">{{ $login->login_at->format('M d, Y') }}</div>
                            <div class="text-muted" style="font-size: 0.8rem;">{{ $login->login_at->format('h:i A') }}</div>
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-light text-dark font-monospace px-2 py-1 border">{{ $login->ip_address }}</span>
                        </td>
                        <td class="align-middle">
                            <div class="text-muted text-truncate" style="max-width: 300px; font-size: 0.85rem;" title="{{ $login->user_agent }}">
                                <i class="fas fa-laptop text-secondary opacity-50 me-2"></i>{{ \Illuminate\Support\Str::limit($login->user_agent ?? 'Unknown Device', 50) }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-5">
                            <i class="fas fa-shield-alt fs-3 mb-2 opacity-25 d-block"></i>
                            No recent login history recorded.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Preview Management
    document.getElementById('photoInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const maxBytes = 2 * 1024 * 1024; // 2MB
            if (e.target.files[0].size > maxBytes) {
                alert('Profile photo must be 2MB or less.');
                e.target.value = '';
                return;
            }
            const fileName = e.target.files[0].name;
            document.getElementById('uploadText').textContent = fileName;
            
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('avatarPreview');
                if (preview) {
                    preview.src = event.target.result;
                    preview.classList.remove('d-none');
                } else {
                    // Force reload logic removed, just update the wrapper
                    const wrapper = document.querySelector('.profile-avatar-wrapper');
                    wrapper.innerHTML = `<img src="${event.target.result}" class="profile-avatar-main shadow-lg border-4 border-white" id="avatarPreview">`;
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Submit Verification
    document.getElementById('profileForm').addEventListener('submit', function() {
        console.log('Form submitting...');
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        btn.disabled = true;
    });
</script>
@endsection
