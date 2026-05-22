<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              outfit: ['"Outfit"', 'sans-serif'],
            },
            colors: {
              brand: '#1e3a6e',
            }
          }
        }
      }
    </script>
    <style>
        @media print {
            @page { margin: 1cm; size: A4; }
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
        body { font-family: 'Outfit', sans-serif; color: #334155; line-height: 1.6; }
    </style>
</head>
<body class="bg-[#f8fafc] md:py-12">

    <div class="max-w-[210mm] mx-auto bg-white shadow-[0_0_50px_rgba(0,0,0,0.05)] relative min-h-[297mm] overflow-hidden rounded-[2rem] border border-slate-100">
        
        <!-- Design Accent -->
        <div class="absolute top-0 left-0 w-full h-2 bg-brand"></div>

        <div class="p-16">
            <!-- Header Section -->
            <div class="flex flex-col items-center text-center mb-16">
                <div class="w-20 h-20 rounded-3xl bg-slate-50 flex items-center justify-center mb-6 border border-slate-100 shadow-sm">
                    <svg class="w-10 h-10 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-[800] text-slate-900 tracking-tight mb-2 uppercase">
                    {{ $title }}
                </h1>
                <div class="flex items-center gap-3">
                    <div class="h-[1px] w-12 bg-slate-200"></div>
                    <p class="text-slate-400 font-bold uppercase tracking-[0.4em] text-[10px]">Academic Assessment Record</p>
                    <div class="h-[1px] w-12 bg-slate-200"></div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-4 gap-6 mb-16">
                <div class="bg-slate-50/50 p-6 rounded-[1.5rem] border border-slate-100 text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Category</p>
                    <p class="text-lg font-black text-brand uppercase">{{ $type }}</p>
                </div>
                <div class="bg-slate-50/50 p-6 rounded-[1.5rem] border border-slate-100 text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Average Score</p>
                    <p class="text-lg font-black text-brand tabular-nums">{{ round($results->avg('score'), 1) }}%</p>
                </div>
                <div class="bg-slate-50/50 p-6 rounded-[1.5rem] border border-slate-100 text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Candidates</p>
                    <p class="text-lg font-black text-brand tabular-nums">{{ $results->unique('user_id')->count() }}</p>
                </div>
                <div class="bg-slate-50/50 p-6 rounded-[1.5rem] border border-slate-100 text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Pass Rate</p>
                    <p class="text-lg font-black text-brand tabular-nums">{{ round(($results->where('passed', true)->count() / max($results->count(), 1)) * 100) }}%</p>
                </div>
            </div>

            <!-- Table Section -->
            <div class="rounded-[1.5rem] border border-slate-100 overflow-hidden shadow-sm">
                <table class="w-full border-collapse text-[13px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest w-16">No.</th>
                            <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Candidate Name</th>
                            <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Quiz Module</th>
                            <th class="px-6 py-5 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest w-24">Score</th>
                            <th class="px-6 py-5 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest w-32">Status</th>
                            <th class="px-6 py-5 text-right text-[10px] font-black text-slate-500 uppercase tracking-widest w-40">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($results as $index => $result)
                        <tr class="group hover:bg-slate-50/30 transition-colors">
                            <td class="px-6 py-5 text-slate-400 font-bold tabular-nums">{{ $index + 1 }}</td>
                            <td class="px-6 py-5 font-[700] text-slate-900">{{ $result->user?->username ?? 'Unknown' }}</td>
                            <td class="px-6 py-5 font-medium text-slate-500">{{ $result->quiz?->title ?? 'N/A' }}</td>
                            <td class="px-6 py-5 text-center font-[800] text-slate-900 tabular-nums">{{ round($result->score, 1) }}%</td>
                            <td class="px-6 py-5 text-center">
                                @if($result->passed)
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-tighter border border-emerald-100">Passed</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-[9px] font-black uppercase tracking-tighter border border-rose-100">Failed</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right text-[11px] text-slate-400 font-bold">
                                {{ $result->completed_at ? $result->completed_at->format('d M Y, h:i A') : 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-32 text-center text-slate-300 italic uppercase tracking-[0.3em] text-[10px] font-black">No assessment records synchronization available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Message -->
            <div class="mt-20 flex justify-between items-center text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">
                <div>Generated on {{ date('d M Y, h:i A') }}</div>
                <div class="flex items-center gap-2">
                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                    <span>Official Assessment Document</span>
                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                </div>
            </div>
        </div>

        <!-- Floating Action Bar -->
        <div class="fixed bottom-10 left-1/2 -translate-x-1/2 no-print bg-white/80 backdrop-blur-2xl border border-slate-200 px-8 py-4 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] flex gap-4 items-center">
            <button onclick="window.print()" class="bg-brand text-white px-10 py-3.5 rounded-[1.2rem] font-black text-[11px] uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center gap-3 shadow-xl shadow-brand/20 active:scale-95">
                <i class="fas fa-print"></i> Print Report
            </button>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="bg-emerald-500 text-white px-8 py-3.5 rounded-[1.2rem] font-black text-[11px] uppercase tracking-widest hover:bg-emerald-600 transition-all flex items-center gap-3 shadow-xl shadow-emerald-500/20 active:scale-95">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <button onclick="window.close()" class="bg-slate-100 text-slate-500 px-8 py-3.5 rounded-[1.2rem] font-black text-[11px] uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95">
                Close
            </button>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
