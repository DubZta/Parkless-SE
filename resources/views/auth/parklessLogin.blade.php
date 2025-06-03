<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parkless Login</title>
    <link rel="stylesheet" href="{{ asset('stylelogin.css') }}">
    <style>
        .back-to-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #1a75ff;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 16px;
        }
        .back-to-home:hover {
            text-decoration: underline;
        }
        .back-arrow {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <a href="{{ route('index') }}" class="back-to-home">
            <span class="back-arrow">←</span> Back to Home
        </a>
        <div class="title-text">
            <div class="title login">Login Form</div>
            <div class="title signup">Sign Up Form</div>
        </div>
        <div class="form-container">
            <!-- Error Message -->
            @if ($errors->any())
                <div style="color: red; margin-bottom: 1rem; text-align: center;">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="slide-controls">
                <input type="radio" name="slide" id="login" checked>
                <input type="radio" name="slide" id="signup">
                <label for="login" class="slide login">Login</label>
                <label for="signup" class="slide signup">Sign Up</label>
                <div class="slider-tab"></div>
            </div>
            <div class="form-inner">
                <!-- Login Form -->
                <form action="{{ url('/parkless-login') }}" method="POST" class="login" style="display: block;">
                    @csrf
                    <div class="field">
                        <input type="email" name="email" placeholder="Email Address" required id="email">
                    </div>
                    <div class="field">
                        <input type="password" name="password" placeholder="Password" required id="password">
                    </div>
                    <div class="pass-link"><a href="{{ route('password.request') }}">Forgot password?</a></div>
                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Login">
                    </div>
                    <div class="signup-link">Not a Parkless member? <a href="#signup">Sign Up!</a></div>
                </form>

                <!-- Signup Form -->
                <form action="{{ route('register') }}" method="POST" class="signup" style="display: none;">
                    @csrf
                    <div class="field">
                        <input type="text" name="name" placeholder="Name" required>
                    </div>
                    <div class="field">
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="field">
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="field">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="field">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Sign Up">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const loginRadio = document.getElementById('login');
        const signupRadio = document.getElementById('signup');
        const loginForm = document.querySelector('form.login');
        const signupForm = document.querySelector('form.signup');

        function toggleForms() {
            if (signupRadio.checked) {
                loginForm.style.display = 'none';
                signupForm.style.display = 'block';
            } else {
                loginForm.style.display = 'block';
                signupForm.style.display = 'none';
            }
        }

        // Inisialisasi
        toggleForms();

        // Event listener
        loginRadio.addEventListener('change', toggleForms);
        signupRadio.addEventListener('change', toggleForms);
    </script>
</body>

</html>
