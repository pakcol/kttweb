<x-layouts.app title="Home Database">
    <link rel="stylesheet" href="{{ asset('css/homeDatabase.css') }}">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        main {
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>

    <section class="home-database-section">
        <div class="container-fluid h-100">
            <div class="row align-items-center h-100 gx-0">
                <div class="col-lg-6 col-md-12 left-panel">
                    <div class="content-wrapper">
                        <h4 class="welcome-text">
                            Halo, selamat datang 
                            <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>!
                        </h4>
                        <h1 class="main-title">DATABASE</h1>
                        <p class="company-name">PT. Kupang Tour & Travel</p>
                        Input Tiket
                        <button class="btn-input" onclick="window.location.href='{{ route('input-tiket.index') }}'">
                        </button>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 right-panel">
                    <div class="image-wrapper">
                        <img src="{{ asset('images/ikon_6.png') }}" alt="Database Illustration" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</x-layouts.app>
