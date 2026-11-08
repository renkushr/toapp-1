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
                        <ul class="navbar-nav main-menu me-auto" id="linkItem">
                            <li><a class="{{ menuActive('home') }}" href="{{ route('home') }}">@lang('Home')</a>
                            </li>
                            <li><a href="{{ route('about.us') }}">@lang('About Us')</a></li>
                            <li><a href="#plan">@lang('Plan')</a></li>
                            <li><a href="#feature">@lang('Feature')</a></li>
                            <li><a href="#faq">@lang('Faq')</a></li>
                            <li><a href="#gateway">@lang('Gateway')</a></li>
                            @php
                                $pages = App\Models\Page::where('tempname', $activeTemplate)
                                    ->where('is_default', Status::NO)
                                    ->get();
                            @endphp
                            @foreach ($pages as $k => $data)
                                <li>
                                    <a href="{{ route('pages', [$data->slug]) }}">
                                        {{ __($data->name) }}
                                    </a>
                                </li>
                            @endforeach

                            @auth
                                <li><a class="{{ menuActive('ticket.open') }}" href="{{ route('ticket.open') }}">@lang('Support')</a></li>
                            @else
                                <li><a class="{{ menuActive('contact') }}" href="{{ route('contact') }}">@lang('Contact')</a></li>
                            @endauth
                        </ul>
                        <div class="nav-right">

                            @auth
                                <a class="btn btn-sm btn--base me-3 btn--capsule px-3" href="{{ route('user.home') }}">
                                    @lang('Dashboard')
                                </a>

                                @if (request()->routeIs('user.authorization'))
                                    <button class="btn btn-sm btn--base me-3 btn--capsule px-3" data-bs-toggle="modal" data-bs-target="#ConfirmationModal"
                                        type="button">@lang('Logout')
                                    </button>
                                @endif
                            @else
                                <a class="btn btn-sm btn--base me-3 btn--capsule px-3" data-bs-toggle="modal" data-bs-target="#loginModal"
                                    href="#0">@lang('Log In')</a>

                                <a class="fs--14px me-3 text-white registerBtn" id="open-registration-modal" data-bs-toggle="modal"
                                    data-bs-target="#registerModal" href="#0">@lang('Create Account')</a>
                                <!-- Button trigger modal -->

                            @endauth

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

    @if (request()->routeIs('user.authorization'))
        <x-logout-confirmation />
    @endif

    @push('script')
        <script>
            $('.registerBtn').on('click', function() {
                @if (!gs('registration'))
                    notify('error', 'Registration is currently disabled');
                @endif
            })
        </script>
        @include('partials.language_switcher_assets')
    @endpush
