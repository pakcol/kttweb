<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Database - PT. Kupang Tour & Travel</title>
    <link rel="stylesheet" href="{{ asset('css/homeDatabase.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <img src="{{ asset('images/logoKTTputih.png') }}" alt="Logo KTT">
    </div>
    <ul class="nav-links">
        <li><a href="{{ url('/homeDatabase') }}">Home</a></li>
        <li><a href="{{ url('/sub-agent') }}">Sub Agent</a></li>
        <li><a href="{{ url('/pln') }}">PLN</a></li>
        <li><a href="{{ url('/admin') }}">Admin</a></li>
        <li>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        </li>
    </ul>
</nav>

    <!-- Content -->
    <div class="container">
        <div class="left-panel">
            <h4>Halo, selamat datang <span>{{ Auth::user()->name ?? 'User' }}</span>!</h4>
            <h1>DATABASE</h1>
            <p>PT. Kupang Tour & Travel</p>
            <button class="btn-input" onclick="window.location.href='{{ url('/input-data') }}'">Input Data</button>
        </div>

        <div class="right-panel">
            <img src="{{ asset('images/ikon_6.png') }}" alt="Database Illustration">
        </div>
    </div>

    <!-- Logout form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</body>
</html>
