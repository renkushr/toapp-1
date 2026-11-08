@extends($activeTemplate . 'layouts.frontend')

@section('content')
@include($activeTemplate . 'partials.public_nav')

<div class="lp-page">
    <div class="lp-page__shell">
        <section class="lp-page__hero">
            <span class="lp-page__kicker">@lang('About Us')</span>
            <h1>{{ $page->title }}</h1>
            <p>@lang('Learn more about who we are and what we do.')</p>
        </section>

        <article class="lp-page__card about-us-content">
            {!! $page->body !!}
        </article>
    </div>
</div>
@endsection

@push('style')
<style>
.lp-page {
    padding: 1.5rem 0 4rem;
    font-family: Poppins, sans-serif;
    color: #0b2033;
}

.lp-page__shell {
    width: min(1120px, calc(100% - 2rem));
    margin: 0 auto;
}

.lp-page__hero {
    margin: 1.5rem 0 1.25rem;
}

.lp-page__kicker {
    display: inline-block;
    margin-bottom: .65rem;
    color: #1268f3;
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.lp-page__hero h1 {
    margin: 0;
    font-size: clamp(1.7rem, 3.2vw, 2.4rem);
    font-weight: 700;
    letter-spacing: -.04em;
}

.lp-page__hero p {
    margin: .7rem 0 0;
    max-width: 36rem;
    color: #5b6b7f;
    line-height: 1.6;
}

.lp-page__card {
    padding: 1.5rem 1.35rem;
    border-radius: 28px;
    background: rgba(255,255,255,.9);
    border: 1px solid rgba(18, 104, 243, .12);
    box-shadow: 0 22px 50px rgba(15, 23, 42, .08);
}

.about-us-content {
    color: #0f172a;
    line-height: 1.7;
}

.about-us-content h1,
.about-us-content h2,
.about-us-content h3,
.about-us-content h4 {
    margin: 1.2em 0 .5em;
    font-weight: 700;
    letter-spacing: -.02em;
}

.about-us-content p {
    margin: 0 0 1em;
}

.about-us-content ul,
.about-us-content ol {
    margin: 0 0 1em;
    padding-left: 1.25rem;
}

.about-us-content a {
    color: #1268f3;
    font-weight: 600;
    text-decoration: underline;
}

.about-us-content img {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    margin: .75rem 0;
    display: block;
}

.about-us-content blockquote {
    margin: 1em 0;
    padding: .75rem 1rem;
    border-left: 3px solid #1268f3;
    background: rgba(18, 104, 243, .06);
    border-radius: 0 12px 12px 0;
}

body {
    background:
        radial-gradient(circle at 8% -10%, rgba(19, 200, 214, .22), transparent 38%),
        radial-gradient(circle at 92% 0%, rgba(18, 104, 243, .16), transparent 36%),
        linear-gradient(180deg, #f7fbff 0%, #eef6fb 42%, #f8fbff 100%) !important;
}
</style>
@endpush
