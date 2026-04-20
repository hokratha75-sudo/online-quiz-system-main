@extends('layouts.admin')

@section('content')
<div class="max-w-[1000px] mx-auto p-6 md:p-8 lg:p-10 font-inter">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-5">
            <a href="{{ route('admin.users.index') }}" class="w-12 h-12 rounded-2xl border border-slate-100 bg-white flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm group">
                <i class="fas fa-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight uppercase">Register Entity</h1>
                <p class="text-xs font-bold text-indigo-600 mt-1 uppercase tracking-widest">Initialize a new administrative node or student profile</p>
            </div>
        </div>
        <div class="hidden md:flex items-center gap-3">
            <span class="px-4 py-2 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-sm">Protocol v2.4</span>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-8 bg-rose-50 border border-rose-100 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-3 text-rose-600 font-bold uppercase text-[10px] tracking-widest mb-3">
                <i class="fas fa-exclamation-triangle mt-px"></i>
                Validation Protocols Failed
            </div>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-xs font-bold text-rose-700 uppercase tracking-tight flex items-center gap-2">
                        <div class="w-1 h-1 rounded-full bg-rose-400"></div>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Authentication Matrix -->
        <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-md">
                    <i class="fas fa-shield-alt text-[10px]"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-900 tracking-widest uppercase">Authentication Matrix</h3>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div>
                        <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Username <span class="text-rose-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="e.g. johndoe" required 
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" required 
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">System Tier <span class="text-rose-500">*</span></label>
                        <select name="role_id" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ (old('role_id') == $role->id) ? 'selected' : '' }}>
                                    {{ strtoupper($role->role_name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Security Pass <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" placeholder="••••••••" required 
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                        <p class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Min 8 characters</p>
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Confirm Protocol <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required 
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Intelligence -->
        <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-md">
                    <i class="fas fa-user-tag text-[10px]"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-900 tracking-widest uppercase">Personal Intelligence</h3>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Given Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="John" 
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Surname <span class="text-rose-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Doe" 
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Comm Index (Phone)</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 234 567 890" 
                               class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm tabular-nums">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Physical Location (Address)</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="123 Main St, Anytown" 
                               class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Origin Date (Birthday)</label>
                        <input type="date" name="birthday" value="{{ old('birthday') }}" 
                               class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-sm tabular-nums">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-widest text-indigo-600 uppercase mb-3 ml-1">Gender Index</label>
                        <select name="sex" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>MALE</option>
                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>FEMALE</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 pt-4">
                        <div class="p-6 border border-slate-100 rounded-[24px] bg-slate-50/50 flex flex-col sm:flex-row items-center gap-6">
                            <div class="relative group">
                                <div class="w-24 h-24 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl font-bold border-4 border-white shadow-xl shadow-indigo-600/20 overflow-hidden shrink-0">
                                    <span id="initialPreview" class="text-2xl font-bold">U</span>
                                    <img src="" class="w-full h-full object-cover hidden" id="avatarPreview">
                                </div>
                                <button type="button" onclick="document.getElementById('photoInput').click()" class="absolute -bottom-2 -right-2 w-8 h-8 bg-white border border-slate-100 text-indigo-600 rounded-lg shadow-lg flex items-center justify-center hover:bg-slate-950 hover:text-white transition-all">
                                    <i class="fas fa-camera text-xs"></i>
                                </button>
                            </div>
                            <div class="flex-grow text-center sm:text-left">
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Visual Identity</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 tracking-tight">Upload profile visualization node</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-2 tracking-tight">JPG, PNG or GIF • Max 2MB</p>
                                <input type="file" name="profile_photo" id="photoInput" class="hidden" accept="image/*">
                                <button type="button" onclick="document.getElementById('photoInput').click()" class="mt-3 text-[10px] font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-widest transition-colors flex items-center gap-2 mx-auto sm:mx-0">
                                   <i class="fas fa-upload"></i> Initialize File Upload
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-4 pt-6">
            <button type="submit" class="w-full md:w-auto px-12 h-14 bg-slate-950 hover:bg-slate-900 text-white rounded-2xl font-bold uppercase tracking-widest text-xs transition-all shadow-xl shadow-slate-900/20">Initialize Account</button>
            <a href="{{ route('admin.users.index') }}" class="w-full md:w-auto px-10 h-14 bg-white border border-slate-100 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-2xl font-bold uppercase tracking-widest text-xs transition-all flex items-center justify-center">Cancel Protocol</a>
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
