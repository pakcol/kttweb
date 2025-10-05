<x-layouts.app title="HomeDb">
    <section class="full-bleed hero">
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
    </section>
</x-layouts.app>

