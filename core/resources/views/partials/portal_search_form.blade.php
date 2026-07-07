<form class="portal-search-form" method="GET">
    @foreach($preserve ?? [] as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <input
        type="search"
        class="form-control"
        name="{{ $name ?? 'search' }}"
        value="{{ $value ?? request('search') }}"
        placeholder="{{ $placeholder }}"
    >
    <button class="btn btn-primary portal-search-submit" type="submit">
        <i class="ti ti-search me-1"></i> Search
    </button>
</form>
