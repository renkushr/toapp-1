<div class="list-group list-group-flush portal-help-list">
    @foreach([
        ['Super Admin', 'Full owner access. Keep this for trusted operators only.', 'All'],
        ['Operations', 'Members, packages, referral reports, support and readiness.', 'Ops'],
        ['Finance', 'Deposit, withdrawal, package approval, balance adjustment and reports.', 'Money'],
        ['Support', 'Help desk and member review without money controls.', 'Care'],
        ['Viewer', 'Read-only dashboard and reports for owner review.', 'Read'],
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
