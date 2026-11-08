@php
    $variant = $variant ?? 'portal'; // portal | user | compact
    $supported = config('toapp_languages.supported', []);
    $codes = array_keys($supported);
    $dbLanguages = \App\Models\Language::query()
        ->whereIn('code', $codes)
        ->get()
        ->keyBy('code');

    $languages = collect($supported)->map(function ($meta, $code) use ($dbLanguages) {
        $row = $dbLanguages->get($code);
        return (object) [
            'code' => $code,
            'name' => $meta['name'],
            'native' => $meta['native'],
            'image' => $row->image ?? null,
        ];
    })->values();

    // Prefer config order even if DB is empty yet.
    if ($languages->isEmpty()) {
        $languages = collect($supported)->map(fn ($meta, $code) => (object) [
            'code' => $code,
            'name' => $meta['name'],
            'native' => $meta['native'],
            'image' => null,
        ])->values();
    }

    $currentCode = session('lang', app()->getLocale() ?: 'en');
    if (! isset($supported[$currentCode])) {
        $currentCode = 'en';
    }
    $current = $languages->firstWhere('code', $currentCode) ?? $languages->first();
    $iconClass = $variant === 'portal' ? 'ti ti-language' : 'las la-globe';
    $caretClass = $variant === 'portal' ? 'ti ti-chevron-down' : 'las la-angle-down';
@endphp

@if($languages->isNotEmpty())
<div class="toapp-lang-switcher toapp-lang-switcher--{{ $variant }}" data-lang-switcher>
    <button type="button" class="toapp-lang-switcher__btn" aria-haspopup="listbox" aria-expanded="false" title="@lang('Language')">
        <i class="{{ $iconClass }}" aria-hidden="true"></i>
        <span class="toapp-lang-switcher__label">{{ $current->native }}</span>
        <i class="{{ $caretClass }} toapp-lang-switcher__caret" aria-hidden="true"></i>
    </button>
    <div class="toapp-lang-switcher__menu" role="listbox" hidden>
        @foreach($languages as $item)
            <a
                class="toapp-lang-switcher__item {{ $item->code === $currentCode ? 'is-active' : '' }}"
                href="{{ route('lang', $item->code) }}"
                role="option"
                aria-selected="{{ $item->code === $currentCode ? 'true' : 'false' }}"
            >
                <strong>{{ $item->native }}</strong>
                <span>{{ $item->name }}</span>
            </a>
        @endforeach
    </div>
</div>
@endif
