{{-- resources/views/components/navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="KTT Logo" height="40" class="me-2">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Login</a>
                    </li>
                @else
                    @foreach([
                        ['url' => '/homeDb', 'text' => 'Home'],
                        ['url' => '/sub-agent', 'text' => 'Sub Agent'],
                        ['url' => '/pln', 'text' => 'PLN'],
                        ['url' => '/admin', 'text' => 'Admin']
                    ] as $item)
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is(trim($item['url'], '/')) ? 'fw-bold text-primary' : '' }}" 
                           href="{{ url($item['url']) }}">{{ $item['text'] }}</a>
                    </li>
                    @endforeach
                    
                    <li class="nav-item ms-3">
                        <div class="dropdown">
                            <a class="btn btn-outline-primary btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
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
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>