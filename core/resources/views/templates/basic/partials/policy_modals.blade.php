@php
    $policyModalPages = $policyModalPages
        ?? \App\Models\Frontend::where('data_keys', 'policy_pages.element')
            ->when(activeTemplateName(), function ($q, $template) {
                $q->where('tempname', $template);
            })
            ->orderBy('id')
            ->get();
@endphp

@foreach ($policyModalPages as $policyModalPage)
    @php
        $policyModalId = 'policyModal-' . \Illuminate\Support\Str::slug((string) $policyModalPage->slug);
        $policyModalTitle = __($policyModalPage->data_values->title ?? 'Policy');
        $policyModalDetails = $policyModalPage->data_values->details ?? '';
    @endphp
    <div class="modal fade app-modal policy-page-modal" id="{{ $policyModalId }}" tabindex="-1" aria-labelledby="{{ $policyModalId }}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $policyModalId }}Label">{{ $policyModalTitle }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                </div>
                <div class="modal-body policy-page-modal__body">
                    {!! $policyModalDetails !!}
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="app-btn app-btn-primary w-100" data-bs-dismiss="modal">
                        @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<style>
    .policy-page-modal .modal-footer {
        padding: 0 22px 22px;
        background: #f8fbff;
    }

    .policy-page-modal__body {
        max-height: min(65vh, 560px);
        overflow-y: auto;
        color: #0b2033;
        line-height: 1.6;
    }

    .policy-page-modal__body h1,
    .policy-page-modal__body h2,
    .policy-page-modal__body h3,
    .policy-page-modal__body h4 {
        margin-top: 1rem;
        margin-bottom: .5rem;
        font-weight: 700;
        letter-spacing: -.02em;
    }

    .policy-page-modal__body p {
        margin-bottom: .85rem;
    }

    .policy-page-modal__body ul,
    .policy-page-modal__body ol {
        padding-left: 1.2rem;
        margin-bottom: .85rem;
    }

    @media (max-width: 575px) {
        .policy-page-modal .modal-footer {
            padding: 0 16px 16px;
        }
    }
</style>
