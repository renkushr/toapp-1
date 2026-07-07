@extends($activeTemplate.'layouts.frontend')

@include($activeTemplate . 'partials.auth_ui')

@section('content')
    @php
        $dob = data_get(session('user_register'), 'dob');
        $selectedMonth = $dob ? \Carbon\Carbon::parse($dob)->format('m') : old('mm');
        $selectedDate = $dob ? \Carbon\Carbon::parse($dob)->format('d') : old('dd');
        $selectedYear = $dob ? \Carbon\Carbon::parse($dob)->format('Y') : old('yyyy');
        $selectedSex = data_get(session('user_register'), 'sex', old('sex'));
    @endphp

    <div class="auth-surface">
        <div class="auth-shell">
            <div class="auth-topbar">
                <a class="auth-back" href="{{ route('user.register') }}" aria-label="@lang('Back')">
                    <i class="las la-arrow-left"></i>
                </a>
                <span class="auth-optional">@lang('Step 2')</span>
            </div>

            <div class="auth-card">
                <div class="auth-card__header">
                    <span class="auth-eyebrow">@lang('Step 2 of 3')</span>
                    <h1 class="auth-title">@lang('Profile details')</h1>
                    <p class="auth-copy">@lang('Add the basic information required to prepare your account profile.')</p>
                </div>

                <div class="auth-card__body">
                    <div class="auth-progress" aria-label="@lang('Registration progress')">
                        <span class="auth-step is-complete"></span>
                        <span class="auth-step is-active"></span>
                        <span class="auth-step"></span>
                    </div>

                    @if ($errors->any())
                        <div class="auth-error-summary">
                            @foreach ($errors->all() as $error)
                                <div>{{ __($error) }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form class="auth-form auth-enhanced-form" method="POST" action="{{ route('user.data.submit') }}">
                        @csrf

                        <div class="auth-field">
                            <span class="auth-label">@lang('Gender')</span>
                            <div class="auth-choice-grid">
                                <button type="button" data-sex="1" class="auth-choice gender {{ $selectedSex == 1 ? 'active' : '' }}">
                                    @lang('Male')
                                </button>
                                <button type="button" data-sex="2" class="auth-choice gender {{ $selectedSex == 2 ? 'active' : '' }}">
                                    @lang('Female')
                                </button>
                            </div>
                            <input type="hidden" name="sex" value="{{ $selectedSex }}" required>
                            <p class="auth-help">@lang('Select one option so the form can continue.')</p>
                        </div>

                        <div class="auth-field">
                            <label class="auth-label">@lang('Date of birth')</label>
                            <div class="auth-date-grid">
                                <select name="mm" class="auth-select" required>
                                    <option value="">@lang('MM')</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        @php $month = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $month }}" @selected($selectedMonth == $month)>{{ $month }}</option>
                                    @endfor
                                </select>

                                <select name="dd" class="auth-select" required>
                                    <option value="">@lang('DD')</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        @php $date = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $date }}" @selected($selectedDate == $date)>{{ $date }}</option>
                                    @endfor
                                </select>

                                <select name="yyyy" class="auth-select" required>
                                    <option value="">@lang('YYYY')</option>
                                    @foreach ($year as $data)
                                        <option value="{{ $data->year }}" @selected($selectedYear == $data->year)>{{ $data->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="dob" id="dob" value="{{ $dob }}">
                            <p class="auth-help">@lang('You must be at least 16 years old to continue.')</p>
                        </div>

                        <div class="auth-field">
                            <label class="auth-label" for="agent_license_no">@lang('Agent License No.')</label>
                            <input
                                id="agent_license_no"
                                type="text"
                                class="auth-control"
                                name="agent_license_no"
                                value="{{ data_get(session('user_register'), 'agent_license_no', old('agent_license_no')) }}"
                                placeholder="@lang('Your agent license number')"
                                required
                            >
                            <p class="auth-help">@lang('Enter the license number of the agent who serves your area.')</p>
                        </div>

                        <div class="auth-field">
                            <label class="auth-label" for="zipcode">@lang('US Zip Code')</label>
                            <input
                                id="zipcode"
                                type="text"
                                class="auth-control"
                                name="zipcode"
                                value="{{ data_get(session('user_register'), 'zipcode', old('zipcode')) }}"
                                placeholder="12345"
                                inputmode="numeric"
                                pattern="\d{5}(-\d{4})?"
                                maxlength="10"
                                required
                            >
                            <p class="auth-help">@lang('Must match a ZIP code covered by your agent.')</p>
                        </div>

                        <div class="auth-field">
                            <label class="auth-label" for="ssn">@lang('Last 4 SSN')</label>
                            <input
                                id="ssn"
                                type="text"
                                class="auth-control"
                                name="ssn"
                                value="{{ data_get(session('user_register'), 'ssn', old('ssn')) }}"
                                placeholder="0000"
                                inputmode="numeric"
                                maxlength="4"
                                required
                            >
                            <p class="auth-help">@lang('Enter only the last four digits.')</p>
                        </div>

                        <div class="auth-field">
                            <label class="auth-label" for="mobile">@lang('Phone No.')</label>
                            <input
                                id="mobile"
                                type="tel"
                                class="auth-control"
                                name="mobile"
                                value="{{ data_get(session('user_register'), 'mobile', old('mobile')) }}"
                                placeholder="@lang('Numbers only')"
                                inputmode="numeric"
                                pattern="[0-9]+"
                                required
                            >
                        </div>

                        <div class="auth-note">
                            <span class="auth-note__icon">i</span>
                            <span>@lang('This step prevents incomplete accounts and helps support verify requests faster later.')</span>
                        </div>

                        <button type="submit" class="auth-submit" data-loading-text="@lang('Saving...')">
                            @lang('Set Your Password')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.gender').on('click', function() {
                $('.gender').removeClass('active');
                $(this).addClass('active');
                $('[name="sex"]').val($(this).data('sex'));
            });

            $('[name="mm"], [name="dd"], [name="yyyy"]').on('change', function() {
                const mm = $('[name="mm"]').val();
                const dd = $('[name="dd"]').val();
                const yyyy = $('[name="yyyy"]').val();
                $('#dob').val(mm && dd && yyyy ? `${yyyy}-${mm}-${dd}` : '');
            });
        })(jQuery);
    </script>
@endpush
