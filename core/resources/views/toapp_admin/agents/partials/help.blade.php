<div class="list-group list-group-flush portal-help-list">
    <div class="list-group-item">
        <strong>@lang('Registration')</strong>
        <div class="text-secondary">@lang('Users may enter an agent license number during signup (optional). ZIP code can also auto-match an agent.')</div>
    </div>
    <div class="list-group-item">
        <strong>@lang('ZIP code coverage')</strong>
        <div class="text-secondary">{!! __('Admins assign ZIP codes by state from a searchable dropdown, or use <strong>Select all USA</strong> for nationwide coverage. Expand a state to browse and select any number of ZIP codes. Agents cannot edit coverage.') !!}</div>
    </div>
    <div class="list-group-item">
        <strong>@lang('Validation')</strong>
        <div class="text-secondary">@lang("The ZIP code must exist in the agent's coverage list.")</div>
    </div>
    <div class="list-group-item">
        <strong>@lang('Agent portal')</strong>
        <div class="text-secondary">{!! __('Agents sign in at <code>:url</code> to view clients, applications, and chat.', ['url' => rtrim(config('toapp.agent_url'), '/') . '/agent']) !!}</div>
    </div>
    <div class="list-group-item">
        <strong>@lang('Sample data')</strong>
        <div class="text-secondary">{!! __('Run <code>php artisan toapp:seed-agents</code> and <code>toapp:seed-products</code> for demo data.') !!}</div>
    </div>
</div>
