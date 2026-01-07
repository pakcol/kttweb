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
                            <li><a class="dropdown-item" href="{{ url('/insentif') }}">Insentif</a></li>
                            <li><a class="dropdown-item" href="{{ url('/buku-bank') }}">Buku Bank</a></li>
                        </ul>
                    </li>
                    @endif

                    {{-- Dropdown user --}}
                    <li>
                        <a class="nav-link fw-medium text-dark" 
                           href="#" 
                           aria-expanded="false">
                            {{ Auth::user()->name }}
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
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar-nav .nav-link {
        padding: 8px 12px;
        transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
        color: #0d6efd !important;
    }

    /* Dropdown Styles */
    .dropdown-click-menu {
        border: none;
        border-radius: 12px;
        padding: 8px 0;
        margin-top: 8px;
        background: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.1);
        min-width: 200px;
    }

    .dropdown-item {
        padding: 10px 20px;
        transition: all 0.3s ease;
        color: #495057;
        text-decoration: none;
        display: block;
        border-left: 3px solid transparent;
    }

    .dropdown-item:hover {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.1), rgba(111, 66, 193, 0.05));
        color: #0d6efd !important;
        border-left-color: #0d6efd;
        transform: translateX(5px);
    }

    .dropdown-item.text-danger:hover {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));
        color: #dc3545 !important;
        border-left-color: #dc3545;
    }

    /* Dropdown arrow animation */
    .dropdown-toggle::after {
        transition: transform 0.3s ease;
        margin-left: 5px;
    }

    .dropdown.show .dropdown-toggle::after {
        transform: rotate(180deg);
    }

    /* Active state for parent links */
    .nav-link.text-primary {
        position: relative;
    }

    .nav-link.text-primary::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 12px;
        right: 12px;
        height: 2px;
        background: #0d6efd;
        border-radius: 2px;
    }

    /* Custom dropdown show state */
    .dropdown-manual-show .dropdown-click-menu {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
    }

    /* Mobile Responsive */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background-color: #fff;
            padding: 1rem 1.2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .navbar-nav .nav-item {
            margin-bottom: 0.5rem;
        }
        
        .dropdown-click-menu {
            position: static;
            transform: none;
            box-shadow: none;
            margin-top: 0;
            background: rgba(248, 249, 250, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dropdown-item:hover {
            transform: translateX(8px);
        }
    }

    /* Smooth transitions */
    .navbar-nav .nav-link,
    .dropdown-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Focus states for accessibility */
    .nav-link:focus,
    .dropdown-item:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.3);
    }
</style>

<!-- Font Awesome for Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Variable to track current open dropdown
let currentOpenDropdown = null;

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