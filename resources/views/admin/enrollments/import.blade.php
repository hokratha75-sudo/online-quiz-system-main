{{-- resources/views/admin/enrollments/import.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto p-6 md:p-10 font-inter text-slate-900 bg-white">
    
    <!-- Header -->
    <div class="mb-12 border-b border-slate-100 pb-10">
        <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-[0.25em] mb-3">
            <i class="fas fa-upload text-[9px] mr-1"></i> Bulk Operations
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-2">Import Enrollment Data</h1>
        <p class="text-sm text-slate-500 uppercase tracking-widest text-[11px]">{{ $department->department_name }}</p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
    <div class="mb-8 bg-red-50 border border-red-100 rounded-xl p-4">
        <h3 class="text-[11px] font-bold text-red-600 uppercase tracking-widest mb-3 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> Import Failed
        </h3>
        <ul class="space-y-2">
            @foreach ($errors->all() as $error)
            <li class="text-[11px] text-red-600">• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('error'))
    <div class="mb-8 bg-red-50 border border-red-100 rounded-xl p-4">
        <p class="text-[11px] font-bold text-red-600 uppercase tracking-widest flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </p>
    </div>
    @endif

    @if (session('import_results'))
    <div class="mb-8 bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="bg-emerald-50 px-4 py-3 border-b border-emerald-100">
            <h3 class="text-[11px] font-bold text-emerald-700 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> Import Results
            </h3>
        </div>
        <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div>
                <p class="text-2xl font-bold text-emerald-600">{{ session('import_results')['success'] ?? 0 }}</p>
                <p class="text-[9px] text-slate-500">Successful</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-500">{{ session('import_results')['errors'] ?? 0 }}</p>
                <p class="text-[9px] text-slate-500">Failed</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-indigo-600">{{ session('import_results')['total'] ?? 0 }}</p>
                <p class="text-[9px] text-slate-500">Total Processed</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-600">{{ session('import_results')['skipped'] ?? 0 }}</p>
                <p class="text-[9px] text-slate-500">Skipped</p>
            </div>
        </div>
        @if(!empty(session('import_results')['errors_list']))
        <div class="border-t border-slate-100 p-4">
            <details>
                <summary class="text-[10px] font-bold text-red-600 cursor-pointer">View Error Details</summary>
                <ul class="mt-2 space-y-1 max-h-40 overflow-y-auto">
                    @foreach(session('import_results')['errors_list'] as $error)
                    <li class="text-[9px] text-red-500">• {{ $error }}</li>
                    @endforeach
                </ul>
            </details>
        </div>
        @endif
    </div>
    @endif

    <!-- Instructions -->
    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 mb-8">
        <h3 class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
            <i class="fas fa-circle-info"></i> How to Use
        </h3>
        <ol class="space-y-2 text-[11px] text-indigo-700 list-decimal list-inside">
            <li>Prepare a CSV file with the following columns: <code class="bg-white px-2 py-1 rounded">Class Name,User Type,Username,Email,Subject</code></li>
            <li>User Type must be either <strong>Student</strong> or <strong>Teacher</strong></li>
            <li>Username must match exactly with existing users in the system</li>
            <li>Subject names must match existing subjects</li>
            <li>Upload the file and click "Import"</li>
        </ol>
    </div>

    <!-- CSV Template -->
    <div class="bg-slate-50 border border-slate-100 rounded-xl p-6 mb-8">
        <h3 class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-4 flex items-center gap-2">
            <i class="fas fa-file-csv text-emerald-600"></i> CSV Template Example
        </h3>
        <pre class="bg-white border border-slate-200 rounded-lg p-4 text-[10px] font-mono overflow-x-auto text-slate-700">Class Name,User Type,Username,Email,Subject
"Computer Science 101",Student,student1,student1@example.com,Programming
"Computer Science 101",Student,student2,student2@example.com,Programming
"Computer Science 101",Teacher,teacher1,teacher1@example.com,Programming
"Computer Science 101",Student,student1,student1@example.com,Algorithms</pre>
        
        <div class="mt-4">
            <a href="javascript:void(0)" onclick="downloadTemplate()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-[11px] font-bold uppercase tracking-widest rounded-lg transition-all">
                <i class="fas fa-download text-[9px]"></i> Download Template
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <form action="{{ route('enrollments.import', $department->id) }}" method="POST" enctype="multipart/form-data" class="mb-8" id="importForm">
        @csrf

        <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center bg-slate-50 hover:bg-slate-100 hover:border-indigo-300 transition-all cursor-pointer group" id="dropZone">
            <input type="file" name="file" accept=".csv,.txt" class="hidden" id="fileInput" required>
            
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-all">
                    <i class="fas fa-cloud-arrow-up text-indigo-600 text-2xl"></i>
                </div>
                
                <h3 class="text-[13px] font-bold text-slate-900 mb-2">Drop your CSV file here</h3>
                <p class="text-[11px] text-slate-500 mb-4">or click to browse</p>
                
                <div class="text-[10px] text-slate-400 space-y-1">
                    <p>✓ CSV format (.csv, .txt)</p>
                    <p>✓ Maximum 10 MB</p>
                    <p>✓ Must contain header row</p>
                </div>

                <div id="fileName" class="mt-4 hidden">
                    <p class="text-[11px] font-bold text-emerald-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span id="fileNameText"></span>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex gap-3 justify-between">
            <a href="{{ route('enrollments.manage', $department->id) }}" class="h-11 px-6 border border-slate-200 text-slate-700 text-[11px] font-bold uppercase tracking-widest hover:bg-slate-50 rounded-lg transition-all">
                Cancel
            </a>
            <button type="submit" id="submitBtn" class="h-11 px-8 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold uppercase tracking-widest rounded-lg transition-all shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-upload mr-2"></i> Import CSV
            </button>
        </div>
    </form>

    <!-- Alternative: Direct API Upload -->
    <div class="border border-slate-100 rounded-xl p-6 bg-slate-50">
        <h3 class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-3 flex items-center gap-2">
            <i class="fas fa-code"></i> Or Use API
        </h3>
        <p class="text-[11px] text-slate-600 mb-4">Send a POST request with CSV content:</p>
        <pre class="bg-white border border-slate-200 rounded-lg p-4 text-[10px] font-mono text-slate-700 overflow-x-auto">curl -X POST {{ route('enrollments.import', $department->id) }} \
  -H "Content-Type: multipart/form-data" \
  -F "file=@enrollment.csv" \
  -H "X-CSRF-TOKEN: {{ csrf_token() }}"</pre>
    </div>
</div>

<script>
function downloadTemplate() {
    const csv = 'Class Name,User Type,Username,Email,Subject\n"Sample Class",Student,student1,student1@example.com,Mathematics\n"Sample Class",Teacher,teacher1,teacher1@example.com,Mathematics';
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'enrollment_template.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const fileName = document.getElementById('fileName');
const fileNameText = document.getElementById('fileNameText');

dropZone.addEventListener('click', () => fileInput.click());

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-indigo-400', 'bg-indigo-50');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-indigo-400', 'bg-indigo-50');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-indigo-400', 'bg-indigo-50');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        updateFileName(files[0].name);
    }
});

fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        updateFileName(e.target.files[0].name);
    }
});

function updateFileName(name) {
    fileNameText.textContent = name;
    fileName.classList.remove('hidden');
}

document.getElementById('importForm')?.addEventListener('submit', function(e) {
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('Please select a CSV file to import.');
    } else {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Importing...';
    }
});
</script>
@endsection