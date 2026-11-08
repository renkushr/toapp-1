@php
    $homeUrl = route('home');
    $isHome = request()->routeIs('home');
    $isGetStarted = request()->routeIs('get.started');
    $isAbout = request()->routeIs('about.us');
    $isContact = request()->routeIs('contact');

    $aboutHref = $isHome ? '#about' : $homeUrl . '#about';
    $servicesHref = $isHome ? '#services' : $homeUrl . '#services';
    $productsHref = $isHome ? '#products' : $homeUrl . '#products';
    $contactHref = $isHome ? '#contact' : $homeUrl . '#contact';
@endphp

@once
    @push('style')
    <style>
        :root {
            --lp-ink: #0b2033;
            --lp-muted: #5b6b7f;
            --lp-blue: #1268f3;
            --lp-cyan: #13c8d6;
        }

        .lp-nav {
            position: sticky;
            top: 12px;
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin: 12px auto 0;
            padding: 12px 16px;
            width: min(1120px, calc(100% - 2rem));
            border: 1px solid rgba(255,255,255,.7);
            border-radius: 22px;
            background: rgba(255, 255, 255, .92);
            box-shadow: 0 10px 28px rgba(15, 23, 42, .08);
            backdrop-filter: blur(14px);
        }

        .lp-brand {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
            color: var(--lp-ink);
        }

        .lp-brand img {
            width: 92px;
            height: auto;
        }

        .lp-brand strong {
            font-size: 1.05rem;
            letter-spacing: -.02em;
        }

        .lp-nav__links {
            display: none;
            align-items: center;
            gap: 1.1rem;
        }

        .lp-nav__links a {
            color: var(--lp-muted);
            text-decoration: none;
            font-size: .92rem;
            font-weight: 600;
        }

        .lp-nav__links a:hover,
        .lp-nav__links a.is-active {
            color: var(--lp-blue);
        }

        .lp-nav__cta {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
        }

        .lp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 1.15rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: .92rem;
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .lp-btn:hover {
            transform: translateY(-1px);
            text-decoration: none;
        }

        .lp-btn--primary {
            color: #fff;
            background: linear-gradient(135deg, var(--lp-blue), var(--lp-cyan));
            box-shadow: 0 14px 30px rgba(18, 104, 243, .28);
        }

        .lp-btn--ghost {
            color: #0b4f8a;
            background: rgba(255,255,255,.88);
            border: 1px solid rgba(18, 104, 243, .22);
        }

        @media (min-width: 900px) {
            .lp-nav__links { display: inline-flex; }
        }

        @media (max-width: 640px) {
            .lp-brand strong { display: none; }
            .lp-nav__cta .lp-btn--ghost { display: none; }
        }
    </style>
    @endpush
@endonce

<nav class="lp-nav" aria-label="@lang('To-App quick access')">
    <a class="lp-brand" href="{{ $homeUrl }}" aria-label="@lang('To-App home')">
        <img src="{{ asset('assets/global/img/tologo.png') }}" alt="To-App">
        <strong>To-App</strong>
    </a>
    <div class="lp-nav__links">
        <a href="{{ $aboutHref }}" @class(['is-active' => $isAbout])>@lang('About Us')</a>
        <a href="{{ $servicesHref }}">@lang('Our Services')</a>
        <a href="{{ $productsHref }}">@lang('Our Product')</a>
        <a href="{{ $contactHref }}" @class(['is-active' => $isContact])>@lang('Contact Us')</a>
    </div>
    <div class="lp-nav__cta">
        @include('partials.language_switcher', ['variant' => 'compact'])
        <a class="lp-btn lp-btn--ghost" href="{{ route('user.login') }}">@lang('Log In')</a>
        @if($isGetStarted)
            <a class="lp-btn lp-btn--primary" href="{{ route('user.register') }}">@lang('Create Account')</a>
        @else
            <a class="lp-btn lp-btn--primary" href="{{ route('get.started') }}">@lang('Get started')</a>
        @endif
    </div>
</nav>
