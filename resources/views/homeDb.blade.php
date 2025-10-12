    <x-layouts.app title="Home Database">
        <!-- Main Content -->
        <section class="home-database-section">
            <div class="container-fluid h-100">
                <div class="row align-items-center h-100 gx-0">
                    <!-- Left Panel - Content -->
                    <div class="col-lg-6 col-md-12 left-panel">
                        <div class="content-wrapper">
                            <h4 class="welcome-text">
                                Halo, selamat datang 
                                <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>!
                            </h4>
                            <h1 class="main-title">DATABASE</h1>
                            <p class="company-name">PT. Kupang Tour & Travel</p>
                            <button class="btn-input" onclick="window.location.href='{{ route('input-data.index') }}'">
                                Input Data
                            </button>
                        </div>
                    </div>

                    <!-- Right Panel - Illustration -->
                    <div class="col-lg-6 col-md-12 right-panel">
                        <div class="image-wrapper">
                            <img src="{{ asset('images/ikon_6.png') }}" alt="Database Illustration" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Logout form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <!-- Link CSS External -->
        <link rel="stylesheet" href="{{ asset('css/homeDatabase.css') }}">

        <style>
            body { overflow-x: hidden; }
        </style>
    </x-layouts.app>
