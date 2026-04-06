<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Online Quiz System</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Plus Jakarta Sans', 'Kantumruy Pro', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    
    <style>
        .login-mesh {
            background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .login-mesh::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.85), rgba(15, 23, 42, 0.9));
            z-index: 1;
        }
    </style>
</head>
<body class="font-sans bg-slate-50 min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="w-full max-w-[1100px] bg-white rounded-[40px] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-slate-100 min-h-[650px]">
        
        <!-- Branding Identity Panel -->
        <div class="w-full md:w-5/12 login-mesh p-12 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 backdrop-blur-[2px] z-[1]"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
            
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center mb-8 shadow-xl">
                    <i class="fas fa-graduation-cap text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white tracking-tight leading-tight mb-6">
                    Create <br/> <span class="text-indigo-300">Account.</span>
                </h1>
                <p class="text-indigo-100/80 text-sm font-medium leading-relaxed max-w-sm">
                    Create your account to start your learning journey and access world-class assessment materials.
                </p>
            </div>

            <div class="relative z-10 pt-12">
                <div class="flex items-center gap-4 py-4 border-t border-white/10">
                    <div class="flex -space-x-3">
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-600 bg-slate-800 flex items-center justify-center text-[10px] font-bold text-white shadow-xl">AD</div>
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-600 bg-indigo-500/20 backdrop-blur-md flex items-center justify-center text-[10px] font-bold text-white shadow-xl">+</div>
                    </div>
                    <span class="text-xs font-semibold text-indigo-50 tracking-wide">Self-registration Active</span>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="w-full md:w-7/12 p-8 md:p-12 lg:p-16 flex flex-col justify-center bg-white overflow-y-auto custom-scrollbar">
            <div class="max-w-xl mx-auto w-full">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create Account</h2>
                    <p class="text-sm font-medium text-slate-500 mt-2">Fill in your details below to proceed</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-exclamation-circle text-xs"></i>
                        </div>
                        <div class="text-sm font-medium text-rose-700 leading-relaxed pt-1">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-semibold text-slate-700 mb-1 ml-1">First Name</label>
                            <input type="text" id="first_name" name="first_name" required 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-400" 
                                   placeholder="First Name" value="{{ old('first_name') }}">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-semibold text-slate-700 mb-1 ml-1">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-400" 
                                   placeholder="Last Name" value="{{ old('last_name') }}">
                        </div>
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-semibold text-slate-700 mb-1 ml-1">Username</label>
                        <input type="text" id="username" name="username" required 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-400" 
                               placeholder="e.g. johndoe" value="{{ old('username') }}">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1 ml-1">Email Address</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-400" 
                               placeholder="example@student.edu" value="{{ old('email') }}">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1 ml-1">Password</label>
                            <input type="password" id="password" name="password" required 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-400" 
                                   placeholder="••••••••">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1 ml-1">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-400" 
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white h-14 rounded-xl font-semibold text-sm transition-all shadow-md active:scale-[0.98] flex items-center justify-center gap-2">
                            Create Account <i class="fas fa-arrow-right text-xs opacity-70"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm font-medium text-slate-500">
                        Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">Sign in here</a>
                    </p>
                    <p class="text-xs text-slate-400 font-medium mt-3">
                        Version {{ config('app.version', '2.5.0') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
