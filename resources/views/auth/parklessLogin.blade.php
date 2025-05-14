<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parkless Login</title>
    <link rel="stylesheet" href="{{ asset('stylelogin.css') }}">
</head>

<body>
    <div class="wrapper">
        <div class="title-text">
            <div class="title login">Login Form</div>
            <div class="title signup">Sign Up Form</div>
        </div>
        <div class="form-container">
            <div class="slide-controls">
                <input type="radio" name="slide" id="login" checked>
                <input type="radio" name="slide" id="signup">
                <label for="login" class="slide login">Login</label>
                <label for="signup" class="slide signup">Sign Up</label>
                <div class="slider-tab"></div>
            </div>
            <div class="form-inner">
                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="login">
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
                <form action="{{ route('register') }}" method="POST" class="signup">
                    @csrf
                    <div class="field">
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="field">
                        <input type="text" name="name" placeholder="Username" required>
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
    <script src="{{ asset('scriptlogin.js') }}"></script>
</body>

</html>
