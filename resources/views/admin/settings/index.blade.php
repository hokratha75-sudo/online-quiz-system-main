@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto p-6 md:p-8 space-y-8 font-inter">
    <!-- Header -->
    <div class="flex items-center gap-6">
        <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-600 shadow-sm border border-indigo-500/20 backdrop-blur-sm">
            <i class="fas fa-cogs text-xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">System Settings</h1>
            <p class="text-slate-500 leading-relaxed text-sm mt-1">Configure your QuizMaster platform preferences</p>
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
            <div class="border-b border-slate-100 px-6 md:px-8 pt-6 flex gap-8">
                <button @click="tab = 'general'" :class="tab === 'general' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'" class="pb-4 px-2 border-b-2 font-medium text-sm transition-all duration-300 ease-in-out flex items-center gap-2">
                    <i class="fas fa-globe"></i> General
                </button>
                <button @click="tab = 'quiz'" :class="tab === 'quiz' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'" class="pb-4 px-2 border-b-2 font-medium text-sm transition-all duration-300 ease-in-out flex items-center gap-2">
                    <i class="fas fa-clipboard-list"></i> Quiz Rules
                </button>
                <button @click="tab = 'security'" :class="tab === 'security' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'" class="pb-4 px-2 border-b-2 font-medium text-sm transition-all duration-300 ease-in-out flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i> Security
                </button>
            </div>

            <!-- Tab Content Area -->
            <div class="p-6 md:p-8 bg-white">

                <!-- GENERAL TAB -->
                <div x-show="tab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        <input type="hidden" name="_tab" value="general">

                        <!-- Site Identity (Bento Box) -->
                        <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-6 md:p-8 transition-all duration-300 hover:shadow-md hover:border-slate-200/60">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center shrink-0 border border-blue-500/20">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold tracking-tight text-slate-900">Site Identity</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Platform branding and localization</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Site Name</label>
                                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'QuizMaster v2.0') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 placeholder-slate-400 font-medium" placeholder="e.g. QuizMaster v2.0">
                                    <p class="text-[11px] text-slate-500">Appears in the sidebar and emails.</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Institution Name</label>
                                    <input type="text" name="institution_name" value="{{ old('institution_name', $settings['institution_name'] ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 placeholder-slate-400 font-medium" placeholder="e.g. My University">
                                    <p class="text-[11px] text-slate-500">Your school or organization.</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Contact Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 placeholder-slate-400 font-medium" placeholder="admin@example.com">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-semibold tracking-wide text-slate-600 uppercase">Timezone</label>
                                    <select name="timezone" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 outline-none text-sm text-slate-800 font-medium appearance-none">
                                        @php
                                            $currentTz = $settings['timezone'] ?? 'Asia/Phnom_Penh';
                                            $timezones = [
                                                'Asia/Phnom_Penh' => 'Asia/Phnom_Penh (GMT+7)',
                                                'Asia/Bangkok' => 'Asia/Bangkok (GMT+7)',
                                                'Asia/Jakarta' => 'Asia/Jakarta (GMT+7)',
                                                'Asia/Singapore' => 'Asia/Singapore (GMT+8)',
                                                'Asia/Manila' => 'Asia/Manila (GMT+8)',
                                                'Asia/Tokyo' => 'Asia/Tokyo (GMT+9)',
                                                'UTC' => 'UTC (GMT+0)',
                                            ];
                                        @endphp
                                        @foreach($timezones as $tz => $label)
                                            <option value="{{ $tz }}" {{ old('timezone', $currentTz) === $tz ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Site Logo (Bento Box) -->
                        <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-6 md:p-8 transition-all duration-300 hover:shadow-md hover:border-slate-200/60">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 text-orange-600 flex items-center justify-center shrink-0 border border-orange-500/20">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold tracking-tight text-slate-900">Site Logo</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Brand icon (PNG, JPG, SVG — max 2MB)</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                                <div class="w-[180px] h-[90px] rounded-xl border-2 border-dashed border-slate-200 bg-white flex items-center justify-center p-2 shrink-0 overflow-hidden relative group">
                                    @if(!empty($settings['site_logo']))
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Site Logo" class="max-w-full max-h-full object-contain z-10">
                                        <div class="absolute inset-0 bg-slate-900/5 opacity-0 group-hover:opacity-100 transition-opacity z-20"></div>
                                    @else
                                        <div class="text-center">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-slate-300"></i>
                                            <p class="text-[10px] text-slate-400 mt-1 font-medium">No Logo</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="w-full space-y-3">
                                    <input type="file" name="site_logo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer @error('site_logo') border-red-500 @enderror" accept="image/*,.svg">
                                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm">Recommended format: 200×60px transparent PNG or SVG for best resolution across retina screens.</p>
                                    @error('site_logo')
                                        <div class="text-xs font-semibold text-red-500 flex items-center gap-1.5 mt-2">
                                            <i class="fas fa-info-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

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

                            <div class="flex items-center justify-between bg-white border border-slate-200 p-5 rounded-xl">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">Enable Maintenance Mode</h4>
                                    <p class="text-xs text-slate-500 mt-1">When active, students will see a maintenance page. Admins bypass this.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" {{ old('maintenance_mode', (string) ($settings['maintenance_mode'] ?? '0')) === '1' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-medium text-sm transition-all duration-300 ease-in-out shadow-sm shadow-indigo-500/20 flex items-center gap-2">
                                <i class="fas fa-save"></i> Save General Settings
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
                                        ['name' => 'shuffle_questions', 'title' => 'Shuffle Questions', 'desc' => 'Randomize the order of questions for every attempt.', 'value' => old('shuffle_questions', $settings['shuffle_questions'] ?? '1')],
                                        ['name' => 'shuffle_answers', 'title' => 'Shuffle Answers', 'desc' => 'Randomize the order of answer choices for each question.', 'value' => old('shuffle_answers', $settings['shuffle_answers'] ?? '1')],
                                        ['name' => 'show_result_immediately', 'title' => 'Instant Results', 'desc' => 'Students see their score right after submitting.', 'value' => old('show_result_immediately', $settings['show_result_immediately'] ?? '1')],
                                        ['name' => 'allow_review', 'title' => 'Post-Quiz Review', 'desc' => 'Let students review correct answers after the quiz ends.', 'value' => old('allow_review', $settings['allow_review'] ?? '1')]
                                    ];
                                @endphp
                                @foreach($toggles as $toggle)
                                <div class="flex items-center justify-between bg-white border border-slate-200 p-5 rounded-xl hover:border-slate-300 transition-colors">
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-900">{{ $toggle['title'] }}</h4>
                                        <p class="text-xs text-slate-500 mt-1">{{ $toggle['desc'] }}</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                                        <input type="checkbox" name="{{ $toggle['name'] }}" value="1" class="sr-only peer" {{ (string)$toggle['value'] === '1' ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-medium text-sm transition-all duration-300 ease-in-out shadow-sm shadow-indigo-500/20 flex items-center gap-2">
                                <i class="fas fa-save"></i> Save Assessment Rules
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
                                        ['name' => 'disable_right_click', 'title' => 'Disable Context Menus', 'desc' => 'Prevents students from right-clicking or copying questions.', 'value' => old('disable_right_click', $settings['disable_right_click'] ?? '1')],
                                        ['name' => 'tab_switch_detection', 'title' => 'Browser Focus Detection', 'desc' => 'Records a violation when a student leaves the quiz tab.', 'value' => old('tab_switch_detection', $settings['tab_switch_detection'] ?? '1')],
                                        ['name' => 'enforce_fullscreen', 'title' => 'Enforce Fullscreen', 'desc' => 'Forces the browser into fullscreen mode. Exiting creates a violation.', 'value' => old('enforce_fullscreen', $settings['enforce_fullscreen'] ?? '0')],
                                        ['name' => 'auto_submit_on_violation', 'title' => 'Auto-Submit on Breach', 'desc' => 'Automatically end the quiz when violation threshold is reached.', 'value' => old('auto_submit_on_violation', $settings['auto_submit_on_violation'] ?? '0')]
                                    ];
                                @endphp
                                @foreach($secToggles as $toggle)
                                <div class="flex items-center justify-between bg-white border border-slate-200 p-5 rounded-xl hover:border-slate-300 transition-colors">
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-900">{{ $toggle['title'] }}</h4>
                                        <p class="text-xs text-slate-500 mt-1">{{ $toggle['desc'] }}</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                                        <input type="checkbox" name="{{ $toggle['name'] }}" value="1" class="sr-only peer" {{ (string)$toggle['value'] === '1' ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                                    </label>
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

                        <!-- Footer Actions -->
                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-medium text-sm transition-all duration-300 ease-in-out shadow-sm shadow-indigo-500/20 flex items-center gap-2">
                                <i class="fas fa-save"></i> Save Security Policy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
