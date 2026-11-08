    <!-- header-section start  -->
    <header class="header">
        <div class="header__bottom">
            <div class="container-fluid px-lg-5">
                <nav class="navbar navbar-expand-xl align-items-center p-0">
                    <a class="site-logo site-title" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="logo"></a>
                    <button class="navbar-toggler header-button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="menu-toggle"></span>
                    </button>
                    <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
                        <ul class="navbar-nav main-menu me-auto">
                            <li><a class="{{ menuActive('user.home') }}" href="{{ route('user.home') }}">@lang('Dashboard')</a></li>

                            <li class="menu_has_children">
                                <a class="{{ menuActive('user.deposit.*') }}" href="#0">@lang('Deposit')</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('user.deposit.index') }}">@lang('Deposit Money')</a></li>
                                    <li><a href="{{ route('user.deposit.history') }}">@lang('Deposit History')</a></li>
                                </ul>
                            </li>

                            <li class="menu_has_children">
                                <a class="{{ menuActive('user.withdraw.*') }}" href="#0">@lang('Withdraw')</a>
                                <ul class="sub-menu">
                            <li><a href="{{ route('user.withdraw.index') }}">@lang('Withdraw Money')</a></li>
                                    <li><a href="{{ route('user.withdraw.history') }}">@lang('Withdraw History')</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="{{ menuActive('user.referrals') }}" href="{{ route('user.referrals') }}">@lang('Referrals')</a>
                            </li>

                            <li class="menu_has_children">
                                <a class="{{ menuActive(['user.plans', 'user.investment.log']) }}" href="#0">@lang('Investment')</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('user.plans') }}">@lang('Plans')</a></li>
                                    <li><a href="{{ route('user.investment.log') }}">@lang('Investment Log')</a></li>
                                </ul>
                            </li>

                            <li class="menu_has_children">
                                <a class="{{ menuActive('ticket.*') }}" href="#0">@lang('Support')</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('ticket.index') }}">@lang('My Support Tickets')</a></li>
                                    <li><a href="{{ route('ticket.open') }}">@lang('New Support Ticket')</a></li>
                                </ul>
                            </li>

                            <li class="menu_has_children">
                                <a
                                    class="{{ menuActive(['user.profile.setting', 'user.twofactor', 'user.change.password', 'user.change.password']) }} href="#0">@lang('Account')</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
                                    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                                    <li><a href="{{ route('user.transactions') }}">@lang('Transaction Log')</a></li>
                                    <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
                                    <li><a href="{{ route('user.logout') }}">@lang('Logout')</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="nav-right">

                            <a class="btn btn-sm btn--base me-3 btn--capsule px-3" data-bs-toggle="modal" data-bs-target="#ConfirmationModal"
                                href="#0">@lang('Logout')
                            </a>


                            <div class="language_switcher me-3">
                                @include('partials.language_switcher', ['variant' => 'user'])
                            </div>

                        </div>
                    </div>
                </nav>
            </div>
        </div><!-- header__bottom end -->
    </header>
    <!-- header-section end  -->



    @push('script')
        @include('partials.language_switcher_assets')
    @endpush
