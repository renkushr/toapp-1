@extends('toapp_admin.layouts.app')

@section('content')
@php
    $method = old('email_method', $mail->name ?? 'smtp');
@endphp

<div class="row row-cards">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('toapp.admin.settings.edit') }}" class="btn btn-outline-secondary">@lang('Core settings')</a>
            <a href="{{ route('toapp.admin.settings.email') }}" class="btn btn-primary">@lang('Email / SMTP')</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title mb-0">@lang('Email delivery')</h3>
                    <div class="text-secondary">@lang('Configure how OTP and system emails are sent.')</div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('toapp.admin.settings.email.update') }}" class="row g-3" id="emailSettingsForm">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label">@lang('Email from address')</label>
                        <input type="email" name="email_from" class="form-control" value="{{ old('email_from', $settings->email_from) }}" placeholder="you@gmail.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('Sender name')</label>
                        <input type="text" name="email_from_name" class="form-control" value="{{ old('email_from_name', $settings->email_from_name) }}" placeholder="To-App">
                    </div>

                    <div class="col-12">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="en" value="1" @checked(old('en', $settings->en))>
                            <span class="form-check-label">@lang('Enable email sending')</span>
                        </label>
                    </div>

                    <div class="col-12">
                        <label class="form-label">@lang('Send method')</label>
                        <select name="email_method" id="emailMethod" class="form-select">
                            <option value="php" @selected($method === 'php')>@lang('PHP Mail')</option>
                            <option value="smtp" @selected($method === 'smtp')>@lang('SMTP (Gmail / Resend / others)')</option>
                            <option value="sendgrid" @selected($method === 'sendgrid')>@lang('SendGrid API')</option>
                            <option value="mailjet" @selected($method === 'mailjet')>@lang('Mailjet API')</option>
                        </select>
                    </div>

                    <div class="col-12 email-panel" data-panel="smtp" @if($method !== 'smtp') hidden @endif>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">@lang('Host')</label>
                                <input type="text" name="host" class="form-control" value="{{ old('host', $mail->host ?? 'smtp.gmail.com') }}" placeholder="smtp.gmail.com">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">@lang('Port')</label>
                                <input type="text" name="port" class="form-control" value="{{ old('port', $mail->port ?? '587') }}" placeholder="587">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">@lang('Encryption')</label>
                                <select name="enc" class="form-select">
                                    <option value="tls" @selected(old('enc', $mail->enc ?? 'tls') === 'tls')>@lang('TLS')</option>
                                    <option value="ssl" @selected(old('enc', $mail->enc ?? '') === 'ssl')>@lang('SSL')</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">@lang('Username')</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username', $mail->username ?? '') }}" placeholder="you@gmail.com" autocomplete="username">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">@lang('Password / App Password')</label>
                                <input type="password" name="password" class="form-control" value="{{ old('password', $mail->password ?? '') }}" placeholder="@lang('16-character app password')" autocomplete="new-password">
                                <div class="form-hint">{!! __('Gmail App Password is exactly 16 letters (e.g. <code>abcdefghijklmnop</code>). Do not use your normal Gmail password.') !!}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 email-panel" data-panel="sendgrid" @if($method !== 'sendgrid') hidden @endif>
                        <label class="form-label">@lang('SendGrid App Key')</label>
                        <input type="text" name="appkey" class="form-control" value="{{ old('appkey', $mail->appkey ?? '') }}">
                    </div>

                    <div class="col-12 email-panel" data-panel="mailjet" @if($method !== 'mailjet') hidden @endif>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">@lang('Public key')</label>
                                <input type="text" name="public_key" class="form-control" value="{{ old('public_key', $mail->public_key ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">@lang('Secret key')</label>
                                <input type="text" name="secret_key" class="form-control" value="{{ old('secret_key', $mail->secret_key ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">
                            <i class="ti ti-device me-1"></i>
                            @lang('Save email settings')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title mb-0">@lang('Send test email')</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('toapp.admin.settings.email.test') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">@lang('Recipient')</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="your@email.com" required>
                    </div>
                    <button class="btn btn-outline-primary w-100" type="submit">
                        <i class="ti ti-mail me-1"></i>
                        @lang('Send test')
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="text-secondary mb-2">@lang('Gmail quick setup')</div>
                <ol class="ps-3 mb-0 text-secondary" style="font-size: 0.9rem; line-height: 1.55;">
                    <li>@lang('Enable 2-Step Verification')</li>
                    <li>@lang('Create an App Password')</li>
                    <li>@lang('Method: SMTP · Host: smtp.gmail.com · Port: 587 · TLS')</li>
                    <li>@lang('Password: 16 characters, no spaces')</li>
                    <li>@lang('Email From must be the same Gmail address as Username')</li>
                    <li>@lang('If you see “Could not authenticate”, you are using the wrong password type')</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const form = document.getElementById('emailSettingsForm');
        if (!form) return;

        const methodSelect = document.getElementById('emailMethod');
        const panels = form.querySelectorAll('.email-panel');

        function syncPanels() {
            const selected = methodSelect.value;
            panels.forEach(function (panel) {
                panel.hidden = panel.dataset.panel !== selected;
            });
        }

        methodSelect.addEventListener('change', syncPanels);
        syncPanels();
    })();
</script>
@endpush
