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
            padding: 8px 0 48px;
        }

        .to-home__hero {
            display: grid;
            grid-template-columns: minmax(0, 1.02fr) minmax(320px, .82fr);
            gap: 34px;
            align-items: start;
            padding: 16px 0 38px;
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

        .to-home__member-note {
            margin: 16px 0 0;
            max-width: 34rem;
            color: var(--to-muted);
            font-size: 14px;
            font-weight: 600;
            line-height: 1.55;
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
            display: grid;
            gap: 14px;
            min-height: 0;
            padding: 0;
        }

        .to-home__status-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            align-items: stretch;
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
            min-height: 420px;
            height: auto;
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
            min-height: 376px;
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
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 0;
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

        .to-floating,
        .to-mini {
            position: static;
            width: 100%;
            min-height: 72px;
            padding: 14px;
            z-index: 1;
            box-sizing: border-box;
        }

        .to-floating span,
        .to-mini span {
            display: block;
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 600;
            line-height: 1.4;
        }

        .to-floating strong,
        .to-mini strong {
            display: block;
            margin-top: 4px;
            color: var(--to-ink);
            font-size: 18px;
            font-weight: 600;
            line-height: 1.2;
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

        @media (min-width: 1200px) {
            .to-home__shell {
                padding-top: 8px;
            }
        }

        @media (max-width: 991px) {
            .to-home__hero {
                grid-template-columns: 1fr;
                padding-top: 12px;
            }

            .to-hero-card {
                min-height: 360px;
            }

            .to-hero-card__content {
                min-height: 316px;
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
                padding: 4px 0 32px;
            }

            .to-home__hero {
                gap: 22px;
                padding: 10px 0 24px;
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
                gap: 10px;
            }

            .to-home__status-row {
                gap: 10px;
            }

            .to-hero-card {
                min-height: 300px;
                border-radius: 28px;
                padding: 16px;
            }

            .to-hero-card__content {
                min-height: 268px;
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

            .to-floating,
            .to-mini {
                width: 100%;
                min-height: 68px;
                padding: 12px;
                border-radius: 22px;
            }

            .to-floating strong,
            .to-mini strong {
                font-size: 18px;
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

            .to-hero-card {
                min-height: 280px;
            }

            .to-hero-card__content {
                min-height: 248px;
            }
        }

        @media (max-width: 374px) {
            .to-home__shell {
                width: min(100% - 14px, 1120px);
                padding-bottom: 26px;
            }

            .to-home__hero {
                padding-top: 8px;
            }

            .to-home h1 {
                font-size: 28px;
                line-height: 1.2;
            }

            .to-btn {
                min-height: 52px;
                font-size: 15px;
            }

            .to-hero-card {
                min-height: 250px;
                padding: 12px;
                border-radius: 24px;
            }

            .to-hero-card__content {
                min-height: 226px;
            }

            .to-family-panel {
                padding: 12px;
            }

            .to-family-panel__badge {
                display: none;
            }

            .to-mini,
            .to-floating {
                width: 100%;
            }
        }

        @media (max-height: 520px) and (orientation: landscape) {
            .to-home__shell {
                width: min(980px, calc(100% - 28px));
                padding-top: 12px;
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

            .to-hero-card {
                min-height: 220px;
                border-radius: 24px;
            }

            .to-hero-card__content {
                min-height: 196px;
            }

            .to-home__status-row {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    @include($activeTemplate . 'partials.public_nav')

    <main class="page-boxed to-home">
        <div class="to-home__shell">
            <section class="to-home__hero">
                <div>
                    <div class="to-home__eyebrow">
                        To-App <strong>@lang('Get Started')</strong>
                    </div>
                    <h1>
                        @lang('Get Started with To-App')
                    </h1>
                    <p class="to-home__lead">
                        @lang('Create an account to connect with insurance agents, access your referral code, and track your referral activity and eligible rewards.')
                    </p>

                    <div class="to-home__cta" aria-label="@lang('Account actions')">
                        <a class="to-btn to-btn--primary" href="{{ route('user.register') }}">
                            @lang('Create Account')
                        </a>
                        <a class="to-btn to-btn--ghost" href="{{ route('user.login') }}">
                            @lang('Log In')
                        </a>
                    </div>

                    <p class="to-home__member-note">
                        @lang('Already a member? Log in to access your dashboard.')
                    </p>
                </div>

                <div class="to-home__visual" aria-hidden="true">
                    <div class="to-home__status-row">
                        <div class="to-card to-mini">
                            <span>@lang('Status')</span>
                            <strong>@lang('Ready')</strong>
                        </div>
                        <div class="to-card to-floating">
                            <span>@lang('Access')</span>
                            <strong>24/7</strong>
                        </div>
                    </div>

                    <div class="to-card to-hero-card">
                        <div class="to-hero-card__image"></div>
                        <div class="to-hero-card__content">
                            <div class="to-family-panel">
                                <div class="to-family-panel__top">
                                    <div>
                                        <h2>@lang('Your referral space')</h2>
                                        <p>@lang('Connect with agents, share your code, and follow eligible rewards in one place.')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="to-home__features" aria-label="@lang('Get started steps')">
                <article class="to-card to-feature">
                    <div class="to-feature__icon">1</div>
                    <h3>@lang('Create Your Account')</h3>
                    <p>@lang('Sign up using the referral code you received from an existing member.')</p>
                </article>
                <article class="to-card to-feature">
                    <div class="to-feature__icon">2</div>
                    <h3>@lang('Explore and Connect')</h3>
                    <p>@lang('Explore available insurance options and connect with an agent to learn more.')</p>
                </article>
                <article class="to-card to-feature">
                    <div class="to-feature__icon">3</div>
                    <h3>@lang('Share and Track')</h3>
                    <p>@lang('Share your referral code and track your referral activity. Rewards are available only for referrals that meet the applicable program requirements.')</p>
                </article>
            </section>
        </div>
    </main>
@endsection
