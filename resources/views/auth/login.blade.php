<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <link rel="icon" href="{{ asset('images/Logo_Green.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23C9D423" fill-opacity="0.1" d="M0,224L48,208C96,192,192,160,288,165.3C384,171,480,213,576,208C672,203,768,149,864,144C960,139,1056,181,1152,186.7C1248,192,1344,160,1392,144L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-repeat: no-repeat;
            background-position: bottom;
            background-size: contain;
        }
        
        .login-container {
            display: flex;
            width: 900px;
            max-width: 100%;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-radius: 20px;
            overflow: hidden;
            background: white;
        }
        
        .login-image {
            flex: 1;
            background: linear-gradient(135deg, #c9d423 0%, #a6af1d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
            flex-direction: column;
        }
        
        .login-image::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .login-image::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .login-logo {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 200px;
            margin-bottom: 10px;
        }
        
        .brand-name {
            position: relative;
            z-index: 1;
            color: #333;
            font-size: 32px;
            font-weight: 700;
            margin-top: 20px;
            text-align: center;
            letter-spacing: 1px;
        }
        
        .brand-tagline {
            position: relative;
            z-index: 1;
            color: white;
            font-size: 16px;
            font-weight: 400;
            margin-top: 10px;
            text-align: center;
            opacity: 0.8;
        }
        
        .login-form {
            flex: 1;
            padding: 40px;
            position: relative;
        }
        
        h2 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .input-group input:focus {
            border-color: #c9d423;
            box-shadow: 0 0 0 3px rgba(201, 212, 35, 0.2);
            outline: none;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .remember-me label {
            font-size: 14px;
            color: #666;
        }
        
        .forgot-password {
            font-size: 14px;
            color: #666;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #c9d423;
        }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #c9d423;
            color: #333;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .login-btn:hover {
            background-color: #b9c31e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(201, 212, 35, 0.3);
        }
        
        .error-message {
            background-color: #fee;
            color: #e53e3e;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .status-message {
            background-color: #e6f7ff;
            color: #0072b1;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 90%;
            }
            
            .login-image {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <img src="{{ asset('images/Logo_White.png') }}" alt="TheRoom19 Logo" class="login-logo">
            <p class="brand-tagline">Reminder</p>
        </div>
        <div class="login-form">
            <h2>Welcome Back!</h2>
            
    <!-- Session Status -->
            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                    @error('email')
                        <div class="error" style="color: #e53e3e; font-size: 12px; margin-top: 5px;">
                            {{ $message }}
                        </div>
                    @enderror
        </div>

        <!-- Password -->
                <div class="input-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" />
                    @error('password')
                        <div class="error" style="color: #e53e3e; font-size: 12px; margin-top: 5px;">
                            {{ $message }}
                        </div>
                    @enderror
        </div>

                <!-- Remember Me & Forgot Password -->
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Remember me</label>
        </div>
            @if (Route::has('password.request'))
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            Forgot your password?
                </a>
            @endif
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt mr-2"></i> Log in
                </button>
            </form>
        </div>
    </div>
</body>
</html>
