<x-layouts.app title="Home Database">
    <link rel="stylesheet" href="{{ asset('css/homeDatabase.css') }}">

    <style>
html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    min-height: 100%;
    overflow-x: hidden;
}

main {
    padding: 0 !important;
    margin: 0 !important;
}
</style>
</style>

    <section class="home-database-section">
        <div class="home-db-wrapper">
            <!-- LEFT -->
            <div class="left-panel">
                <div class="content-wrapper">
                    <h4 class="welcome-text">
                        Halo, selamat datang
                        <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>!
                    </h4>

                    <h1 class="main-title">DATABASE</h1>
                    <p class="company-name">PT. Kupang Tour & Travel</p>

                    <button class="btn-input"
                            onclick="window.location.href='{{ route('input-tiket.index') }}'">
                        Input Tiket
                    </button>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="right-panel">
                <div class="image-wrapper">
                    <img src="{{ asset('images/ikon_6.png') }}"
                         alt="Database Illustration">
                </div>
            </div>
        </div>
    </section>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</x-layouts.app>