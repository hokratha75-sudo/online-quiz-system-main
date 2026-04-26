@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto p-6 md:p-8 space-y-8 font-inter">
    <div class="flex items-center gap-6 mb-2">
        <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600 text-white shadow-xl shadow-indigo-600/20">
            <i class="fas fa-microchip text-xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 uppercase">System Blueprint</h1>
            <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mt-1">Configure global synchronization parameters</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between gap-4 transition-all duration-300 ease-in-out">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-check text-sm"></i>
                </div>
                <div class="text-sm font-medium text-emerald-800">{{ session('success') }}</div>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center justify-between gap-4 transition-all duration-300 ease-in-out">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                </div>
                <div class="text-sm font-medium text-red-800">{{ session('error') }}</div>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-start justify-between gap-4 transition-all duration-300 ease-in-out">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                </div>
                <div>
                    <div class="text-sm font-semibold text-red-800 mb-1">Please correct the following errors:</div>
                    <ul class="text-sm font-medium text-red-700 space-y-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700 transition mt-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Main Settings Card -->
    <div x-data="{ tab: '{{ old('_tab', request('_tab', 'general')) }}' }" class="bg-white rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-50/50 to-white pointer-events-none"></div>
        
        <div class="relative z-10">
            <!-- Tabs Nav -->
            <div class="border-b border-slate-50 px-6 md:px-8 pt-6 flex gap-10">
                <button @click="tab = 'general'" :class="tab === 'general' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-900 hover:border-slate-200'" class="pb-4 px-2 border-b-2 font-bold text-[11px] uppercase tracking-widest transition-all">
                    <i class="fas fa-globe mr-1.5"></i> Platform
                </button>
                <button @click="tab = 'quiz'" :class="tab === 'quiz' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-900 hover:border-slate-200'" class="pb-4 px-2 border-b-2 font-bold text-[11px] uppercase tracking-widest transition-all">
                    <i class="fas fa-clipboard-list mr-1.5"></i> Assessment
                </button>
                <button @click="tab = 'security'" :class="tab === 'security' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-900 hover:border-slate-200'" class="pb-4 px-2 border-b-2 font-bold text-[11px] uppercase tracking-widest transition-all">
                    <i class="fas fa-shield-alt mr-1.5"></i> Integrity
                </button>
            </div>

            <!-- Tab Content Area -->
            <div class="p-6 md:p-8 bg-white">

                <!-- GENERAL TAB -->
                <div x-show="tab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        <input type="hidden" name="_tab" value="general">

                        <!-- Maintenance Mode -->
                        <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-6 md:p-8 transition-all duration-300 hover:shadow-md hover:border-slate-200/60">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-600 flex items-center justify-center shrink-0 border border-red-500/20">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold tracking-tight text-slate-900">Maintenance Mode</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Disable student access temporarily</p>
                                </div>
                            </div>

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
                                     }" class="flex items-center justify-between bg-white border border-slate-200 p-5 rounded-2xl hover:border-slate-300 transition-all duration-300 hover:shadow-sm">
                                         <div class="flex items-center gap-4">
                                             <div :class="enabled ? 'bg-red-500/10 text-red-600' : 'bg-slate-100 text-slate-400'" class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors duration-300">
                                                 <i class="fas fa-tools"></i>
                                             </div>
                                             <div>
                                                 <h4 class="text-sm font-semibold text-slate-900">Enable Maintenance Mode</h4>
                                                 <p class="text-xs text-slate-500 mt-1">When active, students will see a maintenance page. Admins bypass this.</p>
                                             </div>
                                         </div>
                                         <div class="flex items-center gap-3">
                                             <div x-show="loading" class="animate-spin text-red-500 text-[10px]">
                                                 <i class="fas fa-circle-notch"></i>
                                             </div>
                                             <button 
                                             type="button"
                                             @click="enabled = !enabled; toggle()"
                                             :class="enabled ? 'bg-red-500' : 'bg-slate-200'"
                                             class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:ring-offset-2"
                                             role="switch"
                                             aria-label="Maintenance Mode"
                                             :aria-checked="enabled.toString()"
                                         >
                                             <span 
                                                 aria-hidden="true"
                                                 :class="enabled ? 'translate-x-5' : 'translate-x-0'"
                                                 class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-lg ring-0 transition duration-300 ease-in-out"
                                             ></span>
                                         </button>
                                         </div>
                                     </div>
                        </div>

                        <!-- Sticky Footer Actions -->
                        <div class="sticky bottom-0 -mx-6 md:-mx-8 px-6 md:px-8 py-6 bg-white/80 backdrop-blur-md border-t border-slate-50 flex justify-end z-20 mt-8">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all shadow-xl shadow-indigo-600/20 flex items-center gap-3 hover:scale-[1.02] active:scale-[0.98]">
                                <i class="fas fa-check-circle text-white/50"></i> Save Configuration
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
                        <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-6 md:p-8 transition-all duration-300 hover:shadow-md hover:border-slate-200/60">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-500/20">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold tracking-tight text-slate-900">Default Architecture</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Pre-filled values for new assessments</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Default Time Limit</label>
                                    <div class="relative flex items-center">
                                        <input type="number" name="default_time_limit" value="{{ old('default_time_limit', $settings['default_time_limit'] ?? 30) }}" min="1" max="300" class="w-full pr-16 pl-4 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 font-medium">
                                        <span class="absolute right-4 text-xs font-semibold text-slate-400">MINS</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Pass Threshold</label>
                                    <div class="relative flex items-center">
                                        <input type="number" name="default_pass_percentage" value="{{ old('default_pass_percentage', $settings['default_pass_percentage'] ?? 60) }}" min="1" max="100" class="w-full pr-12 pl-4 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 font-medium">
                                        <span class="absolute right-4 text-xs font-semibold text-slate-400">%</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Max Attempts</label>
                                    <div class="relative flex items-center">
                                        <input type="number" name="max_attempts" value="{{ old('max_attempts', $settings['max_attempts'] ?? 1) }}" min="1" max="99" class="w-full pl-4 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 font-medium">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Display Logic -->
                        <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-6 md:p-8 transition-all duration-300 hover:shadow-md hover:border-slate-200/60">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-500/20">
                                    <i class="fas fa-random"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold tracking-tight text-slate-900">Randomization & Delivery</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Control how questions are displayed to students</p>
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
                                        <button 
                                             type="button"
                                             @click="enabled = !enabled; toggle()"
                                             :class="enabled ? 'bg-emerald-500' : 'bg-slate-200'"
                                             class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:ring-offset-2"
                                             role="switch"
                                             aria-label="{{ $toggle['title'] }}"
                                             :aria-checked="enabled.toString()"
                                         >
                                             <span 
                                                 aria-hidden="true"
                                                 :class="enabled ? 'translate-x-5' : 'translate-x-0'"
                                                 class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-lg ring-0 transition duration-300 ease-in-out"
                                             ></span>
                                         </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sticky Footer Actions -->
                        <div class="sticky bottom-0 -mx-6 md:-mx-8 px-6 md:px-8 py-4 bg-white/80 backdrop-blur-md border-t border-slate-100 flex justify-end z-20 mt-8">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-semibold text-sm transition-all duration-300 ease-in-out shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:scale-[1.02] active:scale-[0.98]">
                                <i class="fas fa-check-circle"></i> Update Assessment Rules
                            </button>
                        </div>
                    </form>
                </div>

                <!-- SECURITY TAB -->
                <div x-show="tab === 'security'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="_tab" value="security">

                        <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-6 md:p-8 transition-all duration-300 hover:shadow-md hover:border-slate-200/60">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-600 flex items-center justify-center shrink-0 border border-red-500/20">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold tracking-tight text-slate-900">Proctoring & Integrity</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Enforce strict rules during quiz attempts</p>
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
                                        <div :class="enabled ? 'bg-red-500/10 text-red-600' : 'bg-slate-100 text-slate-400'" class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors duration-300">
                                            <i class="fas {{ $toggle['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-900">{{ $toggle['title'] }}</h4>
                                            <p class="text-xs text-slate-500 mt-1">{{ $toggle['desc'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div x-show="loading" class="animate-spin text-red-500 text-[10px]">
                                            <i class="fas fa-circle-notch"></i>
                                        </div>
                                         <button 
                                             type="button"
                                             @click="enabled = !enabled; toggle()"
                                             :class="enabled ? 'bg-red-500' : 'bg-slate-200'"
                                             class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:ring-offset-2"
                                             role="switch"
                                             aria-label="{{ $toggle['title'] }}"
                                             :aria-checked="enabled.toString()"
                                         >
                                             <span 
                                                 aria-hidden="true"
                                                 :class="enabled ? 'translate-x-5' : 'translate-x-0'"
                                                 class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-lg ring-0 transition duration-300 ease-in-out"
                                             ></span>
                                         </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-8 border-t border-slate-200 pt-8">
                                <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase mb-3">Violation Threshold</label>
                                <div class="relative flex items-center max-w-xs">
                                    <input type="number" name="max_violations" value="{{ old('max_violations', $settings['max_violations'] ?? 3) }}" min="1" max="20" class="w-full pl-4 pr-12 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all duration-300 outline-none text-sm text-slate-800 font-medium">
                                    <span class="absolute right-4 text-xs font-semibold text-slate-400">Strikes</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">After this number of infractions, the test will be invalidated or auto-submitted.</p>
                            </div>
                        </div>

                        <!-- Sticky Footer Actions -->
                        <div class="sticky bottom-0 -mx-6 md:-mx-8 px-6 md:px-8 py-4 bg-white/80 backdrop-blur-md border-t border-slate-100 flex justify-end z-20 mt-8">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-semibold text-sm transition-all duration-300 ease-in-out shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:scale-[1.02] active:scale-[0.98]">
                                <i class="fas fa-check-circle"></i> Update Security Policy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
