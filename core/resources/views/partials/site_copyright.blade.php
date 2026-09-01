@php
    $siteCompany = 'TONKOW TEK LLP';
    $copyrightVariant = $copyrightVariant ?? 'inline';
@endphp

<footer @class(['site-copyright', 'site-copyright--bar' => $copyrightVariant === 'bar', 'site-copyright--portal' => $copyrightVariant === 'portal'])>
    <span>&copy; {{ date('Y') }} {{ $siteCompany }}. @lang('All rights reserved.')</span>
</footer>

@once
    <style>
        .site-copyright {
            color: #64748b;
            font-size: 12px;
            line-height: 1.5;
            text-align: center;
        }

        .site-copyright--bar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 76px;
            z-index: 1035;
            padding: 6px 14px;
            pointer-events: none;
        }

        .site-copyright--portal {
            margin-top: 24px;
            padding: 16px 0 8px;
            border-top: 1px solid rgba(15, 23, 42, .08);
        }

        @media (max-width: 767px) {
            .site-copyright--bar {
                bottom: 70px;
                font-size: 11px;
            }
        }
    </style>
@endonce
