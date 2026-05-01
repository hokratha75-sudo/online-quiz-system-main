<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Quiz System</title>
    
    <!-- SEO & Performance Nodes -->
    <meta name="description" content="Join the Online Quiz System.">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#007bff',
              'primary-dark': '#0069d9',
              label: '#5e5873',
              input: '#d8d6de',
              placeholder: '#b9b9c3'
            },
            fontFamily: {
              sans: ['Public Sans', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    <style>
        body {
            background-color: #f8f7fa;
        }
        .form-input:focus {
            border-color: #007bff !important;
            box-shadow: 0 3px 10px 0 rgba(0, 123, 255, 0.15);
        }
        /* Hide browser default password reveal button */
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
        input::-webkit-contacts-auto-fill-button,
        input::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            display: none !important;
            pointer-events: none;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #d8d6de;
            border-radius: 10px;
        }
    </style>
</head>
<body class="font-sans min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-[500px] bg-white rounded-[24px] shadow-[0_4px_24px_0_rgba(34,41,47,0.1)] p-8 md:p-10 max-h-[95vh] overflow-y-auto custom-scrollbar">
        
        <div class="mb-8 text-center">
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center shadow-lg shadow-primary/20">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
                <span class="text-slate-800 font-bold text-2xl tracking-tight">Quiz System</span>
            </div>
            
            <h2 class="text-xl font-semibold text-slate-800 mb-6 text-left">Create New Account</h2>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
                <i class="far fa-circle-exclamation text-red-500 mt-1"></i>
                <div class="text-sm text-red-600 font-bold">
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
                    <label for="first_name" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">First Name</label>
                    <input type="text" id="first_name" name="first_name" required 
                           class="w-full h-14 bg-white border border-input text-slate-800 text-sm rounded-xl px-5 transition-all outline-none form-input placeholder:text-placeholder focus:ring-4 focus:ring-primary/10" 
                           placeholder="Hok" value="{{ old('first_name') }}">
                </div>
                <div>
                    <label for="last_name" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required 
                           class="w-full h-14 bg-white border border-input text-slate-800 text-sm rounded-xl px-5 transition-all outline-none form-input placeholder:text-placeholder focus:ring-4 focus:ring-primary/10" 
                           placeholder="Ratha" value="{{ old('last_name') }}">
                </div>
            </div>

            <div>
                <label for="username" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">Username</label>
                <input type="text" id="username" name="username" required 
                       class="w-full h-14 bg-white border border-input text-slate-800 text-sm rounded-xl px-5 transition-all outline-none form-input placeholder:text-placeholder focus:ring-4 focus:ring-primary/10" 
                       placeholder="hokratha" value="{{ old('username') }}">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">Email</label>
                <input type="email" id="email" name="email" required 
                       class="w-full h-14 bg-white border border-input text-slate-800 text-sm rounded-xl px-5 transition-all outline-none form-input placeholder:text-placeholder focus:ring-4 focus:ring-primary/10" 
                       placeholder="hokratha@example.com" value="{{ old('email') }}">
            </div>

            <div x-data="{ show: false }">
                <label for="password" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" id="password" name="password" required 
                           class="w-full h-14 bg-white border border-input text-slate-800 text-sm rounded-xl px-5 transition-all outline-none form-input placeholder:text-placeholder focus:ring-4 focus:ring-primary/10" 
                           placeholder="············">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="far" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">Confirm Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required 
                           class="w-full h-14 bg-white border border-input text-slate-800 text-sm rounded-xl px-5 transition-all outline-none form-input placeholder:text-placeholder focus:ring-4 focus:ring-primary/10" 
                           placeholder="············">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="far" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
            
            <div class="flex items-center pt-1">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" required class="w-4 h-4 rounded border-input text-primary focus:ring-primary/20 cursor-pointer">
                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">I agree to <a href="#" class="text-primary hover:underline">privacy policy & terms</a></span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full h-14 flex items-center justify-center border border-transparent text-sm font-bold rounded-2xl text-white bg-primary hover:bg-primary-dark transition-all shadow-xl shadow-primary/20 active:scale-[0.99] uppercase tracking-widest">
                    Sign up
                </button>
            </div>
            
            <div class="pt-4 text-center">
                <p class="text-sm text-slate-600">
                    Already have an account? <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Sign in instead</a>
                </p>
            </div>

        </form>
    </div>

</body>
</html>
