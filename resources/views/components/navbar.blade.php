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
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm px-3 ms-lg-2" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                @else
                    {{-- Menu untuk user login --}}
                    @foreach([
                        ['url' => '/homeDb', 'text' => 'Home'],
                        ['url' => '/sub-agent', 'text' => 'Sub Agent'],
                        ['url' => '/pln', 'text' => 'PLN'],
                        ['url' => '/admin', 'text' => 'Admin']
                    ] as $item)
                        <li class="nav-item">
                            <a class="nav-link fw-medium {{ request()->is(trim($item['url'], '/')) ? 'text-primary fw-semibold' : 'text-dark' }}"
                               href="{{ url($item['url']) }}">
                                {{ $item['text'] }}
                            </a>
                        </li>
                    @endforeach

                    {{-- Dropdown user --}}
                    <li class="nav-item dropdown">
                        <a class="btn btn-outline-primary btn-sm dropdown-toggle ms-lg-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar-nav .nav-link {
        padding: 8px 12px;
        transition: color 0.2s ease;
    }

    .navbar-nav .nav-link:hover {
        color: #0d6efd !important;
    }

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
    }
</style>
