@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('ticket.index') }}" aria-label="@lang('Back to chat')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-App">
                <span>@lang('Chat')</span>
            </a>
            <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Settings')">
                <i class="las la-cog"></i>
            </a>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-comments"></i> @lang('New chat')</span>
            <h1>@lang('Tell us what you need help with.')</h1>
            <p>@lang('Share the subject, priority, and details so our team can respond with the right next step.')</p>
        </section>

        <div class="app-section-title">
            <div>
                <h2>@lang('Start chat')</h2>
                <p>@lang('You can attach up to 5 files if screenshots or documents help explain the issue.')</p>
            </div>
        </div>

        <div class="app-card">
            <form action="{{ route('ticket.store') }}" class="disableSubmission app-form-grid" method="post" enctype="multipart/form-data">
                @csrf

                <div class="app-form-split">
                    <label class="app-field">
                        <span>@lang('Subject')</span>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="@lang('Brief summary of your issue')" required>
                    </label>

                    <label class="app-field">
                        <span>@lang('Priority')</span>
                        <select name="priority" class="form-control" required>
                            <option value="3" @selected(old('priority') == 3)>@lang('High')</option>
                            <option value="2" @selected(old('priority', 2) == 2)>@lang('Medium')</option>
                            <option value="1" @selected(old('priority') == 1)>@lang('Low')</option>
                        </select>
                    </label>
                </div>

                <label class="app-field">
                    <span>@lang('Message')</span>
                    <textarea name="message" id="inputMessage" rows="6" class="form-control" placeholder="@lang('Describe the issue, what you expected, and any reference numbers')" required>{{ old('message') }}</textarea>
                </label>

                <div class="app-field">
                    <span>@lang('Attachments')</span>
                    <button type="button" class="app-btn app-btn-secondary addAttachment">
                        <i class="las la-paperclip"></i>
                        @lang('Add attachment')
                    </button>
                    <p class="app-support-note">
                        @lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')
                    </p>
                    <div class="app-file-uploads fileUploadsContainer"></div>
                </div>

                <button class="app-btn app-btn-primary w-100" type="submit">
                    <i class="las la-paper-plane"></i>
                    @lang('Start chat')
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function($) {
        "use strict";

        var fileAdded = 0;

        $('.addAttachment').on('click', function() {
            fileAdded++;
            if (fileAdded >= 5) {
                $(this).prop('disabled', true);
            }

            $(".fileUploadsContainer").append(`
                <div class="app-file-upload-row removeFileInput">
                    <input type="file" name="attachments[]" class="form-control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                    <button type="button" class="app-file-remove removeFile" aria-label="@lang('Remove file')">
                        <i class="las la-times"></i>
                    </button>
                </div>
            `);
        });

        $(document).on('click', '.removeFile', function() {
            $('.addAttachment').prop('disabled', false);
            fileAdded--;
            $(this).closest('.removeFileInput').remove();
        });
    })(jQuery);
</script>
@endpush
