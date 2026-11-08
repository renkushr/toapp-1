<div class="list-group list-group-flush portal-help-list">
    @foreach([
        [__('Super Admin'), __('Full owner access. Keep this for trusted operators only.'), __('All')],
        [__('Operations'), __('Members, packages, referral reports, support and readiness.'), __('Ops')],
        [__('Finance'), __('Deposit, withdrawal, package approval, balance adjustment and reports.'), __('Money')],
        [__('Support'), __('Help desk and member review without money controls.'), __('Care')],
        [__('Viewer'), __('Read-only dashboard and reports for owner review.'), __('Read')],
    ] as [$role, $desc, $tag])
        <div class="list-group-item d-flex justify-content-between gap-3">
            <div>
                <strong>{{ $role }}</strong>
                <div class="text-secondary">{{ $desc }}</div>
            </div>
            <span class="badge bg-secondary-lt">{{ $tag }}</span>
        </div>
    @endforeach
</div>
