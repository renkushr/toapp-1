@php
    $supportEmail = trim((string) (gs('support_email') ?: config('toapp.support_email', 'tonkowtek@to-app.com')));
    $social = config('toapp.social', []);
    $variant = $variant ?? 'default'; // default | compact | portal | landing
    $showEmail = $showEmail ?? true;
    $showLabel = $showLabel ?? true;

    $iconSize = (int) ($iconSize ?? 15);
    if ($variant === 'landing') {
        $iconSize = max($iconSize, 28);
    }

    $icons = [
        'facebook' => '<svg viewBox="0 0 24 24" width="'.$iconSize.'" height="'.$iconSize.'" aria-hidden="true"><path fill="currentColor" d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H9v3h2v7h3v-7h2.2l.8-3H14V9z"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" width="'.$iconSize.'" height="'.$iconSize.'" aria-hidden="true"><path fill="currentColor" d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4zm5 4.5A4.5 4.5 0 1 0 16.5 12 4.5 4.5 0 0 0 12 7.5zm0 7.2A2.7 2.7 0 1 1 14.7 12 2.7 2.7 0 0 1 12 14.7zM17.8 6.2a1.1 1.1 0 1 0 1.1 1.1 1.1 1.1 0 0 0-1.1-1.1z"/></svg>',
        'tiktok' => '<svg viewBox="0 0 24 24" width="'.$iconSize.'" height="'.$iconSize.'" aria-hidden="true"><path fill="currentColor" d="M14.5 3h2.1c.2 1.7 1.3 3.2 2.9 4v2.3a7.2 7.2 0 0 1-2.9-1v6.1a5.5 5.5 0 1 1-5.5-5.5c.3 0 .6 0 .9.1v2.5a3 3 0 1 0 2.1 2.9V3z"/></svg>',
        'threads' => '<img class="site-contact-links__img site-contact-links__img--threads" src="'.e(asset('assets/images/social/threads.png')).'" alt="" width="'.$iconSize.'" height="'.$iconSize.'" aria-hidden="true">',
        'x' => '<svg viewBox="0 0 24 24" width="'.$iconSize.'" height="'.$iconSize.'" aria-hidden="true"><path fill="currentColor" d="M4 4h4.1l4 5.5L16.8 4H20l-6.3 7.2L20.5 20H16.4l-4.4-6-5 6H3.2l6.7-7.7L4 4z"/></svg>',
    ];

    $items = [
        ['key' => 'facebook', 'label' => __('Facebook')],
        ['key' => 'instagram', 'label' => __('Instagram')],
        ['key' => 'tiktok', 'label' => __('TikTok')],
        ['key' => 'threads', 'label' => __('Threads')],
        ['key' => 'x', 'label' => __('X')],
    ];
@endphp

<div @class([
    'site-contact-links',
    'site-contact-links--compact' => $variant === 'compact',
    'site-contact-links--portal' => $variant === 'portal',
    'site-contact-links--landing' => $variant === 'landing',
])>
    @if($showLabel)
        <span class="site-contact-links__label">@lang('Contact')</span>
    @endif

    @if($showEmail && $supportEmail !== '')
        <a class="site-contact-links__email" href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
    @endif

    <div class="site-contact-links__social" role="list">
        @foreach($items as $item)
            @if(!empty($social[$item['key']]))
                <a role="listitem" href="{{ $social[$item['key']] }}" target="_blank" rel="noopener noreferrer" title="{{ $item['label'] }}" aria-label="{{ $item['label'] }}">
                    {!! $icons[$item['key']] !!}
                </a>
            @endif
        @endforeach
    </div>
</div>

@once
    <style>
        .site-contact-links {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 0.55rem 0.85rem;
            margin-top: 0.65rem;
            color: #64748b;
            font-size: 12px;
            line-height: 1.4;
        }

        .site-contact-links__label {
            font-weight: 700;
            letter-spacing: .02em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .site-contact-links__email {
            color: #0f172a;
            font-weight: 600;
            text-decoration: none;
        }

        .site-contact-links__email:hover {
            color: #1268f3;
            text-decoration: underline;
        }

        .site-contact-links__social {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .site-contact-links__social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 12px;
            color: #0f172a;
            background: rgba(15, 23, 42, .05);
            text-decoration: none;
            transition: background .15s ease, color .15s ease;
        }

        .site-contact-links__social a:hover {
            color: #1268f3;
            background: rgba(18, 104, 243, .12);
        }

        .site-contact-links__social svg,
        .site-contact-links__social .site-contact-links__img {
            display: block;
        }

        .site-contact-links__social .site-contact-links__img--threads {
            width: 72%;
            height: 72%;
            object-fit: contain;
            border-radius: 8px;
            background: transparent;
        }

        .site-contact-links--compact {
            margin-top: 0.35rem;
            gap: 0.4rem 0.6rem;
        }

        .site-contact-links--compact .site-contact-links__social a {
            width: 28px;
            height: 28px;
            border-radius: 10px;
        }

        .site-contact-links--portal {
            margin-top: 0.75rem;
        }

        .site-contact-links--portal .site-contact-links__email {
            color: inherit;
        }

        .site-contact-links--landing {
            margin-top: 1.1rem;
            gap: 0.75rem 1rem;
            font-size: 13px;
        }

        .site-contact-links--landing .site-contact-links__social {
            gap: 0.65rem;
        }

        .site-contact-links--landing .site-contact-links__social a {
            width: 58px;
            height: 58px;
            border-radius: 18px;
        }

        @media (max-width: 575.98px) {
            .site-contact-links {
                flex-direction: column;
                gap: 0.45rem;
            }

            .site-contact-links--landing .site-contact-links__social a {
                width: 52px;
                height: 52px;
            }
        }
    </style>
@endonce
