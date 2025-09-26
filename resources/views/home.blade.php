<x-layouts.app title="Home">
    <!-- HERO -->
    <section class="full-bleed hero">
        <div class="overlay"></div>
        <div class="content">
            <p class="lead">Halo, selamat datang</p>
            <h1 class="display-4">PT. KUPANG TOUR & TRAVEL</h1>
            {{-- Tombol login di hero --}}
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">LOGIN</a>
        </div>
    </section>

    <!-- WHAT WE DO -->
    <section class="what-section">
        <div class="container">
            <div class="row align-items-center justify-content-center g-5">
                <div class="col-md-5 text-center">
                    <img src="{{ asset('images/hero_3.png') }}" alt="hero_3" class="img-fluid" style="max-height:260px; width:auto; object-fit:contain;">
                </div>
                <div class="col-md-6">
                    <h3 class="fw-bold mb-4">What we do?</h3>
                    <ul class="what-list">
                        <li><img src="{{ asset('images/logo1.png') }}" alt="" style="height:22px; width:auto; object-fit:contain;"> Penjualan Tiket Pesawat Domestik & Internasional</li>
                        <li><img src="{{ asset('images/logo2.png') }}" alt="" style="height:22px; width:auto; object-fit:contain;"> Penjualan Tiket Kapal Laut PELNI</li>
                        <li><img src="{{ asset('images/logo3.png') }}" alt="" style="height:22px; width:auto; object-fit:contain;"> Reservasi & Pemesanan Online</li>
                        <li><img src="{{ asset('images/logo4.png') }}" alt="" style="height:22px; width:auto; object-fit:contain;"> Layanan Customer Service Ramah & Responsif</li>
                        <li><img src="{{ asset('images/logo5.png') }}" alt="" style="height:22px; width:auto; object-fit:contain;"> Konsultasi Jadwal & Rute Perjalanan</li>
                        <li><img src="{{ asset('images/logo6.png') }}" alt="" style="height:22px; width:auto; object-fit:contain;"> Layanan Cetak Tiket & Check-in Online</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- KTT -->
    <section class="full-bleed ktt">
        <div class="overlay"></div>
        <div class="content">
            <h2 class="fw-bold mb-3">KTT</h2>
            <p class="mb-5">“Satu kepercayaan untuk kemudahan tiket pesawat dan kapal laut, siap melayani perjalanan Anda dengan aman dan nyaman.”</p>

            <div class="container">
                <div class="row g-4 justify-content-center align-items-stretch">
                    <div class="col-md-4 d-flex">
                        <div class="card text-center w-100">
                            <img src="{{ asset('images/ikon_1.png') }}" alt="ikon_1" class="card-icon">
                            <h5 class="fw-bold">Trusted</h5>
                            <p class="mb-0">Agen berpengalaman untuk tiket pesawat & kapal laut.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card text-center w-100">
                            <img src="{{ asset('images/ikon_2.png') }}" alt="ikon_2" class="card-icon">
                            <h5 class="fw-bold">Fast & Easy</h5>
                            <p class="mb-0">Proses pemesanan praktis, bisa online & offline.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card text-center w-100">
                            <img src="{{ asset('images/ikon_3.png') }}" alt="ikon_3" class="card-icon">
                            <h5 class="fw-bold">Professional Services</h5>
                            <p class="mb-0">Tim ramah & siap bantu perjalanan Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section class="contact-section">
        <div class="contact-top">
            <h2>PT. KUPANG TOUR & TRAVEL</h2>
            <p>Pesan tiket pesawat dan kapal laut kini lebih mudah, cepat, dan aman bersama solusi terpercaya kami</p>
        </div>
        <div class="contact-bottom">
            <div class="d-flex justify-content-center gap-4 mt-3">
                <a href="#"><img src="{{ asset('images/gmail.png') }}" alt="gmail"></a>
                <a href="#"><img src="{{ asset('images/facebook.png') }}" alt="facebook"></a>
                <a href="#"><img src="{{ asset('images/whatsapp.png') }}" alt="whatsapp"></a>
            </div>
            <small class="d-block mt-3">Based In, Kupang Nusa Tenggara Timur<br>JL. Garuda No. 4</small>
        </div>
    </section>
</x-layouts.app>
