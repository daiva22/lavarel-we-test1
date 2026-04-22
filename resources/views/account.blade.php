<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | AUTOTECH</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('/images/account_Background.png') no-repeat center center;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .account-hero {
            min-height: 100vh;
            padding: 30px 20px;
            box-sizing: border-box;
        }

        .auth-container {
            max-width: 450px;
            margin: 80px auto 0;
        }

        .auth-card {
            background: #1c1c1c;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .auth-card h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .auth-card input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
            background: #fff;
            color: #111;
        }

        .auth-btn {
            width: 100%;
            padding: 14px;
            background: #e63946;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .auth-btn:hover {
            background: #c1121f;
        }

        .switch {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .switch a {
            color: #e63946;
            text-decoration: none;
            font-weight: bold;
        }

        .alert-success {
            background: #198754;
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #dc3545;
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .field-error {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: -8px;
            margin-bottom: 12px;
        }

        .logged-in-box {
            background: #1c1c1c;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
        }

        .logout-form {
            margin-top: 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 16px;
            padding: 0;
            margin: 0;
        }

        .nav-links a,
        .nav-icons a,
        .logo a {
            color: #fff;
            text-decoration: none;
        }

        .nav-icons {
            display: flex;
            gap: 12px;
        }
    </style>
</head>
<body>

<section class="account-hero">

    <nav class="navbar">
        <div class="logo">
            <a href="{{ url('/#home') }}">AUTOTECH</a>
        </div>

        <ul class="nav-links">
            <li><a href="{{ url('/#home') }}">Home</a></li>
            <li><a href="{{ url('/#shop') }}">Shop</a></li>
            <li><a href="{{ url('/#services') }}">Services</a></li>
            <li><a href="{{ url('/booking') }}">Booking</a></li>
            <li><a href="{{ url('/reviews') }}">Reviews</a></li>
        </ul>

        <div class="nav-icons">
            
            <a href="{{ url('/account') }}" class="active" title="Account">👤</a>
            <a href="{{ url('/cart') }}" title="Cart">🛒</a>
        </div>
    </nav>

    <div class="auth-container">

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        @auth
            <div class="logged-in-box">
                <h2>You are logged in</h2>

                <p style="margin-top: 15px;">Welcome, {{ auth()->user()->email }}</p>
                <p style="margin-top: 10px;">Role: {{ auth()->user()->role }}</p>

                @if(auth()->user()->role === 'admin')
                    <div style="margin-top: 20px;">
                        <a href="{{ route('admin.dashboard') }}" class="auth-btn" style="display:inline-block; text-decoration:none; line-height: 50px;">
                            Go to Admin Dashboard
                        </a>
                    </div>
                @else
                    <div style="margin-top: 20px;">
                        <a href="{{ route('booking') }}" class="auth-btn" style="display:inline-block; text-decoration:none; line-height: 50px;">
                            Go to Booking Page
                        </a>
                    </div>
                @endif

                <div class="logout-form">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="auth-btn">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <div class="auth-card" id="login-box" style="{{ $errors->any() && old('form_type') === 'register' ? 'display:none;' : 'display:block;' }}">
                <h2>Login</h2>

                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="form_type" value="login">

                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        value="{{ old('form_type') === 'login' ? old('email') : '' }}"
                        required
                    >
                    @if(old('form_type') === 'login')
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    @endif

                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                    >
                    @if(old('form_type') === 'login')
                        @error('password')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    @endif

                    <button class="auth-btn" type="submit">Login</button>
                </form>

                <div class="switch">
                    Don't have an account?
                    <a href="javascript:void(0)" onclick="showRegister()">Create one</a>
                </div>
            </div>

            <div class="auth-card" id="register-box" style="{{ $errors->any() && old('form_type') === 'register' ? 'display:block;' : 'display:none;' }}">
                <h2>Register</h2>

                <form action="{{ route('register.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="form_type" value="register">

                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        value="{{ old('form_type') === 'register' ? old('email') : '' }}"
                        required
                    >
                    @if(old('form_type') === 'register')
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    @endif

                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                    >
                    @if(old('form_type') === 'register')
                        @error('password')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    @endif

                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm Password"
                        required
                    >

                    <button class="auth-btn" type="submit">Register</button>
                </form>

                <div class="switch">
                    Already have an account?
                    <a href="javascript:void(0)" onclick="showLogin()">Login here</a>
                </div>
            </div>
        @endauth

    </div>

</section>

<script>
    function showRegister() {
        document.getElementById('login-box').style.display = 'none';
        document.getElementById('register-box').style.display = 'block';
    }

    function showLogin() {
        document.getElementById('login-box').style.display = 'block';
        document.getElementById('register-box').style.display = 'none';
    }
</script>

</body>
</html>