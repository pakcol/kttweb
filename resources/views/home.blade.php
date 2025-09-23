<x-layouts.app title="Home">
    <section class="d-flex flex-column justify-content-center align-items-center text-white text-center"
             style="background: url('{{ asset('images/hero_1.png') }}') no-repeat center center fixed;
                    background-size: cover;
                    width: 100vw;
                    height: 100vh;
                    margin: 0;
                    padding: 0;">

        <div class="bg-dark bg-opacity-50 w-100 h-100 position-absolute top-0 start-0"></div>

        <div class="position-relative">
            <p class="lead">Halo, selamat datang</p>
            <h1 class="display-3 fw-bold">PT. KUPANG TOUR & TRAVEL</h1>
            <a href="#services" class="btn btn-primary btn-lg mt-3">Cek Info Penerbangan</a>
        </div>
    </section>

    <div class="container py-5">
        <h2>Konten Biasa</h2>
        <p>Bagian ini tanpa background.</p>
    </div>
</x-layouts.app>
