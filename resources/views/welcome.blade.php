<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Quiz System - Smart Assessment</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Next-gen synchronized assessment platform for educational institutions. Built for scale, ease of use, and performance.">
    <meta name="keywords" content="quiz platform, learning management, assessments, education">
    
    <!-- Preload Assets -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
              display: ['Outfit', 'sans-serif'],
            },
            colors: {
              indigo: {
                50: '#eef2ff',
                100: '#e0e7ff',
                600: '#4f46e5',
                700: '#4338ca',
                900: '#312e81',
              },
              slate: {
                950: '#020617',
              }
            }
          }
        }
      }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .text-glow {
            text-shadow: 0 0 20px rgba(79, 70, 229, 0.4);
        }
    </style>
</head>
<body class="font-sans bg-white text-slate-900 selection:bg-indigo-500 selection:text-white overflow-x-hidden" 
      x-data="{ mobileMenu: false, scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 20">

    <!-- Update Badge (Floating Top-Right) -->
    <div class="fixed top-24 right-6 z-[60] hidden lg:block">
        <a href="#" class="inline-flex items-center gap-2 bg-emerald-500/90 text-white px-4 py-1.5 rounded-full text-xs font-bold backdrop-blur-md shadow-lg shadow-emerald-500/20 border border-emerald-400/30 hover:scale-105 transition-all">
            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
            🎉 Now with AI Question Generator
        </a>
    </div>

    <!-- Navigation Header -->
    <header class="fixed top-0 left-0 right-0 z-[100] transition-all duration-300" 
            :class="scrolled ? 'bg-white/80 backdrop-blur-xl border-b border-slate-200 h-20 shadow-sm' : 'bg-transparent h-24'">
        <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-600/20 group-hover:scale-105 transition-transform">
                    <i class="fas fa-graduation-cap text-white text-lg"></i>
                </div>
                <span class="font-display font-bold text-xl tracking-tight" :class="scrolled ? 'text-slate-900' : 'text-white'">Quiz System</span>
            </a>
            
            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-semibold hover:text-indigo-600 transition-colors" :class="scrolled ? 'text-slate-600' : 'text-white/80'">Features</a>
                <a href="#how-it-works" class="text-sm font-semibold hover:text-indigo-600 transition-colors" :class="scrolled ? 'text-slate-600' : 'text-white/80'">How it Works</a>
                <a href="#pricing" class="text-sm font-semibold hover:text-indigo-600 transition-colors" :class="scrolled ? 'text-slate-600' : 'text-white/80'">Pricing</a>
                <a href="#testimonials" class="text-sm font-semibold hover:text-indigo-600 transition-colors" :class="scrolled ? 'text-slate-600' : 'text-white/80'">About</a>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-4">
                @if (Auth::check())
                    <a href="{{ url('/dashboard') }}" class="h-11 px-6 bg-indigo-600 text-white rounded-xl text-sm font-bold flex items-center shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 hover:shadow-indigo-600/40 transition-all active:scale-95">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:block text-sm font-bold hover:text-indigo-600 transition-colors" :class="scrolled ? 'text-slate-600' : 'text-white/90'">Log In</a>
                    <a href="{{ route('register') }}" class="h-11 px-6 bg-white text-slate-900 rounded-xl text-sm font-bold flex items-center shadow-lg shadow-black/5 hover:bg-indigo-50 hover:shadow-xl transition-all active:scale-95" :class="scrolled ? 'bg-indigo-600 !text-white' : ''">Start Free Trial</a>
                @endif
                <!-- Mobile Toggle -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden w-10 h-10 flex items-center justify-center text-xl" :class="scrolled ? 'text-slate-900' : 'text-white'">
                    <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars-staggered'"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" x-cloak x-transition.opacity class="fixed inset-0 z-[90] bg-slate-900/95 backdrop-blur-lg md:hidden">
        <div class="flex flex-col items-center justify-center h-full gap-8 text-center">
            <a @click="mobileMenu = false" href="#features" class="text-2xl font-bold text-white">Features</a>
            <a @click="mobileMenu = false" href="#how-it-works" class="text-2xl font-bold text-white">How it Works</a>
            <a @click="mobileMenu = false" href="#pricing" class="text-2xl font-bold text-white">Pricing</a>
            <a @click="mobileMenu = false" href="{{ route('login') }}" class="text-2xl font-bold text-white">Log In</a>
            <a @click="mobileMenu = false" href="{{ route('register') }}" class="h-14 px-10 bg-indigo-600 text-white rounded-2xl text-lg font-bold flex items-center">Start Free Trial</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-32 pb-20 px-6 overflow-hidden hero-bg">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-950/90 to-slate-950 pointer-events-none"></div>
        
        <div class="max-w-6xl mx-auto relative z-10 text-center">
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-display font-extrabold text-white tracking-tight leading-[1.1] mb-8 [text-shadow:_0_4px_12px_rgb(0_0_0_/_50%)]">
                Create Quizzes in Minutes.<br/>
                <span class="bg-gradient-to-r from-indigo-400 via-indigo-200 to-white bg-clip-text text-transparent">Grade in Seconds.</span>
            </h1>
            
            <p class="text-indigo-100/70 text-lg md:text-xl font-medium leading-relaxed max-w-2xl mx-auto mb-14 px-4">
                Save 20+ hours per month with automated grading, reusable question banks, and instant analytics.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-5 px-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto h-16 px-10 bg-white text-slate-950 rounded-2xl font-extrabold text-base flex items-center justify-center gap-3 shadow-2xl shadow-indigo-500/10 hover:bg-indigo-50 hover:scale-105 transition-all active:scale-[0.98]">
                    Start Free Trial <i class="fas fa-arrow-right text-sm"></i>
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto h-16 px-10 border-2 border-white/20 bg-white/5 text-white rounded-2xl font-extrabold text-base flex items-center justify-center gap-3 backdrop-blur-md hover:bg-white/10 hover:border-white transition-all active:scale-[0.98]">
                    Log In <i class="far fa-user text-sm"></i>
                </a>
            </div>

            <!-- Floating Stats (Hero Bottom) -->
            <div class="mt-20 flex flex-wrap justify-center gap-10 opacity-60">
                <div class="flex items-center gap-3"><i class="fas fa-shield-halved text-indigo-400"></i> <span class="text-sm font-bold tracking-widest uppercase text-white/80">Secured Platform</span></div>
                <div class="flex items-center gap-3"><i class="fas fa-bolt text-amber-400"></i> <span class="text-sm font-bold tracking-widest uppercase text-white/80">Real-time Results</span></div>
                <div class="flex items-center gap-3"><i class="fas fa-globe text-sky-400"></i> <span class="text-sm font-bold tracking-widest uppercase text-white/80">Global Standard</span></div>
            </div>
        </div>
    </section>

    <!-- Section A: Stats Bar -->
    <section class="relative z-20 py-16 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12 text-center">
                <div class="space-y-2">
                    <h3 class="text-4xl font-display font-black text-slate-900 tracking-tight">10,000+</h3>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fas fa-clock text-indigo-600"></i> Hours Saved by Educators
                    </p>
                </div>
                <div class="space-y-2">
                    <h3 class="text-4xl font-display font-black text-slate-900 tracking-tight">500+</h3>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fas fa-building text-indigo-600"></i> Universities Worldwide
                    </p>
                </div>
                <div class="space-y-2">
                    <h3 class="text-4xl font-display font-black text-slate-900 tracking-tight">99.9%</h3>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fas fa-check-double text-indigo-600"></i> Never Miss Exam Day
                    </p>
                </div>
                <div class="space-y-2">
                    <h3 class="text-4xl font-display font-black text-slate-900 tracking-tight">4.8★</h3>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fas fa-star text-indigo-600"></i> 2,000+ Instructors
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section B: Key Features -->
    <section id="features" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-sm font-black text-indigo-600 uppercase tracking-[0.3em] mb-4">Core Ecosystem</h2>
            <h3 class="text-4xl md:text-5xl font-display font-extrabold text-slate-950 tracking-tight mb-16">Powerful features for<br>modern educators.</h3>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-10 bg-white rounded-[32px] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-left group">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">Build Once, Reuse Forever</h4>
                    <p class="text-slate-500 leading-relaxed">Build complex assessments in minutes with our intuitive drag-and-drop interface.</p>
                </div>
                <!-- Feature 2 -->
                <div class="p-10 bg-white rounded-[32px] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-left group">
                    <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-sky-600 group-hover:text-white transition-colors">
                        <i class="fas fa-bolt-lightning"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">Grade 100 Students in 10 Seconds</h4>
                    <p class="text-slate-500 leading-relaxed">Instant feedback for students and automated grading for teachers to save hours of work.</p>
                </div>
                <!-- Feature 3 -->
                <div class="p-10 bg-white rounded-[32px] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-left group">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">Spot At-Risk Students Early</h4>
                    <p class="text-slate-500 leading-relaxed">Track student performance with deep visual analytics and exportable CSV/Excel reports.</p>
                </div>
                <!-- Feature 4 -->
                <div class="p-10 bg-white rounded-[32px] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-left group">
                    <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <i class="fas fa-database"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">Never Write the Same Question Twice</h4>
                    <p class="text-slate-500 leading-relaxed">Organize your questions by category and difficulty levels for quick quiz building.</p>
                </div>
                <!-- Feature 5 -->
                <div class="p-10 bg-white rounded-[32px] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-left group">
                    <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">Students Get Instant Feedback</h4>
                    <p class="text-slate-500 leading-relaxed">A clean, focused environment for students to take exams without distractions.</p>
                </div>
                <!-- Feature 6 -->
                <div class="p-10 bg-white rounded-[32px] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-left group">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-cloud-arrow-down"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-4">Export Data for Accreditation</h4>
                    <p class="text-slate-500 leading-relaxed">Generated PDF and Excel certificates and results with one-click functionality.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section C: How It Works -->
    <section id="how-it-works" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-20">
                <div class="lg:w-1/2">
                    <h2 class="text-sm font-black text-indigo-600 uppercase tracking-[0.3em] mb-4">Simple Workflow</h2>
                    <h3 class="text-4xl font-display font-extrabold text-slate-950 tracking-tight mb-10">From creation to analytics in three steps.</h3>
                    
                    <div class="space-y-12">
                        <div class="flex gap-6">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold shrink-0 shadow-lg shadow-indigo-600/20">1</div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-950 mb-2">Create your quiz</h4>
                                <p class="text-slate-500">Design questions, set time limits, and configure passing grades in our editor.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="w-12 h-12 bg-slate-100 text-slate-900 rounded-full flex items-center justify-center font-bold shrink-0">2</div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-950 mb-2">Share with students</h4>
                                <p class="text-slate-500">Generate unique codes or links and distribute them to your class or department.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="w-12 h-12 bg-slate-100 text-slate-900 rounded-full flex items-center justify-center font-bold shrink-0">3</div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-950 mb-2">Auto-grade & Analyze</h4>
                                <p class="text-slate-500">Sit back as the system grades everything instantly and provides deep insights.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <div class="absolute -inset-4 bg-indigo-600/5 rounded-[40px] rotate-3 -z-10"></div>
                    <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&q=80&w=2070" 
                         alt="App Preview" class="rounded-[32px] shadow-2xl border border-slate-200">
                </div>
            </div>
        </div>
    </section>

    <!-- Section D: Testimonials -->
    <section id="testimonials" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-sm font-black text-indigo-600 uppercase tracking-[0.3em] mb-4">Social Proof</h2>
            <h3 class="text-4xl font-display font-extrabold text-slate-950 tracking-tight mb-16">Trusted by leading educators.</h3>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="p-10 bg-white rounded-[32px] border border-slate-200 text-left">
                    <div class="flex text-amber-400 gap-1 mb-6">
                        <i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i>
                    </div>
                    <p class="text-slate-700 font-medium leading-relaxed mb-10 italic">"This platform cut my grading time from 6 hours to 20 minutes. I can finally focus on teaching instead of paperwork."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/100?u=4" class="w-12 h-12 rounded-full border border-slate-200">
                        <div>
                            <h5 class="text-sm font-bold text-slate-900">Dr. Michael Torres</h5>
                            <p class="text-xs font-semibold text-slate-400 uppercase">Associate Professor, UCLA</p>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 2 -->
                <div class="p-10 bg-white rounded-[32px] border border-slate-200 text-left">
                    <div class="flex text-amber-400 gap-1 mb-6">
                        <i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i>
                    </div>
                    <p class="text-slate-700 font-medium leading-relaxed mb-10 italic">"Our department created a shared question bank with 5,000+ items. It's transformed how we standardize assessments."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/100?u=5" class="w-12 h-12 rounded-full border border-slate-200">
                        <div>
                            <h5 class="text-sm font-bold text-slate-900">Prof. Lisa Chen</h5>
                            <p class="text-xs font-semibold text-slate-400 uppercase">Computer Science Dept Chair, MIT</p>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 3 -->
                <div class="p-10 bg-white rounded-[32px] border border-slate-200 text-left">
                    <div class="flex text-amber-400 gap-1 mb-6">
                        <i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i>
                    </div>
                    <p class="text-slate-700 font-medium leading-relaxed mb-10 italic">"Students love getting instant feedback. My course ratings went up 30% after switching to this system."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/100?u=6" class="w-12 h-12 rounded-full border border-slate-200">
                        <div>
                            <h5 class="text-sm font-bold text-slate-900">James Anderson</h5>
                            <p class="text-xs font-semibold text-slate-400 uppercase">Lecturer, Oxford University</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section E: Final CTA -->
    <section id="pricing" class="py-24 px-6 bg-white overflow-hidden">
        <div class="max-w-6xl mx-auto rounded-[48px] bg-slate-950 p-10 md:p-24 text-center relative">
            <!-- Background effects -->
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-indigo-600/20 rounded-full blur-[100px] -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-600/10 rounded-full blur-[100px] -ml-48 -mb-48"></div>
            
            <div class="relative z-10">
                <h3 class="text-4xl md:text-6xl font-display font-extrabold text-white tracking-tight mb-8">Ready to revolutionize<br>your assessments?</h3>
                <p class="text-indigo-100/60 text-lg md:text-xl max-w-2xl mx-auto mb-14">Join 500+ institutions today. No credit card required to get started.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-5">
                    <a href="{{ route('register') }}" class="h-16 px-12 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all active:scale-95">Start Free Trial</a>
                    <a href="#" class="h-16 px-12 border-2 border-white/20 text-white rounded-2xl font-bold text-lg hover:bg-white/5 transition-all">Talk to Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-16 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-16">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <span class="font-display font-bold text-xl tracking-tight">Quiz System</span>
                </a>
                <div class="flex gap-8">
                    <a href="#" class="text-slate-400 hover:text-slate-900 transition-colors"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-slate-400 hover:text-slate-900 transition-colors"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-slate-400 hover:text-slate-900 transition-colors"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-slate-400 hover:text-slate-900 transition-colors"><i class="fab fa-github"></i></a>
                </div>
            </div>
            <div class="pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-500 text-sm font-medium">
                <p>&copy; {{ date('Y') }} Quiz System Platform. All rights reserved.</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-slate-900 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-900 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
