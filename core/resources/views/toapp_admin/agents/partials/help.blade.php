<div class="list-group list-group-flush portal-help-list">
    <div class="list-group-item">
        <strong>Registration</strong>
        <div class="text-secondary">Users may enter an agent license number during signup (optional). ZIP code can also auto-match an agent.</div>
    </div>
    <div class="list-group-item">
        <strong>ZIP code coverage</strong>
        <div class="text-secondary">Admins assign ZIP codes by state from a searchable dropdown, or use <strong>Select all USA</strong> for nationwide coverage. Expand a state to browse and select any number of ZIP codes. Agents cannot edit coverage.</div>
    </div>
    <div class="list-group-item">
        <strong>Validation</strong>
        <div class="text-secondary">The ZIP code must exist in the agent's coverage list.</div>
    </div>
    <div class="list-group-item">
        <strong>Agent portal</strong>
        <div class="text-secondary">Agents sign in at <code>{{ config('toapp.agent_url') }}/agent</code> to view clients, applications, and chat.</div>
    </div>
    <div class="list-group-item">
        <strong>Sample data</strong>
        <div class="text-secondary">Run <code>php artisan toapp:seed-agents</code> and <code>toapp:seed-products</code> for demo data.</div>
    </div>
</div>
