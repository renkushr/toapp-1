@extends('toapp_admin.layouts.app')

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('toapp.admin.settings.edit') }}" class="btn btn-primary">@lang('Core settings')</a>
            <a href="{{ route('toapp.admin.settings.email') }}" class="btn btn-outline-secondary">@lang('Email / SMTP')</a>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title mb-0">@lang('Core settings')</h3>
                    <div class="text-secondary">@lang('Site identity, currency, and verification rules.')</div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('toapp.admin.settings.update') }}" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label">@lang('Site name')</label>
                        <input name="site_name" class="form-control" value="{{ old('site_name', $settings->site_name) }}" required maxlength="40">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('Rows per page')</label>
                        <input name="paginate_number" type="number" min="5" max="100" class="form-control" value="{{ old('paginate_number', $settings->paginate_number ?: 20) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">@lang('Currency text')</label>
                        <input name="cur_text" class="form-control" value="{{ old('cur_text', $settings->cur_text) }}" required maxlength="40">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('Currency symbol')</label>
                        <input name="cur_sym" class="form-control" value="{{ old('cur_sym', $settings->cur_sym) }}" required maxlength="40">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">@lang('Email from')</label>
                        <input name="email_from" type="email" class="form-control" value="{{ old('email_from', $settings->email_from) }}" maxlength="40">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('Email sender name')</label>
                        <input name="email_from_name" class="form-control" value="{{ old('email_from_name', $settings->email_from_name) }}" maxlength="255">
                    </div>

                    <div class="col-12">
                        <div class="alert alert-info mb-0">
                            <div class="fw-bold mb-1">@lang('Contact support (user app)')</div>
                            <div class="text-secondary">@lang('These values appear under Contact support → Call / Email.')</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('Support phone (Call)')</label>
                        <input name="support_phone" class="form-control" value="{{ old('support_phone', $settings->support_phone) }}" maxlength="60" placeholder="+1 (725) 294-7734">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('Support email (Email)')</label>
                        <input name="support_email" type="email" class="form-control" value="{{ old('support_email', $settings->support_email) }}" maxlength="120" placeholder="tonkowtek@to-app.com">
                    </div>

                    <div class="col-12">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="registration" value="1" @checked(old('registration', $settings->registration))>
                                    <span class="form-check-label">@lang('Registration open')</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="en" value="1" @checked(old('en', $settings->en))>
                                    <span class="form-check-label">@lang('Enable email sending')</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ev" value="1" @checked(old('ev', $settings->ev))>
                                    <span class="form-check-label">@lang('Require email verification')</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="user_approval" value="1" @checked(old('user_approval', $settings->user_approval ?? false))>
                                    <span class="form-check-label">@lang('Require admin approval')</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sv" value="1" @checked(old('sv', $settings->sv))>
                                    <span class="form-check-label">@lang('Require mobile verification')</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="kv" value="1" @checked(old('kv', $settings->kv))>
                                    <span class="form-check-label">@lang('Require KYC')</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" @checked(old('maintenance_mode', $settings->maintenance_mode))>
                                    <span class="form-check-label">@lang('Maintenance mode')</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="secure_password" value="1" @checked(old('secure_password', $settings->secure_password))>
                                    <span class="form-check-label">@lang('Secure password rules')</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">
                            <i class="ti ti-device me-1"></i>
                            @lang('Save settings')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
