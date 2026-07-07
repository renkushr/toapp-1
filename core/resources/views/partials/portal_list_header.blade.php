<div class="card-header portal-list-header">
    <h3 class="card-title">{{ $title }}</h3>
    <div class="portal-list-toolbar">
        @isset($toolbarView)
            @include($toolbarView)
        @elseif(isset($toolbar))
            {!! $toolbar !!}
        @endif
    </div>
</div>
