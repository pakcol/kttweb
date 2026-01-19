<x-layouts.app title="Home">
    <!-- HERO -->
    <section class="full-bleed hero" aria-label="Hero">
        <div class="overlay"></div>
        <div class="content">
            <p class="lead">Halo, selamat datang</p>
            <h1 class="display-4">PT. KUPANG TOUR & TRAVEL</h1>
            {{-- Tombol login di hero --}}
            <button class="btn btn-book book-now-btn" type="button">BOOK NOW</button>
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
                        <button class="btn btn-book book-now-btn" type="button">BOOK NOW</button>

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
            <h4 class="text-center mb-4" style="color: #004d61; font-weight: 600;">Promo Spesial</h4>
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
            <h2 id="ktt-title" class="fw-bold mb-4">KTT</h2>
            <p class="mb-5" style="font-size: 1.2rem; max-width: 800px; margin: 0 auto;">"Satu kepercayaan untuk kemudahan tiket pesawat dan kapal laut, siap melayani perjalanan Anda dengan aman dan nyaman."</p>

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

    <div class="separator-section"></div>

    <!-- Kontak -->
    <section class="contact-section" aria-label="Kontak">
        <div class="contact-top">
            <h2>PT. KUPANG TOUR & TRAVEL</h2>
            <p>Pesan tiket pesawat dan kapal laut kini lebih mudah, cepat, dan aman bersama solusi terpercaya kami</p>
        </div>
        <div class="contact-bottom">
            <div class="social-links">
                <!-- Gmail -->
                <a href="https://mail.google.com/mail/?view=cm&fs=1
                    &to=kupang_tt@yahoo.com
                    &su=Permintaan%20Informasi%20Kupang%20Tour%20%26%20Travel
                    &body=Halo%20Kupang%20Tour%20%26%20Travel%2C%0A%0ASaya%20ingin%20menanyakan%20informasi%20terkait%20layanan%20perjalanan.%0A%0ATerima%20kasih."
                    class="social-item"
                   class="social-item"
                   aria-label="Email Kupang Tour & Travel">
                    <img src="{{ asset('images/gmail.png') }}" alt="Gmail">
                    <span class="visually-hidden">Gmail</span>
                </a>

                <!-- Facebook -->
                <a href="https://www.facebook.com/share/17s8aQ9rW6/?mibextid=wwXIfr"
                   target="_blank"
                   class="social-item"
                   aria-label="Facebook Kupang Tour & Travel">
                    <img src="{{ asset('images/facebook.png') }}" alt="Facebook">
                    <span class="visually-hidden">Facebook</span>
                </a>

                <!-- WhatsApp -->
                <a href="https://wa.me/6281237481987"
                   target="_blank"
                   class="social-item"
                   aria-label="WhatsApp Kupang Tour & Travel">
                    <img src="{{ asset('images/whatsapp.png') }}" alt="WhatsApp">
                    <span class="visually-hidden">Whatsapp</span>
                </a>
            </div>
            <small class="d-block mt-4">Based In, Kupang Nusa Tenggara Timur<br>JL. Garuda No. 4</small>
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
            <h3 id="jadwalPelniTitle" style="color: #004d61; text-align: center; margin-bottom: 20px;">Jadwal PELNI</h3>

            <div class="pelni-slideshow" id="pelniSlideshow" aria-live="polite">
                <div class="pelni-inner" id="pelniInner">
                    <img class="pelni-slide" src="{{ asset('images/pelni1.jpg') }}" alt="Jadwal PELNI 1" />
                    <img class="pelni-slide" src="{{ asset('images/pelni2.jpg') }}" alt="Jadwal PELNI 2" />
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
        <button class="modal-close" id="bookNowClose" aria-label="Tutup">&times;</button>

        <div class="book-header">
            <h3>Form Pemesanan Tiket</h3>
            <p>Isi data perjalanan Anda, kami akan bantu proses selanjutnya</p>
        </div>

        <div class="book-form">
            <div class="form-group">
                <input type="text" id="bn_name" placeholder="Nama Lengkap *" required>
                <input type="text" id="bn_airline" placeholder="Airlines *" required>
            </div>

            <div class="form-group">
                <input type="text" id="bn_route1" placeholder="Rute 1 (Kupang - Surabaya) *" required>
                <input type="date" id="bn_date1" required>
            </div>

            <div class="form-group">
                <input type="text" id="bn_route2" placeholder="Rute 2 (Opsional)">
                <input type="date" id="bn_date2">
            </div>

            <small class="form-note">* Wajib diisi</small>
        </div>

        <div class="book-action">
            <button id="sendToWa" class="btn-book-submit">
                BOOK TIKET
            </button>
        </div>
    </div>
</div>

    {{-- Inline scripts for the front-end only behavior --}}
    @push('scripts')
    <script>
    (function () {
        /* PROMO STRIP (FIXED VERSION) */
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
                card.setAttribute('data-index', idx);
                
                // Tambahkan event click untuk membuka modal
                card.addEventListener('click', () => openPromoModal(idx));

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
        if (promoStripInner) {
            promoStripInner.addEventListener('mouseenter', () => clearInterval(promoInterval));
            promoStripInner.addEventListener('mouseleave', () => { 
                promoInterval = setInterval(rotatePromo, 5000); 
            });
        }

        /* PROMO MODAL */
        const promoModal = document.getElementById('promoModal');
        const promoModalBody = document.getElementById('promoModalBody');
        const promoModalClose = document.getElementById('promoModalClose');

        function openPromoModal(idx) {
            if (!promoModalBody) return;
            
            promoModalBody.innerHTML = '';
            const big = document.createElement('img');
            big.src = promoImages[idx];
            big.alt = 'Detail Promo ' + (idx + 1);
            big.className = 'promo-modal-img';
            promoModalBody.appendChild(big);

            promoModal.setAttribute('aria-hidden', 'false');
            promoModal.style.display = 'flex';
            
            // focus for keyboard
            if (promoModalBody) promoModalBody.focus();
            
            // stop strip rotation while modal open
            clearInterval(promoInterval);
        }

        function closePromoModal() {
            promoModal.setAttribute('aria-hidden', 'true');
            promoModal.style.display = 'none';
            // resume rotation
            promoInterval = setInterval(rotatePromo, 5000);
        }

        if (promoModalClose) {
            promoModalClose.addEventListener('click', closePromoModal);
        }
        
        if (promoModal) {
            promoModal.addEventListener('click', function (e) {
                if (e.target === promoModal) closePromoModal();
            });
        }

        /* LANDSCAPE PROMO */
        const landscapeImages = [
            "{{ asset('images/LandscapePromo.jpg') }}"
            // add more if available: "{{ asset('images/LandscapePromo2.jpg') }}"
        ];
        
        const landscapeInner = document.getElementById('landscapeInner');
        const landscapeControls = document.getElementById('landscapeControls');
        let landscapeIdx = 0;
        let landscapeInterval = null;

        function renderLandscape() {
            if (!landscapeInner) return;
            
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
                if (landscapeControls) landscapeControls.style.display = 'none';
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
            
            const landscapeNextBtn = document.querySelector('.landscape-next');
            const landscapePrevBtn = document.querySelector('.landscape-prev');
            
            if (landscapeNextBtn) {
                landscapeNextBtn.addEventListener('click', () => {
                    clearInterval(landscapeInterval);
                    nextLandscape();
                    landscapeInterval = setInterval(nextLandscape, 5000);
                });
            }
            
            if (landscapePrevBtn) {
                landscapePrevBtn.addEventListener('click', () => {
                    clearInterval(landscapeInterval);
                    prevLandscape();
                    landscapeInterval = setInterval(nextLandscape, 5000);
                });
            }
        }

        // Pause landscape autoplay on hover
        const landscapePromoEl = document.getElementById('landscapePromo');
        if (landscapePromoEl) {
            landscapePromoEl.addEventListener('mouseenter', () => clearInterval(landscapeInterval));
            landscapePromoEl.addEventListener('mouseleave', () => {
                if (landscapeImages.length > 1) landscapeInterval = setInterval(nextLandscape, 5000);
            });
        }

        /* JADWAL PELNI MODAL (slideshow) */
        const jadwalPelniBtn = document.getElementById('jadwalPelniBtn');
        const jadwalPelniModal = document.getElementById('jadwalPelniModal');
        const jadwalPelniClose = document.getElementById('jadwalPelniClose');

        const pelniInner = document.getElementById('pelniInner');
        const pelniSlides = pelniInner ? Array.from(pelniInner.querySelectorAll('.pelni-slide')) : [];
        let pelniIdx = 0;
        let pelniInterval = null;

        function showPelniSlide(i) {
            pelniSlides.forEach((s, idx) => {
                s.style.display = (idx === i) ? 'block' : 'none';
                if (idx === i) s.setAttribute('aria-hidden', 'false'); 
                else s.setAttribute('aria-hidden', 'true');
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

        const pelniPrevBtn = document.getElementById('pelniPrev');
        const pelniNextBtn = document.getElementById('pelniNext');
        
        if (pelniPrevBtn) {
            pelniPrevBtn.addEventListener('click', () => {
                stopPelniAuto();
                pelniIdx = (pelniIdx - 1 + pelniSlides.length) % pelniSlides.length;
                showPelniSlide(pelniIdx);
                startPelniAuto();
            });
        }
        
        if (pelniNextBtn) {
            pelniNextBtn.addEventListener('click', () => {
                stopPelniAuto();
                pelniIdx = (pelniIdx + 1) % pelniSlides.length;
                showPelniSlide(pelniIdx);
                startPelniAuto();
            });
        }

        function openJadwalPelniModal() {
            if (!jadwalPelniModal) return;
            
            jadwalPelniModal.setAttribute('aria-hidden', 'false');
            jadwalPelniModal.style.display = 'flex';
            showPelniSlide(pelniIdx);
            startPelniAuto();
            jadwalPelniModal.focus();
        }

        function closeJadwalPelniModal() {
            if (!jadwalPelniModal) return;
            
            jadwalPelniModal.setAttribute('aria-hidden', 'true');
            jadwalPelniModal.style.display = 'none';
            stopPelniAuto();
        }

        if (jadwalPelniBtn) {
            jadwalPelniBtn.addEventListener('click', openJadwalPelniModal);
        }
        
        if (jadwalPelniClose) {
            jadwalPelniClose.addEventListener('click', closeJadwalPelniModal);
        }
        
        if (jadwalPelniModal) {
            jadwalPelniModal.addEventListener('click', function (e) {
                if (e.target === jadwalPelniModal) closeJadwalPelniModal();
            });
        }

        // Initial pelni setup: hide all but first
        if (pelniSlides.length === 0 && pelniInner) {
            // If no images, show placeholder
            pelniInner.innerHTML = '<div class="pelni-placeholder">Belum ada jadwal PELNI.</div>';
        } else if (pelniSlides.length > 0) {
            showPelniSlide(0);
        }

        /* BOOK NOW → WHATSAPP CHAT */
        const bookBtn = document.getElementById('bookNowBtn');
        const bookModal = document.getElementById('bookNowModal');
        const bookClose = document.getElementById('bookNowClose');
        const sendToWa = document.getElementById('sendToWa');

        document.querySelectorAll('.book-now-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (bookModal) {
                    bookModal.style.display = 'flex';
                }
            });
        });

        if (bookClose) {
            bookClose.addEventListener('click', () => {
                if (bookModal) bookModal.style.display = 'none';
            });
        }

        if (sendToWa) {
            sendToWa.addEventListener('click', () => {
                const nama = document.getElementById('bn_name').value;
                const airline = document.getElementById('bn_airline').value;
                const rute1 = document.getElementById('bn_route1').value;
                const tgl1 = document.getElementById('bn_date1').value;
                const rute2 = document.getElementById('bn_route2').value;
                const tgl2 = document.getElementById('bn_date2').value;

                // Validasi input
                if (!nama || !airline || !rute1 || !tgl1) {
                    alert('Harap isi semua field yang wajib diisi!');
                    return;
                }

                // Format tanggal
                const formatDate = (dateString) => {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', { 
                        day: 'numeric', 
                        month: 'long', 
                        year: 'numeric' 
                    });
                };

                const message =
                    `Halo Kupang Tour & Travel,

                    Saya ingin menanyakan ketersediaan tiket dengan detail berikut:

                    Nama Penumpang  : ${nama}
                    Maskapai        : ${airline}
                    Rute 1          : ${rute1}
                    Tanggal Flight  : ${formatDate(tgl1)}
                    Rute 2          : ${rute2 || '-'}
                    Tanggal Flight  : ${tgl2 ? formatDate(tgl2) : '-'}

                    Mohon dibantu untuk pengecekan ketersediaan dan informasi selanjutnya.
                    Terima kasih.`;

                    const waUrl = `https://wa.me/6281237481987?text=${encodeURIComponent(message)}`;
                    window.open(waUrl, '_blank');
            });
        }

        // Klik area luar → tutup modal
        if (bookModal) {
            bookModal.addEventListener('click', (e) => {
                if (e.target === bookModal) {
                    bookModal.style.display = 'none';
                }
            });
        }

        // ESC → tutup modal untuk semua modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (promoModal && promoModal.style.display === 'flex') closePromoModal();
                if (jadwalPelniModal && jadwalPelniModal.style.display === 'flex') closeJadwalPelniModal();
                if (bookModal && bookModal.style.display === 'flex') bookModal.style.display = 'none';
            }
        });

        /* Accessibility: trap focus inside modals when open */
        function trapFocus(modalEl) {
            if (!modalEl) return;
            
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

        // Trap focus untuk semua modal
        trapFocus(promoModal);
        trapFocus(jadwalPelniModal);
        trapFocus(bookModal);

    })();
    </script>
    @endpush
</x-layouts.app>