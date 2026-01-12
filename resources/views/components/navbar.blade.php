{{-- resources/views/components/navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top border-bottom">
    <div class="container-fluid px-4 px-lg-5">
        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="KTT Logo" height="40">
        </a>

        {{-- Toggle button (for mobile) --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- Jam Realtime -->
        <span id="realtime-clock" class="ms-3 fw-medium text-dark"></span>
        {{-- Menu --}}
        <div class="collapse navbar-collapse justify-content-end mt-2 mt-lg-0" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center gap-lg-3">
                @guest
                    {{-- Menu untuk tamu --}}
                    <li class="nav-item">
                        <a class="nav-link text-dark fw-medium {{ request()->is('/') ? 'text-primary' : '' }}" href="{{ url('/') }}">
                            Home
                        </a>
                    </li>
                @else
                    {{-- Menu untuk user login dengan Dropdown --}}
                    
                    <!-- Home Dropdown -->
                    <li class="nav-item dropdown" id="homeDropdown">
                        <a class="nav-link fw-medium dropdown-toggle {{ request()->is('homeDb') ? 'text-primary fw-semibold' : 'text-dark' }}" 
                           href="#" 
                           role="button" 
                           data-bs-toggle="dropdown"
                           aria-expanded="false"
                           onclick="toggleDropdown('homeDropdown')">
                            Home
                        </a>
                        <ul class="dropdown-menu dropdown-click-menu">
                            <li><a class="dropdown-item" href="{{ url('/input-tiket') }}">Input Tiket</a></li>
                            <li><a class="dropdown-item" href="{{ url('/tiket/piutang') }}">Piutang</a></li>
                            <li><a class="dropdown-item" href="{{ url('/biaya') }}">Biaya</a></li>
                            <li><a class="dropdown-item" href="{{ url('/find-ticket') }}">Find Ticket</a></li>
                            <li><a class="dropdown-item" href="{{ url('/mutasi-tiket') }}">Mutasi Tiket</a></li>
                            <li><a class="dropdown-item" href="{{ url('/cash-flow') }}">Cash Flow</a></li>
                        </ul>
                    </li>

                    <!-- Sub Agent Dropdown -->
                    <li class="nav-item dropdown" id="subAgentDropdown">
                        <a class="nav-link fw-medium {{ request()->is('sub-agent') ? 'text-primary fw-semibold' : 'text-dark' }}" 
                           href="{{ url('/subagent') }}" 
                           role="button"
                           aria-expanded="false">
                            Sub Agent
                        </a>
                    </li>

                    <!-- PLN Dropdown -->
                    <li class="nav-item dropdown" id="plnDropdown">
                        <a class="nav-link fw-medium dropdown-toggle {{ request()->is('pln') ? 'text-primary fw-semibold' : 'text-dark' }}" 
                           href="#" 
                           role="button" 
                           data-bs-toggle="dropdown"
                           aria-expanded="false"
                           onclick="toggleDropdown('plnDropdown')">
                            PPOB
                        </a>
                        <ul class="dropdown-menu dropdown-click-menu">
                            <li><a class="dropdown-item" href="{{ url('/ppob') }}">Transaksi</a></li>
                            <li><a class="dropdown-item" href="{{ url('/ppob/piutang') }}">Piutang</a></li>
                        </ul>
                    </li>

                    <!-- Admin Dropdown - Hanya untuk Superuser -->
                    @if(auth()->check() && auth()->user()->roles === 'superuser')
                    <li class="nav-item dropdown" id="adminDropdown">
                        <a class="nav-link fw-medium dropdown-toggle {{ request()->is('admin') ? 'text-primary fw-semibold' : 'text-dark' }}" 
                           href="#" 
                           role="button" 
                           data-bs-toggle="dropdown"
                           aria-expanded="false"
                           onclick="toggleDropdown('adminDropdown')">
                            Admin
                        </a>
                        <ul class="dropdown-menu dropdown-click-menu">
                            <li><a class="dropdown-item" href="{{ url('/register') }}">Register new User</a></li>
                            <li><a class="dropdown-item" href="{{ url('/rekap-penjualan') }}">Rekapan Penjualan</a></li>
                            <li><a class="dropdown-item" href="{{ url('/buku-bank') }}">Buku Bank</a></li>
                        </ul>
                    </li>
                    @endif

                    {{-- Dropdown user --}}
                    <li class="nav-item user-nav">
    <a class="nav-link d-flex align-items-center nav-user-pill"
       href="#">

        <span class="user-name">{{ Auth::user()->name }}</span>

        <span class="avatar-ring">
            <img src="{{ asset('images/people.png') }}"
                 alt="User Profile"
                 class="user-avatar">
        </span>
    </a>
</li>
<li class="nav-item dropdown">
    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt me-2"></i>Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</li>

<style>
.user-nav {
    display: flex;
    align-items: center;
}

.nav-user-pill {
    display: flex !important;
    align-items: center;
    gap: 10px;

    padding: 6px 14px;
    border-radius: 999px;

    background: rgba(13,110,253,0.06);
    border: 1px solid rgba(13,110,253,0.15);

    transition: all 0.3s ease;
}

.user-name {
    font-weight: 600;
    font-size: 0.95rem;
    white-space: nowrap;
    color: #1f2937;
    letter-spacing: 0.2px;
}

.avatar-ring {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 34px;
    height: 34px;
    border-radius: 50%;

    background: linear-gradient(
        135deg,
        rgba(13,110,253,0.5),
        rgba(111,66,193,0.5)
    );
}

.user-avatar {
    width: 26px !important;
    height: 26px !important;
    min-width: 26px;
    min-height: 26px;

    border-radius: 50%;
    object-fit: cover;
    background: #fff;
}

.nav-user-pill:hover {
    background: rgba(13,110,253,0.12);
    transform: translateY(-1px);
}

.nav-user-pill:hover .avatar-ring {
    box-shadow: 0 0 0 4px rgba(13,110,253,0.15);
}

.user-nav .nav-link::after {
    display: none !important;
}


</style>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
:root {
    --primary: #0d6efd;
    --dark: #1f2937;
    --muted: #6b7280;
    --glass-bg: rgba(255, 255, 255, 0.85);
    --glass-border: rgba(255, 255, 255, 0.35);
    --shadow-soft: 0 8px 25px rgba(0,0,0,0.08);
    --shadow-strong: 0 22px 50px rgba(0,0,0,0.18);
    --radius-lg: 18px;
    --radius-md: 12px;
}
.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1050;
    overflow: visible !important;

    backdrop-filter: blur(14px);
    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.95),
        rgba(255,255,255,0.88)
    ) !important;

    border-bottom: 1px solid var(--glass-border);
    transition: all 0.35s ease;
}

.container-fluid,
.navbar-collapse {
    overflow: visible !important;
}
.navbar-brand img {
    transition: transform 0.35s ease, filter 0.35s ease;
}

.navbar-brand:hover img {
    transform: scale(1.08);
    filter: drop-shadow(0 4px 8px rgba(13,110,253,0.25));
}

.navbar-nav .nav-link {
    position: relative;
}

.navbar-nav .nav-link::after {
    content: "";
    position: absolute;
    bottom: 4px;
    left: 14px;
    right: 14px;
    height: 2px;

    background: linear-gradient(90deg, var(--primary), #6f42c1);
    border-radius: 2px;

    transform: scaleX(0);
    transform-origin: center;
    transition: transform 0.3s ease;
}

/* Hover & Active */
.navbar-nav .nav-link:hover {
    color: var(--primary) !important;
    transform: translateY(-1px);
}

.navbar-nav .nav-link:hover::after,
.navbar-nav .nav-link.text-primary::after {
    transform: scaleX(1);
}

.navbar-nav .nav-link.text-primary {
    font-weight: 600;
}

#realtime-clock {
    font-size: 0.9rem;
    color: var(--muted);
    padding: 6px 14px;
    border-radius: 999px;
    background: rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

#realtime-clock:hover {
    background: rgba(13,110,253,0.1);
    color: var(--primary);
}

.nav-item.dropdown {
    position: relative;
}

.dropdown-click-menu {
    position: absolute;
    top: calc(100% + 10px);
    left: 0;
    z-index: 9999;

    min-width: 220px;
    padding: 10px 0;
    border-radius: var(--radius-lg);

    background: var(--glass-bg);
    backdrop-filter: blur(18px);
    border: 1px solid var(--glass-border);
    box-shadow: var(--shadow-strong);

    opacity: 0;
    visibility: hidden;
    transform: translateY(12px) scale(0.96);
    transition: all 0.35s ease;
}
.dropdown-manual-show > .dropdown-click-menu {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) scale(1);
}


.dropdown-item {
    position: relative;
    padding: 12px 22px;
    font-size: 0.92rem;
    color: #374151;
    transition: all 0.3s ease;
}

.dropdown-item::after {
    content: "";
    position: absolute;
    bottom: 6px;
    left: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary), #6f42c1);
    border-radius: 2px;
    transform: translateX(-50%);
    transition: width 0.3s ease;
}

.dropdown-item:hover {
    background: linear-gradient(
        135deg,
        rgba(13,110,253,0.12),
        rgba(111,66,193,0.06)
    );
    color: var(--primary) !important;
}

.dropdown-item:hover::after {
    width: 55%;
}


.dropdown-toggle::after {
    transition: transform 0.35s ease;
}

.dropdown-manual-show .dropdown-toggle::after {
    transform: rotate(180deg);
}

@media (max-width: 991.98px) {
    .navbar-collapse {
        background: var(--glass-bg);
        backdrop-filter: blur(14px);
        border-radius: var(--radius-md);
        padding: 18px;
        box-shadow: var(--shadow-soft);
        animation: slideDown 0.4s ease;
    }

    .navbar-nav .nav-item {
        margin-bottom: 6px;
    }

    .dropdown-click-menu {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        margin-top: 6px;
        background: rgba(248,249,250,0.9);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.nav-link:focus,
.dropdown-item:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(13,110,253,0.25);
}
.dropdown-click-menu {
    display: block !important;
    pointer-events: none;
}

.dropdown-manual-show > .dropdown-click-menu {
    display: block !important;
    pointer-events: auto;
}

</style>



<!-- Font Awesome for Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Variable to track current open dropdown
let currentOpenDropdown = null;

function updateClock() {
    const clock = document.getElementById('realtime-clock');
    const now = new Date();

    // Format jam: HH:MM:SS
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    clock.textContent = `${hours}:${minutes}:${seconds}`;
}

// Update setiap detik
setInterval(updateClock, 1000);

// Inisialisasi saat halaman load
updateClock();

function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const dropdownMenu = dropdown.querySelector('.dropdown-click-menu');
    
    // Close all other dropdowns
    closeAllDropdowns();
    
    // If clicking the same dropdown, toggle it
    if (currentOpenDropdown === dropdownId) {
        dropdown.classList.remove('dropdown-manual-show');
        currentOpenDropdown = null;
    } else {
        // Open the clicked dropdown
        dropdown.classList.add('dropdown-manual-show');
        currentOpenDropdown = dropdownId;
        
        // Add click outside listener
        setTimeout(() => {
            document.addEventListener('click', closeDropdownOnClickOutside);
        }, 10);
    }
}

function closeAllDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        dropdown.classList.remove('dropdown-manual-show');
    });
    currentOpenDropdown = null;
    document.removeEventListener('click', closeDropdownOnClickOutside);
}

function closeDropdownOnClickOutside(event) {
    const dropdowns = document.querySelectorAll('.dropdown');
    let clickedInsideDropdown = false;
    
    dropdowns.forEach(dropdown => {
        if (dropdown.contains(event.target)) {
            clickedInsideDropdown = true;
        }
    });
    
    if (!clickedInsideDropdown) {
        closeAllDropdowns();
    }
}

// Close dropdowns when clicking on a dropdown item
document.addEventListener('DOMContentLoaded', function() {
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('click', function() {
            closeAllDropdowns();
        });
    });
});

// Close dropdowns when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAllDropdowns();
    }
});
</script>