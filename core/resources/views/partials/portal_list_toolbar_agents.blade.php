<button type="button" class="btn btn-ghost-secondary" data-bs-toggle="modal" data-bs-target="#agentHelpModal">
    <i class="ti ti-help-circle me-1"></i> How it works
</button>
@include('partials.portal_search_form', ['placeholder' => 'Search agents'])
<a href="{{ route('toapp.admin.agents.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i> New agent</a>
