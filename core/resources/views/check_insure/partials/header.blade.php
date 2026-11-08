<header class="header">
    <div class="container-fluid">
        <button class="btn btn-icon navbar-toggle" type="button">
            <span class="group">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
        <div class="navbar-brand">
            <a href="{{ route('checkinsure.register') }}">
                <img src="{{ asset('check-insure/img/logo.svg') }}" alt="Check Insure">
            </a>
        </div>
        <ul class="nav nav-general d-none d-sm-flex">
            @guest
                <li>
                    <a href="{{ route('checkinsure.login') }}" class="text-white text-decoration-none">Sign in</a>
                </li>
            @endguest
            <li class="nav-lang dropdown">
                <a href="#" data-bs-toggle="dropdown" data-bs-display="static">EN</a>
                <ul class="dropdown-menu">
                    <li class="active"><a href="#">EN</a></li>
                    <li><a href="#">TH</a></li>
                </ul>
            </li>
            @auth
                @if(auth()->user()->registration_channel === 'third_party')
                    <li>
                        <form action="{{ route('checkinsure.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-link text-white text-decoration-none p-0" type="submit">Logout</button>
                        </form>
                    </li>
                @endif
            @endauth
        </ul>
    </div>
</header>

<div class="navbar-slider">
    <ul class="nav nav-sidebar">
        <li class="{{ request()->routeIs('checkinsure.register') ? 'active' : '' }}">
            <a class="nav-title" href="{{ route('checkinsure.register') }}">Home</a>
        </li>
        @guest
            <li class="{{ request()->routeIs('checkinsure.login') ? 'active' : '' }}">
                <a class="nav-title" href="{{ route('checkinsure.login') }}">Sign in</a>
            </li>
        @endguest
        @auth
            <li class="{{ request()->routeIs('checkinsure.profile', 'checkinsure.survey') ? 'active' : '' }}">
                <a class="nav-title" href="{{ route('checkinsure.profile') }}">Get a quote</a>
            </li>
            @if(session('checkinsure_criteria'))
                <li class="{{ request()->routeIs('checkinsure.results') ? 'active' : '' }}">
                    <a class="nav-title" href="{{ route('checkinsure.results') }}">Your results</a>
                </li>
            @endif
        @endauth
        <li><a class="nav-title" href="mailto:{{ config('toapp.support_email', 'tonkowtek@to-app.com') }}">@lang('Contact us')</a></li>
    </ul>
</div>
