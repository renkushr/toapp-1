@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    @include('partials.portal_list_header', [
        'title' => 'Launch readiness · ' . $readyCount . '/' . count($checks) . ' checks passing',
        'toolbarView' => 'partials.portal_list_toolbar_readiness',
    ])
    <div class="card-body">
        <div class="list-group list-group-flush">
            @foreach($checks as $check)
                <div class="list-group-item d-flex gap-3 align-items-start">
                    <span class="avatar {{ $check['ok'] ? 'bg-success-lt text-success' : 'bg-danger-lt text-danger' }}">
                        <i class="ti {{ $check['ok'] ? 'ti-check' : 'ti-alert-circle' }}"></i>
                    </span>
                    <div>
                        <strong>{{ $check['title'] }}</strong>
                        <div class="text-secondary">{{ $check['detail'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@include('partials.portal_help_modal', [
    'id' => 'readinessChecklistModal',
    'title' => 'Production checklist',
    'partial' => 'toapp_admin.readiness.partials.checklist',
])
@endsection
