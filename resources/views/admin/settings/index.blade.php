@extends('layouts.admin')

@section('content')
<!-- Flash Messages -->
    <!-- FLASH ALERTS CONTAINER (optional but cleaner) -->
@if(session('success') || session('error') || $errors->any())

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="fixed top-4 right-4 z-[9999] success-alert w-[360px] bg-white border border-emerald-100 rounded-2xl p-3.5 flex items-center justify-between shadow-xl overflow-hidden transition-all duration-300">

            <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>

            <div class="flex items-center gap-3.5">
                <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100/50">
                    <i class="fas fa-check text-emerald-500 text-xs"></i>
                </div>

                <div>
                    <h4 class="text-[13px] font-bold text-slate-900 leading-tight">Action Successful</h4>
                    <p class="text-[11px] font-medium text-slate-500">
                        {{ session('success') }}
                    </p>
                </div>
            </div>

            <button type="button"
                class="w-10 h-10 rounded-full bg-emerald-100 border border-emerald-500 text-emerald-600 hover:bg-emerald-200"
                onclick="this.parentElement.remove()">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    @endif


    <!-- ERROR -->
    @if(session('error') || $errors->any())
        <div class="fixed top-4 right-4 z-[9999] error-alert w-[360px] bg-white border border-rose-100 rounded-2xl p-3.5 flex items-center justify-between shadow-xl overflow-hidden transition-all duration-300">

            <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>

            <div class="flex items-center gap-3.5">
                <div class="w-9 h-9 rounded-full bg-rose-50 flex items-center justify-center shrink-0 border border-rose-100/50">
                    <i class="fas fa-exclamation-triangle text-rose-500 text-xs"></i>
                </div>

                <div>
                    <h4 class="text-[13px] font-bold text-slate-900 leading-tight">Action Failed</h4>

                    <p class="text-[11px] font-medium text-slate-500">
                        @if(session('error'))
                            {{ session('error') }}
                        @elseif($errors->any())
                            {{ $errors->first() }}
                        @endif
                    </p>
                </div>
            </div>

            <button type="button"
                class="w-10 h-10 rounded-full bg-rose-100 border border-rose-500 text-rose-600 hover:bg-rose-200"
                onclick="this.parentElement.remove()">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    @endif
@endif
<script>
document.addEventListener("DOMContentLoaded", function () {

    function autoHide(selector) {
        const el = document.querySelector(selector);

        if (el) {
            setTimeout(() => {
                el.classList.add("opacity-0", "translate-x-5");

                setTimeout(() => {
                    el.remove();
                }, 300);

            }, 5000);
        }
    }

    autoHide(".success-alert");
    autoHide(".error-alert");

});
</script>
<style>
    
    /* Custom Scrollbar for sleek aesthetic */
    .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4f46e5; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
</style>
<div class="max-w-6xl mx-auto p-6 md:p-8 space-y-8 font-inter">
    <div class="flex items-center gap-6 mb-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 leading-none">Manage Setting</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">Manage global application architecture</p>
        </div>
    </div>

    <!-- Main Settings Card -->
    <div x-data="{ tab: '{{ old('_tab', request('_tab', 'general')) }}' }" class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative">
        <div class="absolute inset-0 bg-transparent pointer-events-none"></div>
        
        <div class="relative z-10">
            <!-- Tabs Nav -->
<div class="w-full border-b border-slate-200 bg-white/50 backdrop-blur-md sticky top-0 z-10 pt-4 px-4">
    <div class="flex overflow-x-auto no-scrollbar">
        <div class="flex items-center justify-center gap-3" role="tablist">

            <!-- General Tab -->
            <button
                @click="tab = 'general'"
                :class="tab === 'general' 
                    ? 'bg-rose-600 text-white shadow-md shadow-indigo-200 border-none' 
                    : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200'"
                class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 ease-in-out whitespace-nowrap focus:outline-none"
                role="tab"
                :aria-selected="tab === 'general'">
                <i class="fas fa-compass text-sm"></i>
                <span>General</span>
            </button>

            <!-- Quiz Tab -->
            <button
                @click="tab = 'quiz'"
                :class="tab === 'quiz' 
                    ? 'bg-green-600 text-white shadow-md shadow-sky-200 border-none' 
                    : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200'"
                class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 ease-in-out whitespace-nowrap focus:outline-none"
                role="tab"
                :aria-selected="tab === 'quiz'">
                <i class="fas fa-file-lines text-sm"></i>
                <span>Quiz Rules</span>
            </button>

            <!-- Security Tab -->
            <button
                @click="tab = 'security'"
                :class="tab === 'security' 
                    ? 'bg-blue-600 text-white shadow-md shadow-rose-200 border-none' 
                    : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200'"
                class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 ease-in-out whitespace-nowrap focus:outline-none"
                role="tab"
                :aria-selected="tab === 'security'">
                <i class="fas fa-shield-halved text-sm"></i>
                <span>Security</span>
            </button>

        </div>
    </div>
</div>
            <!-- Tab Content Area -->
            <div class="px-6 bg-white">

                <!-- GENERAL TAB -->
                <div x-show="tab === 'general'" 
     x-transition:enter="transition ease-out duration-300" 
     x-transition:enter-start="opacity-0 translate-y-4" 
     x-transition:enter-end="opacity-100 translate-y-0" 
     class="space-y-8 p-1">
    
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="_tab" value="general">

        <!-- Section Maintenance Mode -->
        <div class="group bg-white border border-slate-200 rounded-[24px] p-6 md:p-8">
            <div class="flex items-center gap-5 mb-8">
                <div class="w-14 h-14 rounded-[20px] bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold tracking-tight text-slate-800">System Rest Time</h3>
                    <p class="text-sm font-medium text-slate-500">Pause student activities for maintenance purposes</p>
                </div>
            </div>

            <!-- Toggle Control Card -->
            <div x-data="{ 
                    enabled: {{ (string)($settings['maintenance_mode'] ?? '0') === '1' ? 'true' : 'false' }},
                    loading: false,
                    async toggle() {
                        this.loading = true;
                        try {
                            const res = await fetch('{{ route('admin.settings.update') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    'maintenance_mode': this.enabled ? '1' : '0',
                                    '_tab': 'general'
                                })
                            });
                            if (!res.ok) throw new Error();
                        } catch (e) {
                            this.enabled = !this.enabled;
                            alert('Failed to save maintenance mode.');
                        } finally {
                            this.loading = false;
                        }
                    }
                }" 
                class="flex items-center justify-between bg-slate-50/50 border border-slate-100 p-6 rounded-2xl">
                
                <div class="flex items-center gap-4">
                    <div :class="enabled ? 'bg-rose-500 text-white rotate-[360deg]' : 'bg-slate-200 text-slate-500'" 
                         class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 shadow-sm">
                        <i class="fas fa-power-off text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Maintenance Status</h4>
                        <p class="text-[12px] font-medium text-slate-400 mt-0.5" x-text="enabled ? 'Currently restricted' : 'Public access enabled'"></p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div x-show="loading" class="animate-spin text-rose-500">
                        <i class="fas fa-circle-notch"></i>
                    </div>
                    
                    <!-- Modern Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer select-none">
                        <input type="checkbox" class="sr-only peer" x-model="enabled" @change="toggle()">
                        <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-500"></div>
                    </label>
                </div>
            </div>
            
            <p class="mt-5 text-[11px] text-slate-400 italic">
                * Note: When active, only users with administrative privileges can access the front-end.
            </p>
        </div>

        <!-- Sticky Footer Actions -->
        <div class="sticky bottom-4 left-0 right-0 px-6 py-4 bg-white/70 backdrop-blur-xl border border-slate-200 rounded-[20px] shadow-2xl shadow-slate-200/50 flex justify-end items-center z-20 mt-12 transition-all duration-300 hover:bg-white">
            <div class="mr-auto hidden md:block">
                <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Settings Manager</span>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-3 hover:shadow-lg hover:shadow-indigo-500/25 active:scale-95 border-none">
                <i class="fas fa-save text-indigo-300"></i> 
                Save Changes 
            </button>
        </div>
    </form>
</div>

                <!-- QUIZ RULES TAB -->
                <div x-show="tab === 'quiz'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="_tab" value="quiz">

                        <!-- Default Quiz Params -->
                        <div class="bg-slate-50/30 border border-slate-100 rounded-2xl p-10 md:p-15 transition-all duration-300">
                            <div class="flex items-center gap-5 mb-10">
                                <div class="w-14 h-14 rounded-[20px] bg-indigo-50 text-white flex items-center justify-center shrink-0 ">
                                    <i class="fas fa-sliders text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold tracking-tight text-slate-900">Quick Start Defaults</h3>
                                    <p class="text-sm font-medium text-slate-400 mt-1">Setup values for new quizzes</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Default Time Limit</label>
                                    <div class="relative flex items-center">
                                        <input type="number" name="default_time_limit" value="{{ old('default_time_limit', $settings['default_time_limit'] ?? 30) }}" min="1" max="300" class="w-full pr-12 pl-3 py-2.5 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 font-medium">
                                        <span class="absolute right-4 text-xs font-semibold text-slate-400">MINS</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Pass Grading</label>
                                    <div class="relative flex items-center">
                                        <input type="number" name="default_pass_percentage" value="{{ old('default_pass_percentage', $settings['default_pass_percentage'] ?? 60) }}" min="1" max="100" class="w-full pr-12 pl-3 py-2.5 rounded-lg border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 font-medium">
                                        <span class="absolute right-4 text-xs font-semibold text-slate-400">%</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Max Attempts</label>
                                    <div class="relative flex items-center">
                                        <input type="number" name="max_attempts" value="{{ old('max_attempts', $settings['max_attempts'] ?? 1) }}" min="1" max="99" class="w-full pl-3 py-2.5 rounded-lg border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 font-medium border-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Display Logic -->
                        <div class="bg-slate-50/30 border border-slate-100 rounded-2xl p-8 md:p-10 transition-all duration-300">
                            <div class="flex items-center gap-5 mb-10">
                                <div class="w-14 h-14 rounded-[20px] bg-emerald-50 text-white flex items-center justify-center shrink-0">
                                    <i class="fas fa-shuffle text-emerald-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold tracking-tight text-slate-900">Question Delivery</h3>
                                    <p class="text-sm font-medium text-slate-400 mt-1">Control how students see and interact with questions</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                @php
                                    $toggles = [
                                        ['name' => 'shuffle_questions', 'title' => 'Shuffle Questions', 'desc' => 'Randomize the order of questions for every attempt.', 'value' => (string)($settings['shuffle_questions'] ?? '1')],
                                        ['name' => 'shuffle_answers', 'title' => 'Shuffle Answers', 'desc' => 'Randomize the order of answer choices for each question.', 'value' => (string)($settings['shuffle_answers'] ?? '1')],
                                        ['name' => 'show_result_immediately', 'title' => 'Instant Results', 'desc' => 'Students see their score right after submitting.', 'value' => (string)($settings['show_result_immediately'] ?? '1')],
                                        ['name' => 'allow_review', 'title' => 'Post-Quiz Review', 'desc' => 'Let students review correct answers after the quiz ends.', 'value' => (string)($settings['allow_review'] ?? '1')]
                                    ];
                                @endphp
                                @foreach($toggles as $toggle)
                                <div x-data="{ 
                                    name: '{{ $toggle['name'] }}',
                                    enabled: {{ $toggle['value'] === '1' ? 'true' : 'false' }},
                                    loading: false,
                                    async toggle() {
                                        this.loading = true;
                                        try {
                                            const res = await fetch('{{ route('admin.settings.update') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Accept': 'application/json'
                                                },
                                                body: JSON.stringify({
                                                    [this.name]: this.enabled ? '1' : '0',
                                                    '_tab': 'quiz'
                                                })
                                            });
                                            if (!res.ok) throw new Error();
                                        } catch (e) {
                                            this.enabled = !this.enabled;
                                            alert('Failed to save setting.');
                                        } finally {
                                            this.loading = false;
                                        }
                                    }
                                }" class="flex items-center justify-between bg-white border border-slate-200 p-5 rounded-2xl hover:border-slate-300 transition-all duration-300 hover:shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div :class="enabled ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-100 text-slate-400'" class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors duration-300">
                                            <i class="fas {{ $toggle['name'] === 'shuffle_questions' ? 'fa-stream' : ($toggle['name'] === 'shuffle_answers' ? 'fa-random' : 'fa-check-circle') }}"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-900">{{ $toggle['title'] }}</h4>
                                            <p class="text-xs text-slate-500 mt-1">{{ $toggle['desc'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div x-show="loading" class="animate-spin text-emerald-500 text-[10px]">
                                            <i class="fas fa-circle-notch"></i>
                                        </div>
                                         <label :for="'quiz_toggle_' + name" class="flex cursor-pointer items-center gap-2">
                                             <div class="relative flex items-center">
                                                 <input type="checkbox" :id="'quiz_toggle_' + name" class="peer sr-only" x-model="enabled" @change="toggle()" />
                                                 <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                                             </div>
                                         </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sticky Footer Actions -->
                        <div class="sticky bottom-4 left-0 right-0 px-6 py-4 bg-white/70 backdrop-blur-xl border border-slate-200 rounded-[20px] shadow-2xl shadow-slate-200/50 flex justify-end items-center z-20 mt-12 transition-all duration-300 hover:bg-white">
                            <div class="mr-auto hidden md:block">
                                <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Quiz Manager</span>
                            </div>
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-3 hover:shadow-lg hover:shadow-indigo-500/25 active:scale-95 border-none">
                                <i class="fas fa-save text-indigo-300"></i> 
                                    Update Quiz Rules 
                            </button>
                        </div>
                    </form>
                </div>

                <!-- SECURITY TAB -->
                <div x-show="tab === 'security'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="_tab" value="security">

                        <div class="bg-slate-50/30 border border-slate-100 rounded-[32px] p-8 md:p-10 transition-all duration-300">
                            <div class="flex items-center gap-5 mb-10">
                                <div class="w-14 h-14 rounded-[20px] bg-indigo-50 text-white flex items-center justify-center shrink-0">
                                    <i class="fas fa-shield-halved text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold tracking-tight text-slate-900">Exam Safety & Fairness</h3>
                                    <p class="text-sm font-medium text-slate-400 mt-1">Encourage academic honesty during attempts</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                @php
                                    $secToggles = [
                                        ['name' => 'disable_right_click', 'icon' => 'fa-mouse-pointer', 'title' => 'Disable Context Menus', 'desc' => 'Prevents students from right-clicking or copying questions.', 'value' => (string)($settings['disable_right_click'] ?? '1')],
                                        ['name' => 'tab_switch_detection', 'icon' => 'fa-external-link-alt', 'title' => 'Browser Focus Detection', 'desc' => 'Records a violation when a student leaves the quiz tab.', 'value' => (string)($settings['tab_switch_detection'] ?? '1')],
                                        ['name' => 'enforce_fullscreen', 'icon' => 'fa-expand', 'title' => 'Enforce Fullscreen', 'desc' => 'Forces the browser into fullscreen mode. Exiting creates a violation.', 'value' => (string)($settings['enforce_fullscreen'] ?? '0')],
                                        ['name' => 'auto_submit_on_violation', 'icon' => 'fa-clock', 'title' => 'Auto-Submit on Breach', 'desc' => 'Automatically end the quiz when violation threshold is reached.', 'value' => (string)($settings['auto_submit_on_violation'] ?? '0')]
                                    ];
                                @endphp
                                @foreach($secToggles as $toggle)
                                <div x-data="{ 
                                    name: '{{ $toggle['name'] }}',
                                    enabled: {{ $toggle['value'] === '1' ? 'true' : 'false' }},
                                    loading: false,
                                    async toggle() {
                                        this.loading = true;
                                        try {
                                            const res = await fetch('{{ route('admin.settings.update') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Accept': 'application/json'
                                                },
                                                body: JSON.stringify({
                                                    [this.name]: this.enabled ? '1' : '0',
                                                    '_tab': 'security'
                                                })
                                            });
                                            if (!res.ok) throw new Error();
                                        } catch (e) {
                                            this.enabled = !this.enabled;
                                            alert('Failed to save setting.');
                                        } finally {
                                            this.loading = false;
                                        }
                                    }
                                }" class="flex items-center justify-between bg-white border border-slate-200 p-5 rounded-2xl hover:border-slate-300 transition-all duration-300 hover:shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div :class="enabled ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-400'" class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors duration-300">
                                            <i class="fas {{ $toggle['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-900">{{ $toggle['title'] }}</h4>
                                            <p class="text-xs text-slate-500 mt-1">{{ $toggle['desc'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div x-show="loading" class="animate-spin text-blue-500 text-[10px]">
                                            <i class="fas fa-circle-notch"></i>
                                        </div>
                                         <label :for="'sec_toggle_' + name" class="flex cursor-pointer items-center gap-2">
                                             <div class="relative flex items-center">
                                                 <input type="checkbox" :id="'sec_toggle_' + name" class="peer sr-only" x-model="enabled" @change="toggle()" />
                                                 <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-500"></div>
                                             </div>
                                         </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-8 border-t border-slate-200 pt-8">
                                <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase mb-3">Warning Limit</label>
                                <div class="relative flex items-center max-w-xs">
                                    <input type="number" name="max_violations" value="{{ old('max_violations', $settings['max_violations'] ?? 3) }}" min="1" max="20" class="w-full pl-4 pr-12 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-red-500/20 focus:border-blue-500 transition-all duration-300 outline-none text-sm text-slate-800 font-medium">
                                    <span class="absolute right-4 text-xs font-semibold text-slate-400">Strikes</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">After this number of warnings, the test will be finished automatically.</p>
                            </div>
                        </div>

                        <!-- Sticky Footer Actions -->
                        <div class="sticky bottom-4 left-0 right-0 px-6 py-4 bg-white/70 backdrop-blur-xl border border-slate-200 rounded-[20px] shadow-2xl shadow-slate-200/50 flex justify-end items-center z-20 mt-12 transition-all duration-300 hover:bg-white">
                            <div class="mr-auto hidden md:block">
                                <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Security Manager</span>
                            </div>
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-3 hover:shadow-lg hover:shadow-indigo-500/25 active:scale-95 border-none">
                                <i class="fas fa-save text-indigo-300"></i> 
                                    Update Security Policy 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
