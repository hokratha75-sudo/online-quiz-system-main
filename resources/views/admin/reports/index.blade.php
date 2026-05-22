@extends('layouts.admin')

@section('topbar-title', __('Advanced Reports'))

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-siemreap">
    
    <div class="mb-10">
        <h1 class="text-3xl font-moul text-slate-800 mb-2">ការគ្រប់គ្រងរបាយការណ៍ស្មុគស្មាញ</h1>
        <p class="text-slate-500 font-medium">សូមជ្រើសរើសប្រភេទរបាយការណ៍ដែលអ្នកចង់ទាញយក</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Report by Class -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 hover:shadow-xl hover:border-indigo-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                <i class="fas fa-users-class text-2xl"></i>
            </div>
            <h3 class="text-xl font-moul text-slate-800 mb-4">តាមថ្នាក់រៀន</h3>
            <form action="{{ route('admin.reports.generate') }}" method="GET" target="_blank">
                <input type="hidden" name="type" value="class">
                <div class="mb-6">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">ជ្រើសរើសថ្នាក់</label>
                    <select name="id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                        <option value="">-- ជ្រើសរើស --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold text-sm shadow-sm hover:bg-indigo-700 active:scale-[0.98] transition-all focus:outline-none focus:ring-0">
                        ទាញយក Score Report
                    </button>
                    <button type="submit" name="listname" value="1" class="w-full bg-white text-slate-700 border border-slate-200 py-3 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all">
                        ទាញយកបញ្ជីឈ្មោះសិស្ស
                    </button>
                </div>
            </form>
        </div>

        <!-- Report by Major -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 hover:shadow-xl hover:border-emerald-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i class="fas fa-graduation-cap text-2xl"></i>
            </div>
            <h3 class="text-xl font-moul text-slate-800 mb-4">តាមជំនាញសិក្សា</h3>
            <form action="{{ route('admin.reports.generate') }}" method="GET" target="_blank">
                <input type="hidden" name="type" value="major">
                <div class="mb-6">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">ជ្រើសរើសជំនាញ</label>
                    <select name="id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all" required>
                        <option value="">-- ជ្រើសរើស --</option>
                        @foreach($majors as $major)
                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold text-sm shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 active:scale-[0.98] transition-all">
                    ទាញយករបាយការណ៍
                </button>
            </form>
        </div>

        <!-- Report by Department -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 hover:shadow-xl hover:border-rose-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mb-6 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                <i class="fas fa-building text-2xl"></i>
            </div>
            <h3 class="text-xl font-moul text-slate-800 mb-4">តាមដេប៉ាតឺម៉ង់</h3>
            <form action="{{ route('admin.reports.generate') }}" method="GET" target="_blank">
                <input type="hidden" name="type" value="department">
                <div class="mb-6">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">ជ្រើសរើសដេប៉ាតឺម៉ង់</label>
                    <select name="id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-rose-500 outline-none transition-all" required>
                        <option value="">-- ជ្រើសរើស --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-rose-600 text-white py-3 rounded-xl font-bold text-sm shadow-lg shadow-rose-600/20 hover:bg-rose-700 active:scale-[0.98] transition-all">
                    ទាញយករបាយការណ៍
                </button>
            </form>
        </div>

        <!-- Report by Subject -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 hover:shadow-xl hover:border-amber-200 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <h3 class="text-xl font-moul text-slate-800 mb-4">តាមមុខវិជ្ជា</h3>
            <form action="{{ route('admin.reports.generate') }}" method="GET" target="_blank">
                <input type="hidden" name="type" value="subject">
                <div class="mb-6">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">ជ្រើសរើសមុខវិជ្ជា</label>
                    <select name="id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-500 outline-none transition-all" required>
                        <option value="">-- ជ្រើសរើស --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-amber-600 text-white py-3 rounded-xl font-bold text-sm shadow-lg shadow-amber-600/20 hover:bg-amber-700 active:scale-[0.98] transition-all">
                    ទាញយករបាយការណ៍
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
