@extends('toapp_agent.layouts.app')

@section('content')
<section class="ta-panel">
    <div class="ta-panel-head"><div><span class="ta-kicker">Applications</span><h2>Third-party quotes</h2></div></div>
    <div class="ta-table-wrap">
        <table class="ta-table">
            <thead><tr><th>Ref</th><th>Customer</th><th>Product</th><th>ZIP</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>{{ $app->reference }}</td>
                        <td>{{ $app->user?->firstname }} {{ $app->user?->lastname }}</td>
                        <td>{{ $app->plan?->name ?: '—' }}</td>
                        <td>{{ $app->zipcode }}</td>
                        <td>{{ $app->statusLabel() }}</td>
                        <td><a href="{{ route('toapp.agent.applications.show', $app) }}">Open</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6">No applications in your ZIP areas yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $applications->links() }}
</section>
@endsection
