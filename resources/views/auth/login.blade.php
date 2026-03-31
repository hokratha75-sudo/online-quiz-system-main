<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Online Quiz System</title>
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="body">
<div class="login-container">
    <img src="{{ asset('image/1.png') }}" alt="Quiz System Logo" style="max-width: 150px; margin-bottom: 20px;">
    <h2 style="color: blue; text-align:center; font-weight:bold">Online Quiz System</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" required placeholder="Enter your username" value="{{ old('username') }}">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <!-- Forgot Password removed -->
</div>
</body>
</html>
