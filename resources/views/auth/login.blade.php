<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Quiz System</title>
    
    <!-- SEO & Performance Nodes -->
    <meta name="description" content="Secure portal for the Online Quiz System.">
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
    </style>
</head>
<body class="font-sans min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-[450px] bg-white rounded-[24px] shadow-[0_4px_24px_0_rgba(34,41,47,0.1)] p-8 md:p-10">
        
        <div class="mb-8 text-center">
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center shadow-lg shadow-primary/20">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
                <span class="text-slate-800 font-bold text-2xl tracking-tight">Quiz System</span>
            </div>
            
            <h2 class="text-xl font-semibold text-slate-800 mb-1 text-left">Welcome Back</h2>
            <p class="text-slate-500 text-sm text-left">sign-in to your account</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
                <i class="far fa-circle-exclamation text-red-500 mt-1"></i>
                <div class="text-sm text-red-600 font-bold">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="username" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">Email or Username</label>
                <input type="text" id="username" name="username" required 
                       class="w-full h-14 bg-white border border-input text-slate-800 text-sm rounded-xl px-5 transition-all outline-none form-input placeholder:text-placeholder focus:ring-4 focus:ring-primary/10" 
                       placeholder="Enter your email or username"
                       value="{{ old('username') }}">
            </div>

            <div x-data="{ show: false }">
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-xs font-semibold text-label uppercase tracking-wider">Password</label>
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-primary hover:underline">Forgot Password?</a>
                </div>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" id="password" name="password" required 
                           class="w-full h-14 bg-white border border-input text-slate-800 text-sm rounded-xl px-5 transition-all outline-none form-input placeholder:text-placeholder focus:ring-4 focus:ring-primary/10" 
                           placeholder="············">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="far" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
            
            <div class="flex items-center pt-1">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-input text-primary focus:ring-primary/20 cursor-pointer">
                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Remember Me</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full h-14 flex items-center justify-center border border-transparent text-sm font-bold rounded-2xl text-white bg-primary hover:bg-primary-dark transition-all shadow-xl shadow-primary/20 active:scale-[0.99] uppercase tracking-widest">
                    Login
                </button>
            </div>
            
            <div class="pt-4 text-center">
                <p class="text-sm text-slate-600">
                    New on our platform? <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Create an account</a>
                </p>
            </div>

        </form>
    </div>

</body>
</html>
