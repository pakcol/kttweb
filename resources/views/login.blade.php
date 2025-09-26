{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT. Kupang Tour & Travel</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="left-panel">
            <img src="{{ asset('images/logoKTTputih.png') }}" alt="Logo KTT" class="logo">
            <h4>Halo, selamat datang</h4>
            <h1>LOGIN <br> DATABASE</h1>
        </div>

        <div class="right-panel">
            <div class="login-box">
                <h2>LOGIN</h2>
                <p class="subtitle">DATABASE PT. KUPANG TOUR & TRAVEL</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                        <img src="{{ asset('images/userLogin.png') }}" class="icon" alt="user">
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="input-group">
                        <img src="{{ asset('images/userPass.png') }}" class="icon" alt="password">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn-login">Login Now</button>
                </form>

                <div class="extra">
                    <span>Forget Password?</span>
                    <a href="#">Change Password</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
