@extends('layouts.admin')

@section('content')
<div class="max-w-[1600px] mx-auto p-8 font-sans">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" 
               class="no-underline w-11 h-11 rounded-xl border border-gray-100 bg-white flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm group">
                <i class="fas fa-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
            </a>
            <div class="mt-3">
                <h1 class="text-lg font-bold text-gray-900 tracking-tight uppercase">Update Profile</h1>
                <p class="text-xs font-bold text-indigo-600 mt-1 uppercase tracking-wider">Editing information for 
                    <span class="text-gray-900 px-2 py-0.5 bg-gray-100 rounded-md">{{ $user->username }}</span>
                </p>
            </div>
        </div>
        <div class="hidden md:flex items-center gap-3">
            <span class="px-6 py-2.5 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">User ID: #{{ $user->id }}</span>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-8 bg-rose-50 border border-rose-100 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-3 text-rose-600 font-bold uppercase text-[10px] tracking-wider mb-3">
                <i class="fas fa-exclamation-triangle"></i>
                Please fix the following errors
            </div>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-xs font-bold text-rose-700 uppercase tracking-wide flex items-center gap-2">
                        <div class="w-1 h-1 rounded-full bg-rose-400"></div>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Authentication Matrix -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-4">
            <div class=" border-b border-gray-50 bg-gray-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-md">
                    <i class="fas fa-shield-alt text-[10px]"></i>
                </div>
                <h3 class="text-xs font-bold text-gray-900 tracking-wider uppercase mt-2">Account Settings</h3>
            </div>
            
            <div class="pt-3">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div>
                        <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">Username <span class="text-rose-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required 
                               class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">Role <span class="text-rose-500">*</span></label>
                        <select name="role_id" class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ strtoupper($role->role_name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">New Password</label>
                        <input type="password" name="password" placeholder="••••••••" 
                               class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                        <p class="mt-2 text-[10px] font-bold text-red-600 uppercase tracking-wider ml-1">Leave blank to keep current • Min 8 characters</p>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">Login Method</label>
                        <select name="auth_method" class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="manual" {{ old('auth_method', $user->auth_method) == 'manual' ? 'selected' : '' }}>Internal Database</option>
                            <option value="ldap" {{ old('auth_method', $user->auth_method) == 'ldap' ? 'selected' : '' }}>Institutional LDAP</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Intelligence -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-4">
            <div class="border-b border-gray-50 bg-gray-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-md">
                    <i class="fas fa-user-tag text-[10px]"></i>
                </div>
                <h3 class="text-xs font-bold text-gray-900 tracking-wider uppercase mt-2">Personal Information</h3>
            </div>
            
            <div class="pt-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">First Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required 
                                   class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">Last Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required 
                                   class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm tabular-nums">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" 
                               class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">Date of Birth</label>
                        <input type="date" name="birthday" value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}" 
                               class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm tabular-nums">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-wider text-indigo-600 uppercase mb-2 ml-1">Gender</label>
                        <select name="sex" class="w-full p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="" {{ old('sex', $user->sex) == '' ? 'selected' : '' }}>NOT DISCLOSED</option>
                            <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>MALE</option>
                            <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>FEMALE</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 pt-4">
                        <div class="p-6 border border-gray-100 rounded-2xl bg-gray-50/50 flex flex-col sm:flex-row items-center gap-6">
                            <div class="relative group">
                                <div class="w-24 h-24 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl font-bold border-4 border-white shadow-xl shadow-indigo-600/20 overflow-hidden shrink-0">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/'.$user->profile_photo) }}" class="w-full h-full object-cover" id="avatarPreview">
                                    @else
                                        <span id="initialPreview">{{ substr($user->username, 0, 1) }}</span>
                                        <img src="" class="w-full h-full object-cover hidden" id="avatarPreview">
                                    @endif
                                </div>
                                <button type="button" onclick="document.getElementById('photoInput').click()" class="absolute -bottom-2 -right-2 w-8 h-8 bg-white border border-gray-100 text-indigo-600 rounded-lg shadow-lg flex items-center justify-center hover:bg-gray-900 hover:text-white transition-all">
                                    <i class="fas fa-camera text-xs"></i>
                                </button>
                            </div>
                            <div class="flex-grow text-center sm:text-left">
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Profile Photo</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase mt-1 tracking-wide">Update your profile picture</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase mt-2 tracking-wide">JPG, PNG or GIF • Max 2MB</p>
                                <input type="file" name="profile_photo" id="photoInput" class="hidden" accept="image/*">
                                <button type="button" onclick="document.getElementById('photoInput').click()" class="mt-3 font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider transition-colors flex items-center gap-2 mx-auto sm:mx-0 p-2.5 bg-gray-50 border border-gray-100 rounded-lg text-xs font-bold text-gray-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                                   <i class="fas fa-upload"></i> Upload Photo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Policies -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-4">
            <div class=" border-b border-gray-50 bg-gray-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-md">
                    <i class="fas fa-gavel text-[10px]"></i>
                </div>
                <h3 class="text-xs font-bold text-gray-900 tracking-wider uppercase mt-2">Account Status</h3>
            </div>
            
            <div class="pt-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="p-3 border border-gray-100 rounded-2xl bg-white flex items-center gap-5 hover:border-rose-500 transition-all cursor-pointer group shadow-sm">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_suspended" id="isSuspended" value="1" class="sr-only peer" {{ $user->is_suspended ? 'checked' : '' }}>
                            <div class="w-10 h-6 bg-gray-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider group-hover:text-rose-600">Suspend Account</h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5 tracking-wide">Prevent this user from logging in</p>
                        </div>
                    </label>
                    
                    <label class="p-3 border border-gray-100 rounded-2xl bg-white flex items-center gap-5 hover:border-indigo-500 transition-all cursor-pointer group shadow-sm">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="force_password_change" id="forcePassword" value="1" class="sr-only peer" {{ $user->force_password_change ? 'checked' : '' }}>
                            <div class="w-10 h-6 bg-gray-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider group-hover:text-indigo-600">Force Password Reset</h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5 tracking-wide">User must change password on next login</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-3 justify-end">
            <a href="{{ route('admin.users.index') }}" class="no-underline w-full md:w-auto px-10 h-12 bg-white border border-gray-100 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-bold uppercase tracking-wider text-xs transition-all flex items-center justify-center">Discard</a>
            <button type="submit" class="w-full md:w-auto px-10 h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold uppercase tracking-wider text-xs transition-all shadow-lg shadow-gray-900/20">Save Changes</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('photoInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const maxBytes = 2 * 1024 * 1024; // 2MB
            if (e.target.files[0].size > maxBytes) {
                alert('Profile photo must be 2MB or less.');
                e.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('avatarPreview');
                const initial = document.getElementById('initialPreview');
                if (preview) {
                    preview.src = event.target.result;
                    preview.classList.remove('hidden');
                    if(initial) initial.classList.add('hidden');
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endsection