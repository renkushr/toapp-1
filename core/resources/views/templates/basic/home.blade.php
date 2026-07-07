@extends($activeTemplate . 'layouts.frontend')

@push('style-lib')
    <link rel="shortcut icon" href="{{ asset('assets/images/logo_icon/favicon.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('assets/images/logo_icon/favicon.png') }}" type="image/png">
@endpush

@push('style')
    <style>
        :root {
            --to-ink: #10243f;
            --to-muted: #6a7890;
            --to-blue: #1e84f4;
            --to-cyan: #18abcf;
            --to-orange: #ffb832;
            --to-line: rgba(24, 171, 207, .18);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(24, 171, 207, .2), transparent 34rem),
                radial-gradient(circle at 85% 18%, rgba(255, 184, 50, .22), transparent 22rem),
                linear-gradient(180deg, #f7fcff 0%, #ffffff 44%, #edf7fb 100%) !important;
        }

        .page-boxed {
            background: transparent !important;
        }

        .to-home {
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            color: var(--to-ink);
            font-family: Poppins, sans-serif;
        }

        .to-home::before {
            position: absolute;
            inset: 0;
            z-index: 0;
            content: "";
            pointer-events: none;
            background-image:
                linear-gradient(rgba(30, 132, 244, .055) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 132, 244, .055) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: linear-gradient(180deg, #000 0%, transparent 68%);
        }

        .to-home__shell {
            position: relative;
            z-index: 1;
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 24px 0 48px;
        }

        .to-home__nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 14px 16px;
            border: 1px solid rgba(255, 255, 255, .72);
            border-radius: 28px;
            background: rgba(255, 255, 255, .72);
            box-shadow: 0 22px 70px rgba(15, 58, 95, .1);
            backdrop-filter: blur(18px);
        }

        .to-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--to-ink);
            text-decoration: none;
        }

        .to-brand img {
            width: 104px;
            height: auto;
        }

        .to-brand span {
            display: none;
            color: var(--to-muted);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .02em;
        }

        .to-home__nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .to-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border: 1px solid var(--to-line);
            border-radius: 999px;
            background: #fff;
            color: #2d597b;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 10px 24px rgba(24, 171, 207, .09);
        }

        .to-chip::before {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--to-cyan), var(--to-blue));
            content: "";
        }

        .to-home__hero {
            display: grid;
            grid-template-columns: minmax(0, 1.02fr) minmax(320px, .82fr);
            gap: 34px;
            align-items: center;
            padding: 70px 0 38px;
        }

        .to-home__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 10px 14px;
            border: 1px solid rgba(24, 171, 207, .22);
            border-radius: 999px;
            background: rgba(255, 255, 255, .78);
            color: #24627e;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 12px 36px rgba(24, 171, 207, .1);
        }

        .to-home__eyebrow strong {
            color: var(--to-blue);
        }

        .to-home h1 {
            max-width: 720px;
            margin: 0;
            color: var(--to-ink);
            font-size: clamp(34px, 5.6vw, 70px);
            font-weight: 600;
            letter-spacing: 0;
            line-height: 1.12;
            word-spacing: .14em;
        }

        .to-home h1 span {
            display: block;
            background: linear-gradient(120deg, var(--to-cyan), var(--to-blue) 56%, var(--to-orange));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .to-home__lead {
            max-width: 590px;
            margin: 22px 0 0;
            color: var(--to-muted);
            font-size: clamp(16px, 2vw, 19px);
            line-height: 1.75;
        }

        .to-home__cta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 32px;
        }

        .to-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 58px;
            padding: 0 28px;
            border-radius: 18px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .to-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
        }

        .to-btn--primary {
            border: 0;
            background: linear-gradient(135deg, var(--to-cyan), var(--to-blue));
            color: #fff;
            box-shadow: 0 18px 40px rgba(30, 132, 244, .26);
        }

        .to-btn--ghost {
            border: 1px solid rgba(30, 132, 244, .28);
            background: rgba(255, 255, 255, .86);
            color: #07568f;
            box-shadow: 0 14px 34px rgba(15, 58, 95, .08);
        }

        .to-home__proof {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
            color: #587089;
            font-size: 13px;
            font-weight: 600;
        }

        .to-home__proof span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .to-home__proof span::before {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #0ba779;
            box-shadow: 0 0 0 6px #e7f9f4;
            content: "";
        }

        .to-home__visual {
            position: relative;
            min-height: 520px;
        }

        .to-card {
            border: 1px solid rgba(255, 255, 255, .8);
            border-radius: 34px;
            background: rgba(255, 255, 255, .84);
            box-shadow: 0 28px 80px rgba(16, 36, 63, .13);
            backdrop-filter: blur(18px);
        }

        .to-hero-card {
            position: relative;
            overflow: hidden;
            height: 500px;
            padding: 22px;
        }

        .to-hero-card__image {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .2), rgba(255, 255, 255, .92) 74%),
                url("{{ asset('assets/global/img/tablet-ver.png') }}") center top / cover no-repeat;
        }

        .to-hero-card__content {
            position: relative;
            z-index: 1;
            display: flex;
            height: 100%;
            flex-direction: column;
            justify-content: flex-end;
        }

        .to-family-panel {
            padding: 18px;
            border: 1px solid rgba(30, 132, 244, .12);
            border-radius: 26px;
            background: rgba(255, 255, 255, .86);
        }

        .to-family-panel__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .to-family-panel h2 {
            margin: 0;
            color: var(--to-ink);
            font-size: 21px;
            font-weight: 600;
            letter-spacing: -.02em;
        }

        .to-family-panel p {
            margin: 5px 0 0;
            color: var(--to-muted);
            font-size: 13px;
            line-height: 1.55;
        }

        .to-family-panel__badge {
            padding: 8px 11px;
            border-radius: 999px;
            background: #fff5d8;
            color: #9b6500;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .to-meter {
            overflow: hidden;
            height: 10px;
            border-radius: 999px;
            background: #e9f3fb;
        }

        .to-meter span {
            display: block;
            width: 78%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--to-cyan), var(--to-blue), var(--to-orange));
        }

        .to-floating {
            position: absolute;
            right: -12px;
            bottom: 74px;
            width: 210px;
            padding: 16px;
            z-index: 2;
        }

        .to-floating strong {
            display: block;
            color: var(--to-ink);
            font-size: 30px;
            font-weight: 600;
            line-height: 1;
        }

        .to-floating span {
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .to-mini {
            position: absolute;
            top: 30px;
            left: -20px;
            width: 178px;
            padding: 14px;
            z-index: 2;
        }

        .to-mini span {
            display: block;
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .to-mini strong {
            display: block;
            margin-top: 4px;
            color: var(--to-ink);
            font-size: 18px;
            font-weight: 600;
        }

        .to-home__features {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-top: 8px;
        }

        .to-feature {
            position: relative;
            overflow: hidden;
            padding: 24px;
        }

        .to-feature::after {
            position: absolute;
            right: -24px;
            bottom: -28px;
            width: 96px;
            height: 96px;
            border-radius: 32px;
            background: rgba(24, 171, 207, .1);
            content: "";
            transform: rotate(18deg);
        }

        .to-feature__icon {
            display: grid;
            width: 46px;
            height: 46px;
            margin-bottom: 18px;
            place-items: center;
            border-radius: 16px;
            background: linear-gradient(135deg, #e9fbff, #fff4d1);
            color: var(--to-blue);
            font-size: 22px;
            font-weight: 600;
        }

        .to-feature h3 {
            margin: 0 0 8px;
            color: var(--to-ink);
            font-size: 18px;
            font-weight: 600;
        }

        .to-feature p {
            margin: 0;
            color: var(--to-muted);
            font-size: 14px;
            line-height: 1.65;
        }

        @media (min-width: 768px) {
            .to-brand span {
                display: inline;
            }
        }

        @media (min-width: 1200px) {
            .to-home__shell {
                padding-top: 32px;
            }

            .to-home__hero {
                min-height: 650px;
            }
        }

        @media (max-width: 991px) {
            .to-home__hero {
                grid-template-columns: 1fr;
                padding-top: 46px;
            }

            .to-home__visual {
                min-height: 440px;
            }

            .to-hero-card {
                height: 420px;
            }

            .to-floating {
                right: 12px;
            }

            .to-mini {
                left: 12px;
            }

            .to-home__features {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 576px) and (max-width: 991px) {
            .to-home__shell {
                width: min(720px, calc(100% - 34px));
            }

            .to-home h1 {
                max-width: 640px;
                font-size: clamp(44px, 8vw, 60px);
            }

            .to-home__cta {
                max-width: 520px;
            }

            .to-home__features {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .to-feature {
                padding: 20px;
            }
        }

        @media (max-width: 575px) {
            .to-home__shell {
                width: min(100% - 22px, 1120px);
                padding: 12px 0 32px;
            }

            .to-home__nav {
                justify-content: center;
                align-items: flex-start;
                padding: 13px 14px;
                border-radius: 22px;
            }

            .to-brand img {
                width: 92px;
            }

            .to-chip {
                display: none;
            }

            .to-home__hero {
                gap: 22px;
                padding: 30px 0 24px;
            }

            .to-home h1 {
                max-width: 100%;
                font-size: clamp(31px, 9.6vw, 44px);
                letter-spacing: 0;
                line-height: 1.16;
                word-spacing: .08em;
            }

            .to-home__lead {
                margin-top: 16px;
                font-size: 14.5px;
                line-height: 1.65;
            }

            .to-home__cta {
                display: grid;
                grid-template-columns: 1fr;
                gap: 12px;
                margin-top: 24px;
            }

            .to-btn {
                width: 100%;
                min-height: 56px;
                border-radius: 16px;
            }

            .to-home__visual {
                min-height: 360px;
            }

            .to-hero-card {
                height: 350px;
                border-radius: 28px;
                padding: 16px;
            }

            .to-family-panel {
                padding: 15px;
                border-radius: 22px;
            }

            .to-family-panel__top {
                align-items: flex-start;
                margin-bottom: 14px;
            }

            .to-family-panel h2 {
                font-size: 18px;
            }

            .to-family-panel p {
                font-size: 12.5px;
            }

            .to-family-panel__badge {
                padding: 7px 9px;
                font-size: 11px;
            }

            .to-home__proof {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
                margin-top: 24px;
                font-size: 13px;
            }

            .to-floating {
                right: 8px;
                bottom: 34px;
                width: min(168px, 48vw);
                padding: 13px;
                border-radius: 22px;
            }

            .to-floating strong {
                font-size: 24px;
            }

            .to-mini {
                top: 18px;
                left: 8px;
                width: min(148px, 44vw);
                padding: 12px;
                border-radius: 22px;
            }

            .to-feature {
                padding: 20px;
                border-radius: 26px;
            }
        }

        @media (max-width: 430px) {
            .to-home__shell {
                width: min(100% - 18px, 1120px);
            }

            .to-home__eyebrow {
                margin-bottom: 16px;
                padding: 9px 12px;
                font-size: 12px;
            }

            .to-home h1 {
                font-size: clamp(29px, 9.3vw, 39px);
                line-height: 1.18;
                word-spacing: .05em;
            }

            .to-home__lead {
                font-size: 14px;
            }

            .to-home__visual {
                min-height: 330px;
            }

            .to-hero-card {
                height: 318px;
            }

            .to-floating {
                bottom: 22px;
            }
        }

        @media (max-width: 374px) {
            .to-home__shell {
                width: min(100% - 14px, 1120px);
                padding-bottom: 26px;
            }

            .to-brand img {
                width: 82px;
            }

            .to-home__hero {
                padding-top: 24px;
            }

            .to-home h1 {
                font-size: 28px;
                line-height: 1.2;
            }

            .to-btn {
                min-height: 52px;
                font-size: 15px;
            }

            .to-home__visual {
                min-height: 298px;
            }

            .to-hero-card {
                height: 288px;
                padding: 12px;
                border-radius: 24px;
            }

            .to-family-panel {
                padding: 12px;
            }

            .to-family-panel__badge,
            .to-mini {
                display: none;
            }

            .to-floating {
                right: 10px;
                bottom: 18px;
                width: 150px;
            }
        }

        @media (max-height: 520px) and (orientation: landscape) {
            .to-home__shell {
                width: min(980px, calc(100% - 28px));
                padding-top: 12px;
            }

            .to-home__nav {
                padding: 10px 14px;
            }

            .to-brand img {
                width: 84px;
            }

            .to-home__hero {
                grid-template-columns: minmax(0, 1fr) minmax(220px, .55fr);
                gap: 20px;
                padding: 24px 0;
            }

            .to-home h1 {
                font-size: clamp(30px, 5.2vw, 46px);
            }

            .to-home__lead {
                margin-top: 12px;
                font-size: 14px;
                line-height: 1.55;
            }

            .to-home__cta,
            .to-home__proof {
                margin-top: 18px;
            }

            .to-home__visual {
                min-height: 280px;
            }

            .to-hero-card {
                height: 280px;
                border-radius: 24px;
            }

            .to-floating,
            .to-mini {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <main class="page-boxed to-home">
        <div class="to-home__shell">
            <nav class="to-home__nav" aria-label="To-app quick access">
                <a class="to-brand" href="{{ route('home') }}" aria-label="To-app home">
                    <img src="{{ asset('assets/global/img/tologo.png') }}" alt="To-app">
                    <span>@lang('Family protection made simple')</span>
                </a>
                <div class="to-home__nav-actions">
                    <span class="to-chip">@lang('Secure member portal')</span>
                </div>
            </nav>

            <section class="to-home__hero">
                <div>
                    <div class="to-home__eyebrow">
                        @lang('Get started') <strong>@lang('Together')</strong>
                    </div>
                    <h1>
                        @lang('Protect your family,') <span>@lang('plan your future.')</span>
                    </h1>
                    <p class="to-home__lead">
                        @lang('Manage your plan, deposits, withdrawals, and support requests from one clear member experience built for real daily use.')
                    </p>

                    <div class="to-home__cta" aria-label="Account actions">
                        <a class="to-btn to-btn--primary" href="{{ route('user.register') }}">
                            @lang('Create Account')
                        </a>
                        <a class="to-btn to-btn--ghost" href="{{ route('user.login') }}">
                            @lang('Login')
                        </a>
                    </div>

                    <div class="to-home__proof" aria-label="Platform highlights">
                        <span>@lang('Fast onboarding')</span>
                        <span>@lang('Clear plan tracking')</span>
                        <span>@lang('Member support')</span>
                    </div>
                </div>

                <div class="to-home__visual" aria-hidden="true">
                    <div class="to-card to-mini">
                        <span>@lang('Status')</span>
                        <strong>@lang('Ready')</strong>
                    </div>

                    <div class="to-card to-hero-card">
                        <div class="to-hero-card__image"></div>
                        <div class="to-hero-card__content">
                            <div class="to-family-panel">
                                <div class="to-family-panel__top">
                                    <div>
                                        <h2>@lang('Your family plan')</h2>
                                        <p>@lang('A cleaner place to follow coverage, payments, and requests.')</p>
                                    </div>
                                    <span class="to-family-panel__badge">@lang('78% ready')</span>
                                </div>
                                <div class="to-meter"><span></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="to-card to-floating">
                        <strong>24/7</strong>
                        <span>@lang('Access to your account and request history')</span>
                    </div>
                </div>
            </section>

            <section class="to-home__features" aria-label="What To-app helps with">
                <article class="to-card to-feature">
                    <div class="to-feature__icon">1</div>
                    <h3>@lang('Choose a plan')</h3>
                    <p>@lang('Review available plans with clearer pricing, duration, and member benefits before joining.')</p>
                </article>
                <article class="to-card to-feature">
                    <div class="to-feature__icon">2</div>
                    <h3>@lang('Track money flow')</h3>
                    <p>@lang('See deposits, withdrawals, and approval status without guessing where each request stands.')</p>
                </article>
                <article class="to-card to-feature">
                    <div class="to-feature__icon">3</div>
                    <h3>@lang('Get help faster')</h3>
                    <p>@lang('Support tickets and account actions stay organized so users know the next step clearly.')</p>
                </article>
            </section>
        </div>
    </main>
@endsection
