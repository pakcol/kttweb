<x-layouts.app title="Home">
    <style>
        /* reset dan cegah scroll horizontal */
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* ===== full-bleed util ===== */
        .full-bleed {
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            width: 100vw;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* hero spesifik */
        .hero {
            height: 100vh;
            min-height: 600px;
            background-image: url("{{ asset('images/hero_1.png') }}");
        }

        /* ktt spesifik */
        .ktt {
            min-height: 70vh;
            padding: 80px 0;
            background-image: url("{{ asset('images/hero_2.png') }}");
        }

        /* overlay gelap di atas gambar */
        .full-bleed .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 1;
        }

        /* konten di atas overlay */
        .full-bleed .content {
            position: relative;
            z-index: 2;
            color: #fff;
            text-align: center;
            width: 100%;
            max-width: 1100px;
            padding: 30px;
        }

        .hero h1 {
            font-weight: 800;
            letter-spacing: 1px;
            margin: 8px 0 16px;
            font-size: clamp(32px, 5vw, 64px);
        }

        .hero .lead {
            opacity: .95;
            margin-bottom: 0;
        }

        .layanan-card {
            display:inline-flex;
            align-items:center;
            gap:16px;
            padding:14px 26px;
            border-radius:12px;
            background:#fff;
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
        }

        /* What we do */
        .what-section {
            background: #f7f7f8;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        .what-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url("{{ asset('images/bg_bulat.png') }}") center/cover no-repeat;
            opacity: 0.15;
        }
        .what-section .container {
            position: relative;
            z-index: 2;
        }
        .what-list { list-style: none; padding: 0; margin: 0; text-align: left; }
        .what-list li { margin-bottom:14px; display:flex; gap:12px; align-items:center; font-size: 16px; }

        /* Ikon card */
        .card-icon {
            height: 64px;
            width: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto 12px;
        }

        /* Kartu KTT */
        .ktt .card {
            border-radius: 12px;
            padding: 30px 20px;
            box-shadow: 0 8px 18px rgba(0,0,0,.12);
            background: #fff;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .ktt .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,.18);
        }

        /* ===== contact section (full bleed, 2 layer) ===== */
.contact-section {
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    width: 100vw;
}

.contact-top {
    background: #004d61;
    color: #fff;
    padding: 60px 20px 40px;
    text-align: center;
}

.contact-bottom {
    background: url("{{ asset('images/bg_bulat.png') }}") center/cover no-repeat;
    padding: 40px 20px;
    text-align: center;
    color: #222;
    opacity: 0.95;
}
        .contact-bottom a img,
        .contact-top a img {
            height: 28px;
            width: auto;
        }

        /* footer */
        .site-footer {
            background:#111317;
            color:#fff;
            padding:40px 0;
        }
        .site-footer a img { margin-right: 6px; }

        /* responsive tweaks */
        @media (max-width: 992px) {
            .hero { min-height: 60vh; }
            .ktt { padding:40px 0; }
            .what-section { padding: 50px 20px; }
        }
    </style>

    <!-- HERO (full-bleed) -->
    <section class="full-bleed hero">
        <div class="overlay"></div>
        <div class="content">
            <p class="lead">Halo, selamat datang</p>
            <h1 class="display-4">PT. KUPANG TOUR & TRAVEL</h1>
            <a href="#login" class="btn btn-primary btn-lg">LOGIN</a>
        </div>
    </section>

    <!-- Layanan kecil -->
    <section class="py-5 bg-light text-center">
        <div class="container text-center">
            <div class="layanan-card">
                <img src="{{ asset('images/ikon_4.png') }}" alt="ikon_4" style="height:36px; width:auto; object-fit:contain;">
                <strong>Layanan Tiket Lengkap, Cepat & Mudah</strong>
                <img src="{{ asset('images/ikon_5.png') }}" alt="ikon_5" style="height:36px; width:auto; object-fit:contain;">
            </div>
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

    <!-- KTT (full-bleed like hero) -->
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
                            <p class="mb-0">Kami menjamin kepercayaan penuh dengan harga tiket yang transparan.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card text-center w-100">
                            <img src="{{ asset('images/ikon_2.png') }}" alt="ikon_2" class="card-icon">
                            <h5 class="fw-bold">Fast & Easy</h5>
                            <p class="mb-0">Proses pemesanan tiket mudah, cepat, dan tanpa ribet hanya dalam beberapa menit.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="card text-center w-100">
                            <img src="{{ asset('images/ikon_3.png') }}" alt="ikon_3" class="card-icon">
                            <h5 class="fw-bold">Professional Services</h5>
                            <p class="mb-0">Dilayani oleh tim yang ramah, responsif, dan profesional dalam membantu perjalanan Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Kontak (2 layer) -->
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
