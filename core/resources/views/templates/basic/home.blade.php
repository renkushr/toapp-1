@extends($activeTemplate . 'layouts.frontend')

@push('style')
<style>
:root {
    --lp-ink: #0b2033;
    --lp-muted: #5b6b7f;
    --lp-blue: #1268f3;
    --lp-cyan: #13c8d6;
    --lp-line: rgba(18, 104, 243, .14);
    --lp-paper: rgba(255, 255, 255, .9);
}

html { scroll-behavior: smooth; }

body {
    background: #f3f7fb !important;
    color: var(--lp-ink);
    font-family: Poppins, sans-serif;
}

.lp {
    overflow-x: hidden;
    background: #f3f7fb;
}

.lp-shell {
    width: min(1120px, calc(100% - 2rem));
    margin: 0 auto;
}

.lp-hero {
    position: relative;
    min-height: min(84vh, 720px);
    display: grid;
    align-items: center;
    padding: 5.5rem 0 4rem;
    isolation: isolate;
    overflow: hidden;
    background: #dceef8;
}

.lp-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -2;
    background:
        url("{{ asset('assets/images/landing/hero-banner.jpg') }}") center right / cover no-repeat;
}

.lp-hero::after {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -1;
    pointer-events: none;
    background:
        linear-gradient(
            90deg,
            rgba(243, 247, 251, .96) 0%,
            rgba(243, 247, 251, .78) 26%,
            rgba(243, 247, 251, .2) 48%,
            transparent 64%
        ),
        linear-gradient(
            180deg,
            transparent 70%,
            rgba(243, 247, 251, .55) 88%,
            #f3f7fb 100%
        );
}

.lp-hero__copy {
    max-width: 560px;
    color: var(--lp-ink);
    animation: lpRise .7s ease both;
}

.lp-hero__brand {
    display: inline-block;
    margin: 0 0 .85rem;
    font-size: clamp(2.6rem, 8vw, 4.8rem);
    font-weight: 800;
    letter-spacing: -.06em;
    line-height: .95;
    background: linear-gradient(135deg, var(--lp-blue), var(--lp-cyan));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.lp-hero h1 {
    margin: 0;
    font-size: clamp(1.55rem, 3.6vw, 2.35rem);
    font-weight: 600;
    letter-spacing: -.03em;
    line-height: 1.2;
    color: var(--lp-ink);
}

.lp-hero p {
    margin: .9rem 0 0;
    max-width: 34rem;
    color: var(--lp-muted);
    font-size: 1.05rem;
    line-height: 1.55;
}

.lp-hero__actions {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    margin-top: 1.5rem;
}

.lp-section {
    position: relative;
    padding: 4.75rem 0;
    background: #fff;
}

.lp-section--tint {
    background: #eaf3fa;
}

.lp-section--soft {
    background: #f7fbff;
}

.lp-kicker {
    display: inline-block;
    margin-bottom: .75rem;
    color: var(--lp-blue);
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.lp-section h2 {
    margin: 0;
    font-size: clamp(1.7rem, 3.2vw, 2.4rem);
    font-weight: 700;
    letter-spacing: -.04em;
    line-height: 1.15;
}

.lp-section__lead {
    margin: .85rem 0 0;
    max-width: 36rem;
    color: var(--lp-muted);
    font-size: 1.02rem;
    line-height: 1.6;
}

.lp-about__body {
    margin-top: 1.5rem;
    max-width: 40rem;
}

.lp-about__body p {
    margin: 0;
    color: var(--lp-muted);
    font-size: 1.05rem;
    line-height: 1.7;
}

.lp-about__actions {
    margin-top: 1.35rem;
}

.lp-modal {
    position: fixed;
    inset: 0;
    z-index: 1200;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
}

.lp-modal.is-open {
    display: flex;
}

.lp-modal__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(11, 32, 51, .55);
    backdrop-filter: blur(4px);
}

.lp-modal__dialog {
    position: relative;
    z-index: 1;
    width: min(560px, 100%);
    max-height: min(82vh, 720px);
    overflow: auto;
    padding: 1.6rem 1.45rem 1.4rem;
    border-radius: 28px;
    background: linear-gradient(180deg, #fff, #f5faff);
    border: 1px solid rgba(255,255,255,.9);
    box-shadow: 0 28px 70px rgba(15, 23, 42, .28);
    animation: lpRise .45s ease both;
}

.lp-modal__dialog h3 {
    margin: 0 2rem 1rem 0;
    font-size: 1.35rem;
    font-weight: 700;
    letter-spacing: -.03em;
}

.lp-modal__dialog p {
    margin: 0;
    color: var(--lp-muted);
    line-height: 1.7;
}

.lp-modal__dialog p + p {
    margin-top: 1rem;
}

.lp-modal__tagline {
    margin-top: 1.25rem !important;
    color: var(--lp-ink) !important;
    font-weight: 700;
    letter-spacing: -.02em;
}

.lp-modal__close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
    border: 0;
    border-radius: 999px;
    background: rgba(18, 104, 243, .08);
    color: var(--lp-ink);
    font-size: 1.35rem;
    line-height: 1;
    cursor: pointer;
}

.lp-modal__close:hover {
    background: rgba(18, 104, 243, .14);
}

.lp-modal__footer {
    margin-top: 1.4rem;
}

.lp-services {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 2rem;
}

.lp-service {
    padding: 1.45rem 1.3rem;
    border-radius: 22px;
    background: #fff;
    border: 1px solid rgba(18, 104, 243, .1);
    box-shadow: 0 10px 28px rgba(15, 23, 42, .05);
}

.lp-service__icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    margin-bottom: 1rem;
    border-radius: 16px;
    color: #fff;
    background: linear-gradient(135deg, var(--lp-blue), var(--lp-cyan));
    flex-shrink: 0;
    overflow: hidden;
}

.lp-service__icon svg {
    display: block;
    width: 24px;
    height: 24px;
    flex-shrink: 0;
    overflow: visible;
}

.lp-service h3 {
    margin: 0 0 .45rem;
    font-size: 1.1rem;
    font-weight: 700;
}

.lp-service p {
    margin: 0;
    color: var(--lp-muted);
    line-height: 1.55;
    font-size: .95rem;
}

.lp-product-block {
    display: grid;
    grid-template-columns: 1.05fr .95fr;
    gap: 2.25rem;
    align-items: center;
    margin-top: 2rem;
}

.lp-product-block__copy p {
    margin: 0;
    color: var(--lp-muted);
    font-size: 1.02rem;
    line-height: 1.7;
}

.lp-product-block__copy p + p {
    margin-top: 1.05rem;
}

.lp-product-block__actions {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    margin-top: 1.5rem;
}

.lp-product-block__visual {
    margin: 0;
}

.lp-product-block__visual img {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 24px;
    box-shadow: 0 18px 40px rgba(15, 23, 42, .1);
}

.lp-contact {
    text-align: center;
    padding-bottom: 5rem;
}

.lp-contact .lp-section__lead {
    margin-left: auto;
    margin-right: auto;
}

.lp-contact__card {
    margin: 2rem auto 0;
    max-width: 520px;
    padding: 1.6rem 1.4rem;
    border-radius: 24px;
    background: #fff;
    border: 1px solid rgba(18, 104, 243, .1);
    box-shadow: 0 12px 32px rgba(15, 23, 42, .06);
}

.lp-contact__card .site-contact-links {
    margin-top: 1.25rem;
}

.lp-contact__card .site-contact-links__social {
    gap: .65rem;
}

.lp-contact__card .site-contact-links__social a {
    width: 58px;
    height: 58px;
    border-radius: 18px;
}

.lp-contact__card .site-contact-links__social svg,
.lp-contact__card .site-contact-links__social .site-contact-links__img--threads {
    width: 28px;
    height: 28px;
}

.lp-footer {
    padding: 1.35rem 0 2.25rem;
    text-align: center;
    color: #94a3b8;
    font-size: .82rem;
    background: #eaf3fa;
}

@keyframes lpRise {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 900px) {
    .lp-services,
    .lp-product-block {
        grid-template-columns: 1fr;
    }

    .lp-hero {
        min-height: 72vh;
        padding: 4.5rem 0 2.75rem;
        align-items: end;
    }

    .lp-hero::before {
        background-position: 68% center;
    }

    .lp-hero::after {
        background:
            linear-gradient(
                180deg,
                rgba(243, 247, 251, .25) 0%,
                rgba(243, 247, 251, .72) 48%,
                #f3f7fb 100%
            );
    }
}

@media (max-width: 640px) {
    .lp-hero {
        min-height: 68vh;
    }
    .lp-hero h1 { font-size: 1.45rem; }
    .lp-hero::before {
        background-position: 72% center;
    }
}
</style>
@endpush

@section('content')
@php
    $supportEmail = trim((string) (gs('support_email') ?: config('toapp.support_email', 'tonkowtek@to-app.com')));
@endphp
<div class="lp">
    @include($activeTemplate . 'partials.public_nav')

    {{-- 1. Hero --}}
    <header class="lp-hero">
        <div class="lp-shell">
            <div class="lp-hero__copy">
                <div class="lp-hero__brand">To-App</div>
                <h1>@lang('Protection. Connection. More possibilities.')</h1>
                <p>@lang('To-App connects people with insurance agents and gives eligible users the opportunity to earn referral rewards. We help users explore insurance options while helping agents reach new customers through trusted recommendations.')</p>
                <div class="lp-hero__actions">
                    <a class="lp-btn lp-btn--primary" href="{{ route('user.register') }}">@lang('Create Account')</a>
                    <a class="lp-btn lp-btn--ghost" href="{{ route('user.login') }}">@lang('Log In')</a>
                </div>
            </div>
        </div>
    </header>

    {{-- 2. About us --}}
    <section class="lp-section lp-section--soft" id="about">
        <div class="lp-shell">
            <span class="lp-kicker">@lang('About Us')</span>
            <h2>@lang('Today. Tomorrow. Together.')</h2>
            <div class="lp-about__body">
                <p>@lang('To-App is an insurance marketing and referral platform that brings users and insurance agents together.')</p>
                <div class="lp-about__actions">
                    <button type="button" class="lp-btn lp-btn--ghost" data-lp-modal-open="aboutModal">@lang('Read more')</button>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Our service --}}
    <section class="lp-section" id="services">
        <div class="lp-shell">
            <span class="lp-kicker">@lang('Our Services')</span>
            <h2>@lang('Connect. Share. Track.')</h2>
            <p class="lp-section__lead">@lang('Explore insurance options, share your referral code, and follow your referral activity in one place.')</p>
            <div class="lp-services">
                <article class="lp-service">
                    <span class="lp-service__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="5" r="2.25"/>
                            <circle cx="5.5" cy="17.5" r="2.25"/>
                            <circle cx="18.5" cy="17.5" r="2.25"/>
                            <path d="M10.3 6.6 7.1 15.1"/>
                            <path d="M13.7 6.6 16.9 15.1"/>
                            <path d="M7.9 17.5h8.2"/>
                        </svg>
                    </span>
                    <h3>@lang('Share Your Referral Code')</h3>
                    <p>@lang('Invite friends and family to explore To-App using your personal referral code. Help them connect with insurance agents and discover available services.')</p>
                </article>
                <article class="lp-service">
                    <span class="lp-service__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 3.5h4.5A1.5 1.5 0 0 1 20 5v14a1.5 1.5 0 0 1-1.5 1.5H5.5A1.5 1.5 0 0 1 4 19V5A1.5 1.5 0 0 1 5.5 3.5H10"/>
                            <path d="M9.5 3.5h5v3h-5z"/>
                            <path d="M8 12h8"/>
                            <path d="M8 15.5h5.5"/>
                            <path d="M8 8.8h2.5"/>
                        </svg>
                    </span>
                    <h3>@lang('Track Your Referrals and Rewards')</h3>
                    <p>@lang('Keep track of your referral activity and reward status in one place. Rewards are subject to program rules, eligibility requirements, and verification.')</p>
                </article>
                <article class="lp-service">
                    <span class="lp-service__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="7.5" cy="8" r="2.5"/>
                            <circle cx="16.5" cy="8" r="2.5"/>
                            <path d="M3.8 18c.6-2.6 2.4-4 4.8-4h.2"/>
                            <path d="M15.2 14h.2c2.4 0 4.2 1.4 4.8 4"/>
                            <path d="M10.2 12.8 12 14.6 17 9.4"/>
                        </svg>
                    </span>
                    <h3>@lang('Connect with Insurance Agents')</h3>
                    <p>@lang('Connect with insurance agents to discuss your needs, ask questions, and explore available coverage options.')</p>
                </article>
            </div>
        </div>
    </section>

    {{-- 4. Our product --}}
    <section class="lp-section lp-section--tint" id="products">
        <div class="lp-shell">
            <span class="lp-kicker">@lang('Our Product')</span>
            <h2>@lang('Your Connections. One Platform.')</h2>
            <p class="lp-section__lead">@lang('Connect, share, and track referral rewards in one place.')</p>
            <div class="lp-product-block">
                <div class="lp-product-block__copy">
                    <p>@lang('Connect with insurance agents, share your referral code, and track eligible referral rewards through To-App.')</p>
                    <p>@lang('For agents, To-App brings referral activity and prospective customer connections into one workspace.')</p>
                    <div class="lp-product-block__actions">
                        <a class="lp-btn lp-btn--primary" href="{{ route('user.register') }}">@lang('Create Account')</a>
                        <a class="lp-btn lp-btn--ghost" href="{{ route('user.login') }}">@lang('Log In')</a>
                    </div>
                </div>
                <figure class="lp-product-block__visual">
                    <img
                        src="{{ asset('assets/images/landing/our-product.jpg') }}"
                        alt="@lang('To-App product preview on mobile')"
                        width="1024"
                        height="768"
                        loading="lazy"
                    >
                </figure>
            </div>
        </div>
    </section>

    {{-- 5. Contact us (social) --}}
    <section class="lp-section lp-contact" id="contact">
        <div class="lp-shell">
            <span class="lp-kicker">@lang('Contact Us')</span>
            <h2>@lang('Stay connected')</h2>
            <p class="lp-section__lead">@lang('Reach our team by email or follow To-App on social for updates.')</p>
            <div class="lp-contact__card">
                @if($supportEmail !== '')
                    <a class="lp-btn lp-btn--ghost" href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
                @endif
                @include('partials.site_contact_links', [
                    'variant' => 'landing',
                    'showEmail' => false,
                    'showLabel' => true,
                    'iconSize' => 28,
                ])
            </div>
        </div>
    </section>

    <footer class="lp-footer">
        <div class="lp-shell">
            &copy; {{ date('Y') }} TONKOW TEK LLP · To-App
        </div>
    </footer>
</div>

<div class="lp-modal" id="aboutModal" role="dialog" aria-modal="true" aria-labelledby="aboutModalTitle" hidden>
    <div class="lp-modal__backdrop" data-lp-modal-close></div>
    <div class="lp-modal__dialog">
        <button type="button" class="lp-modal__close" data-lp-modal-close aria-label="@lang('Close')">&times;</button>
        <h3 id="aboutModalTitle">@lang('About Us')</h3>
        <p>@lang('To-App is an insurance marketing and referral platform that brings users and insurance agents together.')</p>
        <p>@lang('For users, we make it easier to connect with agents, explore insurance options, and participate in eligible referral programs.')</p>
        <p>@lang('For agents, we provide tools to manage referrals, organize prospective customer connections, and support business growth.')</p>
        <p>@lang('Our goal is to make insurance connections simpler and create value for everyone involved.')</p>
        <p class="lp-modal__tagline">@lang('Today. Tomorrow. Together.')</p>
        <div class="lp-modal__footer">
            <button type="button" class="lp-btn lp-btn--primary" data-lp-modal-close>@lang('Close')</button>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
(function () {
    var openers = document.querySelectorAll('[data-lp-modal-open]');
    var body = document.body;

    function openModal(id) {
        var modal = document.getElementById(id);
        if (!modal) return;
        modal.hidden = false;
        modal.classList.add('is-open');
        body.style.overflow = 'hidden';
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.hidden = true;
        body.style.overflow = '';
    }

    openers.forEach(function (btn) {
        btn.addEventListener('click', function () {
            openModal(btn.getAttribute('data-lp-modal-open'));
        });
    });

    document.querySelectorAll('.lp-modal').forEach(function (modal) {
        modal.querySelectorAll('[data-lp-modal-close]').forEach(function (el) {
            el.addEventListener('click', function () {
                closeModal(modal);
            });
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.lp-modal.is-open').forEach(closeModal);
    });
})();
</script>
@endpush
