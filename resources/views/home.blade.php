<x-layouts.app title="Home">
    <!-- HERO -->
    <section class="full-bleed hero" aria-label="Hero">
        <div class="overlay"></div>
        <div class="content">
            <p class="lead">Halo, selamat datang</p>
            <h1 class="display-4">PT. KUPANG TOUR & TRAVEL</h1>
            {{-- Tombol login di hero --}}
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">LOGIN</a>
        </div>

        {{-- PROMO STRIP (center-floating) --}}
        <div class="promo-strip" aria-label="Promo Strip" role="region">
            <div class="promo-strip-inner" id="promoStripInner" aria-live="polite"></div>
        </div>
    </section>

    <!-- WHAT WE DO -->
    <section class="what-section" aria-labelledby="what-we-do-title">
        <div class="container">
            <div class="row align-items-center justify-content-center g-5">
                <div class="col-md-5 text-center">
                    <img src="{{ asset('images/hero_3.png') }}" alt="Ilustrasi layanan Kupang Tour & Travel" class="img-fluid" style="max-height:260px; width:auto; object-fit:contain;">
                </div>
                <div class="col-md-6">
                    <h3 id="what-we-do-title" class="fw-bold mb-4">What We Do?</h3>
                    <ul class="what-list">
                        <li><img src="{{ asset('images/logo1.png') }}" alt="logo pesawat" style="height:22px; width:auto; object-fit:contain;"> Penjualan Tiket Pesawat Domestik & Internasional</li>
                        <li><img src="{{ asset('images/logo2.png') }}" alt="logo kapal" style="height:22px; width:auto; object-fit:contain;"> Penjualan Tiket Kapal Laut PELNI</li>
                        <li><img src="{{ asset('images/logo3.png') }}" alt="logo reservasi" style="height:22px; width:auto; object-fit:contain;"> Reservasi & Pemesanan Online</li>
                        <li><img src="{{ asset('images/logo4.png') }}" alt="logo customer service" style="height:22px; width:auto; object-fit:contain;"> Layanan Customer Service Ramah & Responsif</li>
                        <li><img src="{{ asset('images/logo5.png') }}" alt="logo konsultasi" style="height:22px; width:auto; object-fit:contain;"> Konsultasi Jadwal & Rute Perjalanan</li>
                        <li><img src="{{ asset('images/logo6.png') }}" alt="logo check-in" style="height:22px; width:auto; object-fit:contain;"> Layanan Cetak Tiket & Check-in Online</li>
                    </ul>

                    <div class="mt-4 d-flex gap-3">
    <!-- BOOK NOW -->
    <button id="bookNowBtn" class="btn btn-book" type="button"> BOOK NOW </button>

                        <!-- JADWAL PELNI modal trigger -->
                        <button id="jadwalPelniBtn" class="btn btn-outline" type="button" aria-haspopup="dialog" aria-controls="jadwalPelniModal">JADWAL PELNI</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Landscape Promo Section (below What We Do) -->
    <section class="landscape-promo-section" aria-label="Landscape Promo">
        <div class="container">
            <div class="landscape-promo" id="landscapePromo">
                <div class="landscape-inner" id="landscapeInner" role="list"></div>

                <!-- controls (hidden if only 1 slide) -->
                <div class="landscape-controls" id="landscapeControls" aria-hidden="true">
                    <button class="landscape-prev" aria-label="Previous landscape promo">‹</button>
                    <button class="landscape-next" aria-label="Next landscape promo">›</button>
                </div>
            </div>
        </div>
    </section>

    <!-- KTT -->
    <section class="full-bleed ktt" aria-labelledby="ktt-title">
        <div class="overlay"></div>
        <div class="content">
            <h2 id="ktt-title" class="fw-bold mb-3">KTT</h2>
            <p class="mb-5">“Satu kepercayaan untuk kemudahan tiket pesawat dan kapal laut, siap melayani perjalanan Anda dengan aman dan nyaman.”</p>

            <div class="container">
                <div class="row g-4 justify-content-center align-items-stretch">
                    <div class="col-md-4 d-flex">
                        <div class="glass-card text-center w-100">
                            <img src="{{ asset('images/ikon_1.png') }}" alt="ikon trusted" class="card-icon">
                            <h5 class="fw-bold">Trusted</h5>
                            <p class="mb-0">Agen berpengalaman untuk tiket pesawat & kapal laut.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="glass-card text-center w-100">
                            <img src="{{ asset('images/ikon_2.png') }}" alt="ikon fast and easy" class="card-icon">
                            <h5 class="fw-bold">Fast & Easy</h5>
                            <p class="mb-0">Proses pemesanan praktis, bisa online & offline.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex">
                        <div class="glass-card text-center w-100">
                            <img src="{{ asset('images/ikon_3.png') }}" alt="ikon professional services" class="card-icon">
                            <h5 class="fw-bold">Professional Services</h5>
                            <p class="mb-0">Tim ramah & siap bantu perjalanan Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section class="contact-section" aria-label="Kontak">
        <div class="contact-top">
            <h2>PT. KUPANG TOUR & TRAVEL</h2>
            <p>Pesan tiket pesawat dan kapal laut kini lebih mudah, cepat, dan aman bersama solusi terpercaya kami</p>
        </div>
        <div class="contact-bottom">
            <div class="social-links">
    <!-- Gmail -->
    <a href="mailto:kupang_tt@yahoo.com"
       class="social-item"
       aria-label="Email Kupang Tour & Travel">
        <img src="{{ asset('images/gmail.png') }}" alt="Gmail">
        <span>Gmail</span>
    </a>

    <!-- Facebook -->
    <a href="https://www.facebook.com/share/17s8aQ9rW6/?mibextid=wwXIfr"
       target="_blank"
       class="social-item"
       aria-label="Facebook Kupang Tour & Travel">
        <img src="{{ asset('images/facebook.png') }}" alt="Facebook">
        <span>Facebook</span>
    </a>

    <!-- WhatsApp -->
    <a href="https://wa.me/6281237481987"
       target="_blank"
       class="social-item"
       aria-label="WhatsApp Kupang Tour & Travel">
        <img src="{{ asset('images/whatsapp.png') }}" alt="WhatsApp">
        <span>WhatsApp</span>
    </a>
</div>
            <small class="d-block mt-3">Based In, Kupang Nusa Tenggara Timur<br>JL. Garuda No. 4</small>
        </div>
    </section>

    <!-- PROMO MODAL (large view for clicked promo) -->
    <div class="modal-backdrop" id="promoModal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="promoModalTitle" tabindex="-1">
        <div class="modal-content" role="document">
            <button class="modal-close" id="promoModalClose" aria-label="Tutup modal">&times;</button>
            <h3 id="promoModalTitle" class="visually-hidden">Detail Promo</h3>
            <div class="modal-body" id="promoModalBody" tabindex="0"></div>
        </div>
    </div>

    <!-- JADWAL PELNI MODAL (slideshow) -->
    <div class="modal-backdrop" id="jadwalPelniModal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="jadwalPelniTitle" tabindex="-1">
        <div class="modal-content" role="document">
            <button class="modal-close" id="jadwalPelniClose" aria-label="Tutup modal">&times;</button>
            <h3 id="jadwalPelniTitle">Jadwal PELNI</h3>

            <div class="pelni-slideshow" id="pelniSlideshow" aria-live="polite">
                <div class="pelni-inner" id="pelniInner">
                    <!-- Place PELNI images in /public/images/ named pelni1.jpg, pelni2.jpg, etc. -->
                    <img class="pelni-slide" src="{{ asset('images/pelni1.jpg') }}" alt="Jadwal PELNI 1" />
                    <img class="pelni-slide" src="{{ asset('images/pelni2.jpg') }}" alt="Jadwal PELNI 2" />
                    <!-- If you have only one image, keep one. If more, add more img tags. -->
                </div>

                <div class="pelni-controls">
                    <button id="pelniPrev" aria-label="Sebelumnya">Prev</button>
                    <button id="pelniNext" aria-label="Berikutnya">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= BOOK NOW MODAL ================= -->
<div class="modal-backdrop" id="bookNowModal" aria-hidden="true">
    <div class="modal-content book-modal">
        <button class="modal-close" id="bookNowClose">&times;</button>

        <h4 class="fw-bold mb-3">Form Pemesanan Tiket</h4>

        <div class="book-form">
            <input type="text" id="bn_name" placeholder="Nama Lengkap">
            <input type="text" id="bn_airline" placeholder="Airlines">
            <input type="text" id="bn_route1" placeholder="Rute 1 (Kupang - Surabaya)">
            <input type="date" id="bn_date1">
            <input type="text" id="bn_route2" placeholder="Rute 2 (Opsional)">
            <input type="date" id="bn_date2">
        </div>

        <div class="text-end mt-3">
            <button id="sendToWa" class="btn btn-book">
                BOOK
            </button>
        </div>
    </div>
</div>


    {{-- Inline scripts for the front-end only behavior --}}
    @push('scripts')
    <script>
(function () {

    /* ===============================
       PROMO STRIP (FIXED VERSION)
    =============================== */

    const promoImages = [
        "{{ asset('images/Promo1.jpg') }}",
        "{{ asset('images/Promo2.jpg') }}",
        "{{ asset('images/Promo3.jpg') }}",
        "{{ asset('images/Promo4.jpg') }}",
        "{{ asset('images/Promo5.jpg') }}",
        "{{ asset('images/Promo6.jpg') }}"
    ];

    const promoStripInner = document.getElementById('promoStripInner');
    let promoIndex = 0;

    function renderPromoStrip() {
        if (!promoStripInner) return;

        promoStripInner.innerHTML = '';
        const total = promoImages.length;

        // tampilkan 5 kartu (2 kiri, 1 tengah, 2 kanan)
        for (let i = -2; i <= 2; i++) {
            const idx = (promoIndex + i + total) % total;

            const card = document.createElement('div');
            card.className = 'promo-card' + (i === 0 ? ' center' : '');

            const img = document.createElement('img');
            img.src = promoImages[idx];
            img.alt = 'Promo ' + (idx + 1);
            img.loading = 'lazy';

            card.appendChild(img);
            promoStripInner.appendChild(card);
        }
    }

            function rotatePromo() {
                promoIndex = (promoIndex + 1) % promoImages.length;
                renderPromoStrip();
            }

            // initial render
            renderPromoStrip();
            // auto rotate every 5s
            let promoInterval = setInterval(rotatePromo, 5000);

            // Pause on hover/focus for accessibility
            promoStripInner.addEventListener('mouseenter', () => clearInterval(promoInterval));
            promoStripInner.addEventListener('mouseleave', () => { promoInterval = setInterval(rotatePromo, 5000); });

            /* PROMO MODAL */
            const promoModal = document.getElementById('promoModal');
            const promoModalBody = document.getElementById('promoModalBody');
            const promoModalClose = document.getElementById('promoModalClose');

            function openPromoModal(idx) {
                promoModalBody.innerHTML = '';
                const big = document.createElement('img');
                big.src = promoImages[idx];
                big.alt = 'Detail Promo ' + (idx + 1);
                big.className = 'promo-modal-img';
                promoModalBody.appendChild(big);

                promoModal.setAttribute('aria-hidden', 'false');
                promoModal.style.display = 'flex';
                // focus for keyboard
                promoModalBody.focus();
                // stop strip rotation while modal open
                clearInterval(promoInterval);
            }

            function closePromoModal() {
                promoModal.setAttribute('aria-hidden', 'true');
                promoModal.style.display = 'none';
                // resume rotation
                promoInterval = setInterval(rotatePromo, 5000);
            }

            promoModalClose.addEventListener('click', closePromoModal);
            promoModal.addEventListener('click', function (e) {
                if (e.target === promoModal) closePromoModal();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    if (promoModal.style.display === 'flex') closePromoModal();
                    if (jadwalPelniModal.style.display === 'flex') closeJadwalPelniModal();
                }
            });

            /* LANDSCAPE PROMO: use single image asset images/LandscapePromo.jpg
               If you want multiple landscape promos, add them to landscapeImages array.
            */
            const landscapeImages = [
                "{{ asset('images/LandscapePromo.jpg') }}"
                // add more if available: "{{ asset('images/LandscapePromo2.jpg') }}"
            ];
            const landscapeInner = document.getElementById('landscapeInner');
            const landscapeControls = document.getElementById('landscapeControls');
            let landscapeIdx = 0;
            let landscapeInterval = null;

            function renderLandscape() {
                landscapeInner.innerHTML = '';
                landscapeImages.forEach((src, i) => {
                    const item = document.createElement('div');
                    item.className = 'landscape-item' + (i === landscapeIdx ? ' active' : '');
                    const img = document.createElement('img');
                    img.src = src;
                    img.alt = 'Landscape Promo ' + (i + 1);
                    img.loading = 'lazy';
                    item.appendChild(img);
                    landscapeInner.appendChild(item);
                });

                // show controls only if >1
                if (landscapeImages.length > 1) {
                    landscapeControls.setAttribute('aria-hidden', 'false');
                    landscapeControls.style.display = 'flex';
                } else {
                    landscapeControls.style.display = 'none';
                }
            }

            function nextLandscape() {
                landscapeIdx = (landscapeIdx + 1) % landscapeImages.length;
                renderLandscape();
            }
            function prevLandscape() {
                landscapeIdx = (landscapeIdx - 1 + landscapeImages.length) % landscapeImages.length;
                renderLandscape();
            }

            renderLandscape();
            if (landscapeImages.length > 1) {
                landscapeInterval = setInterval(nextLandscape, 5000);
                document.querySelector('.landscape-next').addEventListener('click', () => {
                    clearInterval(landscapeInterval);
                    nextLandscape();
                    landscapeInterval = setInterval(nextLandscape, 5000);
                });
                document.querySelector('.landscape-prev').addEventListener('click', () => {
                    clearInterval(landscapeInterval);
                    prevLandscape();
                    landscapeInterval = setInterval(nextLandscape, 5000);
                });
            }

            // Pause landscape autoplay on hover
            const landscapePromoEl = document.getElementById('landscapePromo');
            landscapePromoEl.addEventListener('mouseenter', () => clearInterval(landscapeInterval));
            landscapePromoEl.addEventListener('mouseleave', () => {
                if (landscapeImages.length > 1) landscapeInterval = setInterval(nextLandscape, 5000);
            });

            /* JADWAL PELNI MODAL (slideshow) */
            const jadwalPelniBtn = document.getElementById('jadwalPelniBtn');
            const jadwalPelniModal = document.getElementById('jadwalPelniModal');
            const jadwalPelniClose = document.getElementById('jadwalPelniClose');

            const pelniInner = document.getElementById('pelniInner');
            const pelniSlides = Array.from(pelniInner.querySelectorAll('.pelni-slide'));
            let pelniIdx = 0;
            let pelniInterval = null;

            function showPelniSlide(i) {
                pelniSlides.forEach((s, idx) => {
                    s.style.display = (idx === i) ? 'block' : 'none';
                    if (idx === i) s.setAttribute('aria-hidden', 'false'); else s.setAttribute('aria-hidden', 'true');
                });
            }

            function startPelniAuto() {
                if (pelniSlides.length > 1) {
                    pelniInterval = setInterval(() => {
                        pelniIdx = (pelniIdx + 1) % pelniSlides.length;
                        showPelniSlide(pelniIdx);
                    }, 10000); // 10s auto slide
                }
            }

            function stopPelniAuto() {
                if (pelniInterval) {
                    clearInterval(pelniInterval);
                    pelniInterval = null;
                }
            }

            document.getElementById('pelniPrev').addEventListener('click', () => {
                stopPelniAuto();
                pelniIdx = (pelniIdx - 1 + pelniSlides.length) % pelniSlides.length;
                showPelniSlide(pelniIdx);
                startPelniAuto();
            });
            document.getElementById('pelniNext').addEventListener('click', () => {
                stopPelniAuto();
                pelniIdx = (pelniIdx + 1) % pelniSlides.length;
                showPelniSlide(pelniIdx);
                startPelniAuto();
            });

            function openJadwalPelniModal() {
                jadwalPelniModal.setAttribute('aria-hidden', 'false');
                jadwalPelniModal.style.display = 'flex';
                showPelniSlide(pelniIdx);
                startPelniAuto();
                jadwalPelniModal.focus();
            }

            function closeJadwalPelniModal() {
                jadwalPelniModal.setAttribute('aria-hidden', 'true');
                jadwalPelniModal.style.display = 'none';
                stopPelniAuto();
            }

            jadwalPelniBtn.addEventListener('click', openJadwalPelniModal);
            jadwalPelniClose.addEventListener('click', closeJadwalPelniModal);
            jadwalPelniModal.addEventListener('click', function (e) {
                if (e.target === jadwalPelniModal) closeJadwalPelniModal();
            });

            // Initial pelni setup: hide all but first
            if (pelniSlides.length === 0) {
                // If no images, show placeholder
                pelniInner.innerHTML = '<div class="pelni-placeholder">Belum ada jadwal PELNI.</div>';
            } else {
                showPelniSlide(0);
            }

            /* Accessibility: trap focus inside modals when open (simple) */
            function trapFocus(modalEl) {
                const focusables = modalEl.querySelectorAll('a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])');
                if (!focusables.length) return;
                const first = focusables[0];
                const last = focusables[focusables.length - 1];
                modalEl.addEventListener('keydown', function (e) {
                    if (e.key !== 'Tab') return;
                    if (e.shiftKey) { // shift+tab
                        if (document.activeElement === first) {
                            e.preventDefault();
                            last.focus();
                        }
                    } else {
                        if (document.activeElement === last) {
                            e.preventDefault();
                            first.focus();
                        }
                    }
                });
            }

            trapFocus(promoModal);
            trapFocus(jadwalPelniModal);

            /* Ensure center card stays dominant on small screens by using CSS scaling; no extra JS required */

            // Done
        })();

    /* =====================================================
   BOOK NOW → WHATSAPP CHAT
===================================================== */
const bookBtn   = document.getElementById('bookNowBtn');
const bookModal = document.getElementById('bookNowModal');
const bookClose = document.getElementById('bookNowClose');
const sendToWa  = document.getElementById('sendToWa');

bookBtn.addEventListener('click', () => {
    bookModal.style.display = 'flex';
});

bookClose.addEventListener('click', () => {
    bookModal.style.display = 'none';
});

sendToWa.addEventListener('click', () => {
    const nama     = bn_name.value;
    const airline  = bn_airline.value;
    const rute1    = bn_route1.value;
    const tgl1     = bn_date1.value;
    const rute2    = bn_route2.value;
    const tgl2     = bn_date2.value;

    const message =
`Halo Kupang travel, saya ingin mencari tiket ke :

Nama            : ${nama}
Airlines        : ${airline}
Rute 1          : ${rute1}
Tgl flight 1    : ${tgl1}
Rute 2          : ${rute2}
Tgl flight 2    : ${tgl2}

apakah bisa dibantu untuk pemesanannya?`;

    const waUrl = `https://wa.me/6281237481987?text=${encodeURIComponent(message)}`;
    window.open(waUrl, '_blank');
});

// Klik area luar → tutup modal
bookModal.addEventListener('click', (e) => {
    if (e.target === bookModal) {
        bookModal.style.display = 'none';
    }
});

// ESC → tutup modal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        bookModal.style.display = 'none';
    }
});
        
    </script>
    @endpush
</x-layouts.app>
