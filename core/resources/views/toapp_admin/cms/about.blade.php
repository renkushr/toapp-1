@extends('toapp_admin.layouts.app')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="card-subtitle">@lang('CMS')</div>
            <h3 class="card-title mb-0">@lang('About Us')</h3>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('about.us') }}" target="_blank" rel="noopener">
            <i class="ti ti-external-link me-1"></i> @lang('View page')
        </a>
    </div>
    <div class="card-body">
        <p class="text-secondary mb-4">
            {!! __('Write the public About Us page. Use bold/italic, links, and images. Changes appear at <code>:url</code> after you save.', ['url' => url('/about-us')]) !!}
        </p>

        <form method="POST" action="{{ route('toapp.admin.cms.about.update') }}" id="about-us-form">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label" for="about-title">@lang('Title')</label>
                <input
                    type="text"
                    class="form-control"
                    id="about-title"
                    name="title"
                    value="{{ old('title', $page->title) }}"
                    maxlength="180"
                    required
                >
                @error('title')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="about-editor">@lang('Content')</label>
                <div id="about-editor" class="about-quill-editor">{!! old('body', $page->body) !!}</div>
                <input type="hidden" name="body" id="about-body" value="{{ old('body', $page->body) }}">
                @error('body')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                <small class="form-hint">@lang('Toolbar: bold, italic, underline, lists, link, and image upload.')</small>
            </div>

            <div class="mb-4">
                <label class="form-check form-switch">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="is_published"
                        value="1"
                        @checked(old('is_published', $page->is_published))
                    >
                    <span class="form-check-label">@lang('Published (visible on the public About Us page)')</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="ti ti-device-floppy me-1"></i> @lang('Save About Us')
            </button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
.about-quill-editor {
    min-height: 360px;
    background: #fff;
    border-radius: 0 0 12px 12px;
}
.about-quill-editor .ql-editor {
    min-height: 320px;
    font-size: 15px;
    line-height: 1.6;
}
.about-quill-editor .ql-editor img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}
.ql-toolbar.ql-snow {
    border-radius: 12px 12px 0 0;
    background: #f8fafc;
}
</style>
@endpush

@push('script-lib')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
@endpush

@push('scripts')
<script>
(function () {
    const editorEl = document.getElementById('about-editor');
    const bodyInput = document.getElementById('about-body');
    const form = document.getElementById('about-us-form');
    if (!editorEl || !bodyInput || !form || typeof Quill === 'undefined') return;

    const uploadUrl = @json(route('toapp.admin.cms.upload'));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content
        || form.querySelector('input[name="_token"]')?.value;
    const i18n = {
        uploadFailed: @json(__('Upload failed')),
        imageUploadFailed: @json(__('Image upload failed')),
        placeholder: @json(__('Write your About Us story…')),
    };

    function imageHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/png,image/jpeg,image/jpg,image/webp,image/gif');
        input.click();
        input.onchange = async function () {
            const file = input.files?.[0];
            if (!file) return;
            const data = new FormData();
            data.append('image', file);
            try {
                const res = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: data,
                    credentials: 'same-origin',
                });
                const json = await res.json();
                if (!res.ok || !json.url) {
                    throw new Error(json.message || i18n.uploadFailed);
                }
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', json.url, 'user');
                quill.setSelection(range.index + 1);
            } catch (err) {
                alert(err.message || i18n.imageUploadFailed);
            }
        };
    }

    const quill = new Quill(editorEl, {
        theme: 'snow',
        placeholder: i18n.placeholder,
        modules: {
            toolbar: {
                container: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link', 'image'],
                    ['clean'],
                ],
                handlers: {
                    image: imageHandler,
                },
            },
        },
    });

    form.addEventListener('submit', function () {
        bodyInput.value = quill.root.innerHTML;
    });
})();
</script>
@endpush
