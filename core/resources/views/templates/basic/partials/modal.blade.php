@php
    $info = json_decode(json_encode(getIpInfo()), true);
    $mobileCode = @implode(',', $info['code']);
    $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
    $policyPages = getContent('policy_pages.element', false, null, true);
@endphp

<!-- Login -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="page-boxed bg">
   
     
   <div style="background-color:#ffffff;margin-top:156px;border-radius:30px" >
   <div>
   <a href="{{ url()->previous() }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" style="margin-left:24px; margin-top:20px">
        <path d="M7 15H27C27.2652 15 27.5196 15.1054 27.7071 15.2929C27.8946 15.4804 28 15.7348 28 16C28 16.2652 27.8946 16.5196 27.7071 16.7071C27.5196 16.8946 27.2652 17 27 17H7C6.73478 17 6.48043 16.8946 6.29289 16.7071C6.10536 16.5196 6 16.2652 6 16C6 15.7348 6.10536 15.4804 6.29289 15.2929C6.48043 15.1054 6.73478 15 7 15Z" fill="#AEE1F4"/>
       <path d="M7.41402 16L15.708 24.292C15.8958 24.4798 16.0013 24.7344 16.0013 25C16.0013 25.2656 15.8958 25.5202 15.708 25.708C15.5202 25.8958 15.2656 26.0013 15 26.0013C14.7345 26.0013 14.4798 25.8958 14.292 25.708L5.29202 16.708C5.19889 16.6151 5.12501 16.5048 5.07459 16.3833C5.02418 16.2618 4.99823 16.1315 4.99823 16C4.99823 15.8685 5.02418 15.7382 5.07459 15.6167C5.12501 15.4952 5.19889 15.3849 5.29202 15.292L14.292 6.292C14.4798 6.10422 14.7345 5.99873 15 5.99873C15.2656 5.99873 15.5202 6.10422 15.708 6.292C15.8958 6.47977 16.0013 6.73445 16.0013 7C16.0013 7.26555 15.8958 7.52022 15.708 7.708L7.41402 16Z" fill="#AEE1F4"/>
   </svg>
   </a>
</div>
    <div style="padding:50px">
      <div style="padding-top:20px">
        
           <span style="
font-family: Poppins;
font-size: 30px;
font-style: normal;
font-weight: 600;
line-height: normal;background: var(--light-blue, linear-gradient(180deg, #18ABCF 0%, #1E84F4 100%));
background-clip: text;
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;"> @lang('LOGIN') </span>
         </div>
       <div>
     <p>@lang('Please Login To Continue')</p>
</div>

       <form class="" action="{{ route('user.login') }}" method="post" id="loginModal">
               @csrf
           <div>
               <div style="margin-bottom:40px;margin-top:40px" >
                   <label class="label">@lang('Email')</label>
                   <div style="margin-top:9px">
                   <input type="text"  name="username" value="{{ old('username') }}" class="form--control box" required>
                   </div>
               </div>
                    <div style="margin-bottom:20px" >
                   <label class="label">@lang('Password')</label>
                   <div style="margin-top:9px">
                   <input id="password" type="password" class="form--control box" name="password" required required>
                    </div>
               </div>

             <div style="color: #F4733B;

font-family: Poppins;
font-size: 12px;
font-style: italic;
font-weight: 500;
line-height: normal;text-align:right">
               <p>@lang('Forgot your password')</p>
              </div>

               
               <button type="submit" style="border-radius: 8px;width: 100%;margin-top:40px;
height: 60px;
flex-shrink: 0;
border: 1px solid var(--light-blue, #18ABCF);

background: var(--light-blue, linear-gradient(180deg, #18ABCF 0%, #1E84F4 100%));color: var(--white, #FFF);
font-family: Poppins;
font-size: 18px;
font-style: normal;
font-weight: 600;
line-height: normal;">@lang('Log In')</button>
            
                  
           </form>
       </div>
   </div>
</div>
</div>
        </div>
    </div>
</div>

{{-- Register --}}
<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">@lang('Create an account')</h3>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i class="la la-times"></i>
                </button>
            </div>
            <div class="modal-body  @if (!gs('registration')) form-disabled @endif">

                @if (!gs('registration'))
                    <span class="form-disabled-text">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="80"
                            height="80" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"
                            class="">
                            <g>
                                <path
                                    d="M255.999 0c-79.044 0-143.352 64.308-143.352 143.353v70.193c0 4.78 3.879 8.656 8.659 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193c0-42.998 34.981-77.98 77.979-77.98s77.979 34.982 77.979 77.98v70.193c0 4.78 3.88 8.656 8.661 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193C399.352 64.308 335.044 0 255.999 0zM382.04 204.89h-30.748v-61.537c0-52.544-42.748-95.292-95.291-95.292s-95.291 42.748-95.291 95.292v61.537h-30.748v-61.537c0-69.499 56.54-126.04 126.038-126.04 69.499 0 126.04 56.541 126.04 126.04v61.537z"
                                    fill="#ff7149" opacity="1" data-original="#ff7149" class=""></path>
                                <path
                                    d="M410.63 204.89H101.371c-20.505 0-37.188 16.683-37.188 37.188v232.734c0 20.505 16.683 37.188 37.188 37.188H410.63c20.505 0 37.187-16.683 37.187-37.189V242.078c0-20.505-16.682-37.188-37.187-37.188zm19.875 269.921c0 10.96-8.916 19.876-19.875 19.876H101.371c-10.96 0-19.876-8.916-19.876-19.876V242.078c0-10.96 8.916-19.876 19.876-19.876H410.63c10.959 0 19.875 8.916 19.875 19.876v232.733z"
                                    fill="#ff7149" opacity="1" data-original="#ff7149" class=""></path>
                                <path
                                    d="M285.11 369.781c10.113-8.521 15.998-20.978 15.998-34.365 0-24.873-20.236-45.109-45.109-45.109-24.874 0-45.11 20.236-45.11 45.109 0 13.387 5.885 25.844 16 34.367l-9.731 46.362a8.66 8.66 0 0 0 8.472 10.436h60.738a8.654 8.654 0 0 0 8.47-10.434l-9.728-46.366zm-14.259-10.961a8.658 8.658 0 0 0-3.824 9.081l8.68 41.366h-39.415l8.682-41.363a8.655 8.655 0 0 0-3.824-9.081c-8.108-5.16-12.948-13.911-12.948-23.406 0-15.327 12.469-27.796 27.797-27.796 15.327 0 27.796 12.469 27.796 27.796.002 9.497-4.838 18.246-12.944 23.403z"
                                    fill="#ff7149" opacity="1" data-original="#ff7149" class=""></path>
                            </g>
                        </svg>
                    </span>
                @endif

                @include($activeTemplate . 'partials.social_login', [($register = 'true')])



                <form class="account-form registration-form verify-gcaptcha2" action="{{ route('user.register') }}" method="post">
                    @csrf
                    <div class="row">
                        @if (session()->get('reference') != null)
                            <div class="col-lg-12 mb-3">
                                <label>@lang('Reference By')</label>
                                <input type="text" name="ref_by" id="referenceBy" class="form--control"
                                    value="{{ session()->get('reference') }}" readonly required>
                            </div>
                        @else
                            <div class="col-lg-12 mb-3">
                                <label>@lang('Referral Code')</label>
                                <input type="text" name="ref_by" class="form--control"
                                    value="{{ old('ref_by') }}" placeholder="@lang('Enter your invitation code')" required>
                                <small>@lang('A valid referral code is required to create an account.')</small>
                            </div>
                        @endif

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('First Name') }}</label>
                                <input type="text" class="form--control " name="firstname" value="{{ old('firstname') }}" required>

                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Last Name') }}</label>
                                <input type="text" class="form--control " name="lastname" value="{{ old('lastname') }}" required>

                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>@lang('E-Mail Address')</label>
                                <input id="email" type="email" class="form--control checkUser" name="email" value="{{ old('email') }}"
                                    required>
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Password')</label>

                                <input type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                    name="password" required>


                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Confirm Password')</label>
                                <input id="password-confirm" type="password" class="form--control" name="password_confirmation" required
                                    autocomplete="new-password">
                            </div>
                        </div>


                        <x-captcha />


                        @if (gs('agree'))
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                                    <label for="agree">@lang('I agree with') </label>
                                    <span>
                                        @foreach ($policyPages as $policy)
                                            <a href="#policyModal-{{ \Illuminate\Support\Str::slug((string) $policy->slug) }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#policyModal-{{ \Illuminate\Support\Str::slug((string) $policy->slug) }}">{{ __($policy->data_values->title) }}</a>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </span>

                                </div>
                            </div>
                        @endif

                    </div>
                    <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Create Account')</button>
                    <p class="text-center mt-3"><span class="text-white"> @lang('Have an account')? </span> <a href="#0" class="text--base"
                            data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">@lang('Log In')</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Exist-User-Credential --}}
<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i class="la la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <h6 class="text-center">@lang('You already have an account please Sign in ')</h6>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn--danger text-white" data-bs-dismiss="modal">@lang('Close')</button>

                <button type="button" class="btn btn--base ex-email" data-bs-dismiss="modal" data-bs-toggle="modal"
                    data-bs-target="#loginModal">@lang('Log In')</button>
            </div>
        </div>
    </div>
</div>

{{-- Password Reset --}}
<div class="modal fade" id="resetModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">@lang('Reset Password')</h3>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i class="la la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="account-form verify-gcaptcha3" method="POST" action="{{ route('user.password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">@lang('Email or Username')</label>
                        <input type="text" class="form-control form--control" name="value" value="{{ old('value') }}" required
                            autofocus="off">
                    </div>

                    <x-captcha />


                    <button type="submit" class="btn btn--base w-100">@lang('Send Password Code')</button>
                    <p class="text-center mt-3"><span class="text-white">@lang('Have been remembering')?</span> <a href="#0" class="text--base"
                            data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">@lang('Log In')</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

@include($activeTemplate . 'partials.policy_modals', ['policyModalPages' => $policyPages])

@push('style')
    <style>
        .form-disabled {
            overflow: hidden;
            position: relative;
        }

        .form-disabled-text svg path {
            fill: #ACE600;
        }


        .form-disabled::after {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            top: 0;
            left: 0;
            backdrop-filter: blur(3px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            z-index: 99;
        }

        .form-disabled .account-logo-area {
            z-index: 999;
        }

        .form-disabled-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 991;
            font-size: 24px;
            height: auto;
            width: 100%;
            text-align: center;
            color: hsl(var(--dark-600));
            font-weight: 600;
            line-height: 1.2;
        }
    </style>
@endpush

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });


            $('.ex-email').on('click', function() {
                $('#existModalCenter').modal('hide');
            })


            let anyError = '{{ @$errors->any() }}';

            let modalType = '{{ Session::get('modalType') }}';

            if (anyError || modalType) {
                let errorModal = '{{ Session::get('modal') }}';
                $(errorModal).modal('show');
            }

            var CaptchaCallback = function() {
                grecaptcha.render('verify-gcaptcha1');
                grecaptcha.render('verify-gcaptcha2');
                grecaptcha.render('verify-gcaptcha3');
            };

        })(jQuery);
    </script>
@endpush
