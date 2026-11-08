@php
    $footerItems = [
        [
            'label' => __('Dashboard'),
            'short' => __('Home'),
            'route' => route('user.home'),
            'icon' => 'assets/global/img/icons/icon-dashboard.svg',
            'active' => request()->routeIs('user.home'),
        ],
        [
            'label' => __('Plans'),
            'short' => __('Plans'),
            'route' => route('user.plans'),
            'icon' => 'assets/global/img/icons/icon-shield-check.svg',
            'active' => request()->routeIs('user.plans', 'user.plans.*', 'user.investment.log'),
        ],
        [
            'label' => __('Referral'),
            'short' => __('Refer'),
            'route' => route('user.referrals'),
            'icon' => 'assets/global/img/icons/icon-users.svg',
            'active' => request()->routeIs('user.referrals'),
        ],
        [
            'label' => __('Withdraw'),
            'short' => __('Cash'),
            'route' => route('user.withdraw.index'),
            'icon' => 'assets/global/img/icons/icon-wallet.svg',
            'active' => request()->routeIs('user.withdraw.*'),
        ],
        [
            'label' => __('Setting'),
            'short' => __('More'),
            'route' => route('user.setting'),
            'icon' => 'assets/global/img/icons/icon-setting.svg',
            'active' => request()->routeIs('user.setting', 'user.profile.setting', 'user.change.password', 'user.bank.setting', 'user.call', 'user.transactions', 'ticket.*'),
        ],
    ];
@endphp

<div class="footer-menu-block">
    <p class="footer-menu-copyright">&copy; {{ date('Y') }} TONKOW TEK LLP</p>
    <ul class="footer-menu">
        @foreach ($footerItems as $item)
            <li @class(['active' => $item['active']])>
                <a class="link-menu" href="{{ $item['route'] }}">
                    <img class="svg-js icons" src="{{ asset($item['icon']) }}" alt="{{ $item['label'] }}">
                    <span class="nav-label nav-label--full">{{ $item['label'] }}</span>
                    <span class="nav-label nav-label--short">{{ $item['short'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
