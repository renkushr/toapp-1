@php
    $isEdit = (bool) $agent;
    $initialByState = $selectedZipsByState ?? [];
    $zipSelectAllUsa = (bool) ($zipSelectAllUsa ?? false);
    $zipCatalogTotal = (int) ($zipCatalogTotal ?? 0);
    if (old('zip_codes') !== null) {
        $zipSelectAllUsa = (bool) old('zip_select_all_usa') || old('zip_codes') === 'ALL';
        if ($zipSelectAllUsa) {
            $initialByState = [];
        } else {
            $oldZips = old('zip_codes');
            if (is_string($oldZips)) {
                $oldZips = preg_split('/[\s,;]+/', $oldZips) ?: [];
            }
            $initialByState = \App\Support\UsZipCatalog::groupZipsByState(is_array($oldZips) ? $oldZips : []);
        }
    }
    $statesMeta = collect(\App\Support\UsZipCatalog::states())->keyBy('abbr');
    if ($zipCatalogTotal <= 0) {
        $zipCatalogTotal = (int) $statesMeta->sum('count');
    }
@endphp
<form method="POST" action="{{ $action }}" id="agentForm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ $isEdit ? __('Agent profile') : __('New agent') }}</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">@lang('Full name')</label>
                    <input class="form-control" name="name" value="{{ old('name', $agent?->name) }}" required maxlength="120">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">@lang('License number')</label>
                    <input class="form-control" name="license_no" value="{{ old('license_no', $agent?->license_no) }}" required maxlength="80" placeholder="AG-12345">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">@lang('Email')</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', $agent?->email) }}" required maxlength="255">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">@lang('Username')</label>
                    <input class="form-control" name="username" value="{{ old('username', $agent?->username) }}" required maxlength="80">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ $isEdit ? __('New password') : __('Password') }}</label>
                    <input class="form-control" type="password" name="password" @unless($isEdit) required @endunless minlength="8" placeholder="{{ $isEdit ? __('Leave blank to keep current') : '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">@lang('Agent fee (%)')</label>
                    <div class="input-group">
                        <input class="form-control" type="number" name="fee_percent" value="{{ old('fee_percent', $agent?->fee_percent ?? '0') }}" required min="0" max="100" step="0.01">
                        <span class="input-group-text">%</span>
                    </div>
                    <small class="form-hint">@lang('Platform fee the agent pays per product sale. Only admins can change this.')</small>
                    @error('fee_percent')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <label class="form-check">
                        <input class="form-check-input" type="checkbox" name="status" value="1" @checked(old('status', $isEdit ? (int) $agent->status === 1 : true))>
                        <span class="form-check-label">@lang('Active agent account')</span>
                    </label>
                </div>
            </div>

            <div class="mb-0" id="zipPicker"
                 data-states-url="{{ route('toapp.admin.agents.zip_states') }}"
                 data-all-url="{{ route('toapp.admin.agents.zip_all') }}"
                 data-state-url-template="{{ route('toapp.admin.agents.zip_state', ['state' => '__STATE__']) }}"
                 data-initial='@json($initialByState)'
                 data-states-meta='@json($statesMeta)'
                 data-select-all-usa="{{ $zipSelectAllUsa ? '1' : '0' }}"
                 data-catalog-total="{{ $zipCatalogTotal }}">
                <label class="form-label required">@lang('ZIP codes (USA)')</label>
                <small class="form-hint d-block mb-2">{!! __('Search and add states, expand to browse every ZIP code in that state — or use <strong>Select all USA</strong> to cover the entire country.') !!}</small>

                <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                    <div class="text-secondary">
                        <strong id="zipSelectedCount">0</strong> @lang('ZIP codes selected')
                        · <strong id="zipStateCount">0</strong> @lang('states')
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="zipSelectAllUsa">@lang('Select all USA')</button>
                        <button type="button" class="btn btn-sm" id="zipClearAll" disabled>@lang('Clear all')</button>
                    </div>
                </div>

                <div id="zipAllUsaSummary" class="zip-all-usa-summary {{ $zipSelectAllUsa ? '' : 'd-none' }} mb-3">
                    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                        <div>
                            <div class="fw-bold mb-1"><i class="ti ti-map-pin me-1"></i> @lang('All USA ZIP codes selected')</div>
                            <div class="text-secondary small" id="zipAllUsaSummaryText">@lang('Nationwide coverage is active.')</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="zipToggleAllUsaDetails">
                            @lang('Show states')
                        </button>
                    </div>
                </div>

                <div id="zipDetailsWrap" class="{{ $zipSelectAllUsa ? 'is-collapsed d-none' : '' }}" @if($zipSelectAllUsa) hidden @endif>
                    <div class="zip-picker-search position-relative mb-3">
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                            <input type="search" class="form-control" id="zipStateSearch" placeholder="@lang('Search state (e.g. California or CA)…')" autocomplete="off">
                        </div>
                        <div class="zip-picker-dropdown list-group d-none" id="zipStateResults" role="listbox"></div>
                    </div>
                    <div id="zipStatePanels" class="zip-state-panels"></div>
                    <div id="zipEmptyHint" class="text-secondary border rounded p-3">@lang('No states added yet. Use the search box above to add a state, then expand it to pick ZIP codes — or click Select all USA.')</div>
                </div>

                <div id="zipHiddenInputs"></div>
                @error('zip_codes')<div class="text-danger mt-2">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary" type="submit">{{ $isEdit ? __('Save changes') : __('Create agent') }}</button>
            <a class="btn" href="{{ route('toapp.admin.agents.index') }}">@lang('Cancel')</a>
        </div>
    </div>
</form>

@push('scripts')
<style>
.zip-picker-dropdown {
    position: absolute;
    z-index: 30;
    left: 0;
    right: 0;
    top: calc(100% + 4px);
    max-height: 280px;
    overflow: auto;
    border: 1px solid var(--tblr-border-color, #e6e7e9);
    border-radius: 6px;
    background: var(--tblr-bg-surface, #fff);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
}
.zip-picker-dropdown .list-group-item {
    cursor: pointer;
    border-left: 0;
    border-right: 0;
}
.zip-picker-dropdown .list-group-item:hover,
.zip-picker-dropdown .list-group-item.active {
    background: rgba(32, 107, 196, 0.08);
}
.zip-state-panels .zip-state-panel {
    border: 1px solid var(--tblr-border-color, #e6e7e9);
    border-radius: 8px;
    margin-bottom: 0.75rem;
    overflow: hidden;
    background: var(--tblr-bg-surface, #fff);
}
.zip-state-panel__head {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: rgba(32, 107, 196, 0.04);
    cursor: pointer;
    user-select: none;
}
.zip-state-panel__head:hover { background: rgba(32, 107, 196, 0.08); }
.zip-state-panel__meta { color: var(--tblr-secondary, #667382); font-size: 0.85rem; }
.zip-state-panel__actions { margin-left: auto; display: flex; gap: 0.35rem; flex-wrap: wrap; }
.zip-state-panel__body {
    display: none;
    border-top: 1px solid var(--tblr-border-color, #e6e7e9);
    padding: 0.75rem 1rem 1rem;
}
.zip-state-panel.is-open .zip-state-panel__body { display: block; }
.zip-state-panel.is-open .zip-chevron { transform: rotate(90deg); }
.zip-chevron { transition: transform .15s ease; display: inline-block; }
.zip-zip-filter { margin-bottom: 0.75rem; }
.zip-zip-list {
    max-height: 320px;
    overflow: auto;
    border: 1px solid var(--tblr-border-color, #e6e7e9);
    border-radius: 6px;
    padding: 0.35rem 0.5rem;
}
.zip-zip-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.28rem 0.25rem;
    border-radius: 4px;
}
.zip-zip-row:hover { background: rgba(32, 107, 196, 0.06); }
.zip-zip-row code { min-width: 4.5rem; }
.zip-chip-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
    margin-top: 0.75rem;
}
.zip-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.15rem 0.45rem;
    border-radius: 999px;
    background: rgba(32, 107, 196, 0.1);
    font-size: 0.8rem;
}
.zip-chip button {
    border: 0;
    background: transparent;
    padding: 0;
    line-height: 1;
    cursor: pointer;
    color: inherit;
}
.zip-all-usa-summary {
    border: 1px solid rgba(32, 107, 196, 0.25);
    border-radius: 8px;
    padding: 0.9rem 1rem;
    background: rgba(32, 107, 196, 0.06);
}
#zipPicker.zip-details-collapsed #zipDetailsWrap,
#zipDetailsWrap.is-collapsed {
    display: none !important;
}
</style>
<script>
(function () {
    const root = document.getElementById('zipPicker');
    if (!root) return;

    const statesUrl = root.dataset.statesUrl;
    const allUrl = root.dataset.allUrl;
    const stateUrlTemplate = root.dataset.stateUrlTemplate;
    const statesMeta = JSON.parse(root.dataset.statesMeta || '{}');
    const initial = JSON.parse(root.dataset.initial || '{}');
    const detailsWrap = document.getElementById('zipDetailsWrap');
    const initialSelectAllUsa = root.dataset.selectAllUsa === '1';
    const initialCatalogTotal = Number(root.dataset.catalogTotal || 0);

    /** @type {Record<string, Set<string>>} */
    const selected = {};
    Object.keys(initial).forEach((abbr) => {
        selected[abbr] = new Set(initial[abbr] || []);
    });

    /** @type {Record<string, Array<{zip:string,city:string}>>} */
    const zipCache = {};
    let selectAllUsa = false;
    let catalogTotal = initialCatalogTotal || Object.values(statesMeta).reduce((n, meta) => n + (Number(meta?.count) || 0), 0);
    let allUsaDetailsOpen = false;
    /** @type {string[]} */
    let nationwideAbbrs = Object.keys(statesMeta).filter((abbr) => abbr !== 'OTHER').sort((a, b) => {
        return ((statesMeta[a]?.name || a)).localeCompare(statesMeta[b]?.name || b);
    });

    const searchInput = document.getElementById('zipStateSearch');
    const resultsEl = document.getElementById('zipStateResults');
    const panelsEl = document.getElementById('zipStatePanels');
    const emptyHint = document.getElementById('zipEmptyHint');
    const hiddenEl = document.getElementById('zipHiddenInputs');
    const countEl = document.getElementById('zipSelectedCount');
    const stateCountEl = document.getElementById('zipStateCount');
    const clearAllBtn = document.getElementById('zipClearAll');
    const selectAllUsaBtn = document.getElementById('zipSelectAllUsa');
    const allUsaSummary = document.getElementById('zipAllUsaSummary');
    const allUsaSummaryText = document.getElementById('zipAllUsaSummaryText');
    const toggleAllUsaDetailsBtn = document.getElementById('zipToggleAllUsaDetails');
    const form = document.getElementById('agentForm');
    const i18n = {
        otherUnknown: @json(__('Other / unknown')),
        zipAcrossCustom: @json(__(':total ZIP codes across :states states. Use Show states only if you need to customize.')),
        zipAcross: @json(__(':total ZIP codes across :states states.')),
        hideStates: @json(__('Hide states')),
        showStates: @json(__('Show states')),
        allUsaSelected: @json(__('All USA selected')),
        selectAllUsa: @json(__('Select all USA')),
        failedNationwide: @json(__('Failed to load nationwide ZIP codes')),
        selectedOfZips: @json(__(':selected selected / :total ZIP codes')),
        failedLoadZips: @json(__('Failed to load ZIP codes')),
        noZipMatch: @json(__('No ZIP codes match this filter.')),
        couldNotUpdateZip: @json(__('Could not update ZIP selection.')),
        loadingZips: @json(__('Loading ZIP codes…')),
        couldNotLoadZips: @json(__('Could not load ZIP codes.')),
        clear: @json(__('Clear')),
        remove: @json(__('Remove')),
        selectAll: @json(__('Select all')),
        filterZipCity: @json(__('Filter ZIP code or city in :state…')),
        couldNotRemoveState: @json(__('Could not remove state.')),
        couldNotClearState: @json(__('Could not clear state.')),
        couldNotSelectAllZips: @json(__('Could not select all ZIP codes.')),
        noStatesFound: @json(__('No states found')),
        zipCodesCount: @json(__(':count ZIP codes')),
        couldNotAddState: @json(__('Could not add state.')),
        selectAtLeastOneZip: @json(__('Select at least one ZIP code.')),
    };

    function t(template, replacements) {
        return Object.keys(replacements).reduce(function (s, key) {
            return s.split(':' + key).join(String(replacements[key]));
        }, template);
    }

    let searchTimer = null;
    let activeIndex = -1;
    let materializePromise = null;

    function stateName(abbr) {
        if (abbr === 'OTHER') return i18n.otherUnknown;
        return (statesMeta[abbr] && statesMeta[abbr].name) || abbr;
    }

    function stateCount(abbr) {
        if (abbr === 'OTHER') return selected[abbr]?.size || 0;
        return (statesMeta[abbr] && statesMeta[abbr].count) || 0;
    }

    function totalSelected() {
        return Object.values(selected).reduce((n, set) => n + set.size, 0);
    }

    function isNationwideSelection() {
        if (!nationwideAbbrs.length || !catalogTotal) return false;
        let total = 0;
        for (let i = 0; i < nationwideAbbrs.length; i++) {
            const abbr = nationwideAbbrs[i];
            const need = stateCount(abbr);
            const have = selected[abbr]?.size || 0;
            if (have < need) return false;
            total += have;
        }
        return total >= catalogTotal;
    }

    function clearSelectedMaps() {
        Object.keys(selected).forEach((k) => delete selected[k]);
    }

    function setDetailsCollapsed(collapsed) {
        root.classList.toggle('zip-details-collapsed', collapsed);
        if (detailsWrap) {
            detailsWrap.classList.toggle('is-collapsed', collapsed);
            detailsWrap.classList.toggle('d-none', collapsed);
            detailsWrap.hidden = collapsed;
        }
        if (collapsed && panelsEl) {
            panelsEl.innerHTML = '';
        }
    }

    function markSelectAllUsa(on) {
        selectAllUsa = !!on;
        if (!selectAllUsa) {
            allUsaDetailsOpen = false;
        }
        updateAllUsaUi();
    }

    function updateAllUsaUi() {
        if (allUsaSummary) {
            allUsaSummary.classList.toggle('d-none', !selectAllUsa);
        }
        setDetailsCollapsed(selectAllUsa && !allUsaDetailsOpen);
        if (allUsaSummaryText) {
            const total = selectAllUsa && catalogTotal ? catalogTotal : totalSelected();
            const states = selectAllUsa ? nationwideAbbrs.length : Object.keys(selected).length;
            allUsaSummaryText.textContent = selectAllUsa
                ? t(i18n.zipAcrossCustom, { total: total.toLocaleString(), states: states })
                : t(i18n.zipAcross, { total: total.toLocaleString(), states: states });
        }
        if (toggleAllUsaDetailsBtn) {
            toggleAllUsaDetailsBtn.textContent = allUsaDetailsOpen ? i18n.hideStates : i18n.showStates;
        }
    }

    function syncHidden() {
        const zips = [];
        if (!selectAllUsa) {
            Object.keys(selected).sort().forEach((abbr) => {
                [...selected[abbr]].sort().forEach((zip) => zips.push(zip));
            });
        }
        hiddenEl.innerHTML = '';

        const allFlag = document.createElement('input');
        allFlag.type = 'hidden';
        allFlag.name = 'zip_select_all_usa';
        allFlag.value = selectAllUsa ? '1' : '0';
        hiddenEl.appendChild(allFlag);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'zip_codes';
        input.value = selectAllUsa ? 'ALL' : zips.join(',');
        hiddenEl.appendChild(input);

        countEl.textContent = String(selectAllUsa && catalogTotal ? catalogTotal : zips.length);
        stateCountEl.textContent = String(selectAllUsa ? nationwideAbbrs.length : Object.keys(selected).length);
        clearAllBtn.disabled = !selectAllUsa && zips.length === 0 && Object.keys(selected).length === 0;
        emptyHint.classList.toggle('d-none', Object.keys(selected).length > 0 || selectAllUsa);
        if (selectAllUsaBtn) {
            selectAllUsaBtn.disabled = false;
            selectAllUsaBtn.textContent = selectAllUsa ? i18n.allUsaSelected : i18n.selectAllUsa;
            selectAllUsaBtn.classList.toggle('btn-primary', selectAllUsa);
            selectAllUsaBtn.classList.toggle('btn-outline-primary', !selectAllUsa);
        }
        updateAllUsaUi();
    }

    async function materializeNationwideSelection() {
        if (!selectAllUsa) return;
        if (materializePromise) {
            await materializePromise;
            return;
        }
        materializePromise = (async () => {
            const res = await fetch(allUrl, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error(i18n.failedNationwide);
            const data = await res.json();
            const byState = data.by_state || {};
            catalogTotal = Number(data.total || catalogTotal);
            clearSelectedMaps();
            Object.keys(byState).forEach((abbr) => {
                selected[abbr] = new Set(byState[abbr] || []);
                zipCache[abbr] = (byState[abbr] || []).map((zip) => ({ zip, city: '' }));
            });
            markSelectAllUsa(false);
        })();
        try {
            await materializePromise;
        } finally {
            materializePromise = null;
        }
    }

    async function ensureState(abbr) {
        if (selectAllUsa) {
            await materializeNationwideSelection();
        }
        if (!selected[abbr]) selected[abbr] = new Set();
        markSelectAllUsa(false);
        renderPanels();
        syncHidden();
    }

    async function removeState(abbr) {
        if (selectAllUsa) {
            await materializeNationwideSelection();
        }
        delete selected[abbr];
        markSelectAllUsa(false);
        renderPanels();
        syncHidden();
    }

    async function toggleZip(abbr, zip, on) {
        if (selectAllUsa) {
            await materializeNationwideSelection();
        }
        if (!selected[abbr]) selected[abbr] = new Set();
        if (on) selected[abbr].add(zip);
        else selected[abbr].delete(zip);
        markSelectAllUsa(false);
        updatePanelMeta(abbr);
        syncHidden();
    }

    function updatePanelMeta(abbr) {
        const panel = panelsEl.querySelector(`[data-state="${abbr}"]`);
        if (!panel) return;
        const meta = panel.querySelector('.zip-state-panel__meta');
        const selectedCount = selectAllUsa ? stateCount(abbr) : (selected[abbr]?.size || 0);
        if (meta) {
            meta.textContent = t(i18n.selectedOfZips, { selected: selectedCount, total: stateCount(abbr) });
        }
        const rows = panel.querySelectorAll('.zip-zip-row input[type="checkbox"]');
        rows.forEach((cb) => {
            cb.checked = selectAllUsa ? true : (selected[abbr]?.has(cb.value) || false);
        });
    }

    async function loadZips(abbr, query) {
        if (abbr === 'OTHER') {
            const rows = [...(selected[abbr] || [])].sort().map((zip) => ({ zip, city: '' }));
            const needle = (query || '').toLowerCase();
            return needle ? rows.filter((r) => r.zip.includes(needle)) : rows;
        }

        if (!query && zipCache[abbr]) return zipCache[abbr];

        const url = stateUrlTemplate.replace('__STATE__', encodeURIComponent(abbr)) + (query ? ('?q=' + encodeURIComponent(query)) : '');
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error(i18n.failedLoadZips);
        const data = await res.json();
        if (!query) zipCache[abbr] = data.zips || [];
        return data.zips || [];
    }

    function renderZipList(panel, abbr, zips) {
        const list = panel.querySelector('.zip-zip-list');
        if (!list) return;
        if (!zips.length) {
            list.innerHTML = '<div class="text-secondary p-2">' + i18n.noZipMatch + '</div>';
            return;
        }
        const frag = document.createDocumentFragment();
        zips.forEach((row) => {
            const label = document.createElement('label');
            label.className = 'zip-zip-row';
            const cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.className = 'form-check-input m-0';
            cb.value = row.zip;
            cb.checked = selectAllUsa ? true : (selected[abbr]?.has(row.zip) || false);
            cb.addEventListener('change', () => {
                toggleZip(abbr, row.zip, cb.checked).catch((err) => alert(err.message || i18n.couldNotUpdateZip));
            });
            const code = document.createElement('code');
            code.textContent = row.zip;
            const city = document.createElement('span');
            city.className = 'text-secondary';
            city.textContent = row.city || '';
            label.appendChild(cb);
            label.appendChild(code);
            label.appendChild(city);
            frag.appendChild(label);
        });
        list.innerHTML = '';
        list.appendChild(frag);
    }

    async function openPanel(panel, abbr) {
        panel.classList.add('is-open');
        const list = panel.querySelector('.zip-zip-list');
        if (list.dataset.loaded === '1') return;
        list.innerHTML = '<div class="text-secondary p-2">' + i18n.loadingZips + '</div>';
        try {
            const zips = await loadZips(abbr);
            list.dataset.loaded = '1';
            renderZipList(panel, abbr, zips);
        } catch (e) {
            list.innerHTML = '<div class="text-danger p-2">' + i18n.couldNotLoadZips + '</div>';
        }
    }

    function panelAbbrs() {
        if (selectAllUsa) {
            return nationwideAbbrs.slice();
        }
        return Object.keys(selected).sort((a, b) => stateName(a).localeCompare(stateName(b)));
    }

    function renderPanels() {
        if (selectAllUsa && !allUsaDetailsOpen) {
            panelsEl.innerHTML = '';
            updateAllUsaUi();
            return;
        }

        const openStates = new Set(
            [...panelsEl.querySelectorAll('.zip-state-panel.is-open')].map((el) => el.dataset.state)
        );
        const filters = {};
        panelsEl.querySelectorAll('.zip-zip-filter input').forEach((input) => {
            filters[input.dataset.state] = input.value;
        });

        panelsEl.innerHTML = '';
        panelAbbrs().forEach((abbr) => {
            const panel = document.createElement('div');
            panel.className = 'zip-state-panel' + (openStates.has(abbr) ? ' is-open' : '');
            panel.dataset.state = abbr;

            const selectedCount = selectAllUsa ? stateCount(abbr) : (selected[abbr]?.size || 0);
            const head = document.createElement('div');
            head.className = 'zip-state-panel__head';
            head.innerHTML = `
                <span class="zip-chevron"><i class="ti ti-chevron-right"></i></span>
                <div>
                    <div class="fw-bold">${stateName(abbr)} <span class="text-secondary">(${abbr})</span></div>
                    <div class="zip-state-panel__meta">${t(i18n.selectedOfZips, { selected: selectedCount, total: stateCount(abbr) })}</div>
                </div>
            `;

            const actions = document.createElement('div');
            actions.className = 'zip-state-panel__actions';
            actions.innerHTML = abbr === 'OTHER' ? `
                <button type="button" class="btn btn-sm" data-act="clear">${i18n.clear}</button>
                <button type="button" class="btn btn-sm btn-ghost-danger" data-act="remove">${i18n.remove}</button>
            ` : `
                <button type="button" class="btn btn-sm" data-act="select-all">${i18n.selectAll}</button>
                <button type="button" class="btn btn-sm" data-act="clear">${i18n.clear}</button>
                <button type="button" class="btn btn-sm btn-ghost-danger" data-act="remove">${i18n.remove}</button>
            `;
            head.appendChild(actions);

            const body = document.createElement('div');
            body.className = 'zip-state-panel__body';
            body.innerHTML = `
                <div class="zip-zip-filter">
                    <input type="search" class="form-control form-control-sm" data-state="${abbr}" placeholder="${t(i18n.filterZipCity, { state: stateName(abbr) })}">
                </div>
                <div class="zip-zip-list"></div>
            `;

            head.addEventListener('click', (e) => {
                if (e.target.closest('.zip-state-panel__actions')) return;
                if (panel.classList.contains('is-open')) {
                    panel.classList.remove('is-open');
                } else {
                    openPanel(panel, abbr);
                }
            });

            actions.querySelector('[data-act="remove"]').addEventListener('click', (e) => {
                e.stopPropagation();
                removeState(abbr).catch((err) => alert(err.message || i18n.couldNotRemoveState));
            });
            actions.querySelector('[data-act="clear"]').addEventListener('click', async (e) => {
                e.stopPropagation();
                try {
                    if (selectAllUsa) await materializeNationwideSelection();
                    selected[abbr] = new Set();
                    markSelectAllUsa(false);
                    updatePanelMeta(abbr);
                    syncHidden();
                } catch (err) {
                    alert(err.message || i18n.couldNotClearState);
                }
            });
            const selectAllBtn = actions.querySelector('[data-act="select-all"]');
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    try {
                        if (selectAllUsa) await materializeNationwideSelection();
                        await openPanel(panel, abbr);
                        const zips = await loadZips(abbr);
                        selected[abbr] = new Set(zips.map((z) => z.zip));
                        markSelectAllUsa(false);
                        updatePanelMeta(abbr);
                        syncHidden();
                        renderZipList(panel, abbr, zips);
                    } catch (err) {
                        alert(err.message || i18n.couldNotSelectAllZips);
                    }
                });
            }

            const filterInput = body.querySelector('input');
            if (filters[abbr]) filterInput.value = filters[abbr];
            let filterTimer = null;
            filterInput.addEventListener('input', () => {
                clearTimeout(filterTimer);
                filterTimer = setTimeout(async () => {
                    const q = filterInput.value.trim();
                    const zips = q ? await loadZips(abbr, q) : (zipCache[abbr] || await loadZips(abbr));
                    renderZipList(panel, abbr, zips);
                }, 200);
            });

            panel.appendChild(head);
            panel.appendChild(body);
            panelsEl.appendChild(panel);

            if (openStates.has(abbr)) {
                openPanel(panel, abbr);
            }
        });
    }

    async function searchStates(q) {
        const url = statesUrl + (q ? ('?q=' + encodeURIComponent(q)) : '');
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        return data.states || [];
    }

    function hideResults() {
        resultsEl.classList.add('d-none');
        resultsEl.innerHTML = '';
        activeIndex = -1;
    }

    function showResults(states) {
        resultsEl.innerHTML = '';
        if (!states.length) {
            resultsEl.innerHTML = '<div class="list-group-item text-secondary">' + i18n.noStatesFound + '</div>';
            resultsEl.classList.remove('d-none');
            return;
        }
        states.slice(0, 20).forEach((state, idx) => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'list-group-item list-group-item-action';
            item.dataset.index = String(idx);
            item.innerHTML = `<strong>${state.name}</strong> <span class="text-secondary">(${state.abbr})</span> <span class="text-secondary float-end">${t(i18n.zipCodesCount, { count: state.count })}</span>`;
            item.addEventListener('click', () => {
                ensureState(state.abbr).then(() => {
                    searchInput.value = '';
                    hideResults();
                    const panel = panelsEl.querySelector(`[data-state="${state.abbr}"]`);
                    if (panel) openPanel(panel, state.abbr);
                }).catch((err) => alert(err.message || i18n.couldNotAddState));
            });
            resultsEl.appendChild(item);
        });
        resultsEl.classList.remove('d-none');
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        const q = searchInput.value.trim();
        searchTimer = setTimeout(async () => {
            if (q.length === 0) {
                hideResults();
                return;
            }
            showResults(await searchStates(q));
        }, 180);
    });

    searchInput.addEventListener('focus', async () => {
        if (searchInput.value.trim().length) {
            showResults(await searchStates(searchInput.value.trim()));
        }
    });

    document.addEventListener('click', (e) => {
        if (!root.contains(e.target)) hideResults();
    });

    searchInput.addEventListener('keydown', (e) => {
        const items = [...resultsEl.querySelectorAll('.list-group-item-action')];
        if (!items.length) return;
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = Math.min(activeIndex + 1, items.length - 1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = Math.max(activeIndex - 1, 0);
        } else if (e.key === 'Enter' && activeIndex >= 0) {
            e.preventDefault();
            items[activeIndex].click();
            return;
        } else if (e.key === 'Escape') {
            hideResults();
            return;
        } else {
            return;
        }
        items.forEach((el, i) => el.classList.toggle('active', i === activeIndex));
    });

    clearAllBtn.addEventListener('click', () => {
        clearSelectedMaps();
        markSelectAllUsa(false);
        renderPanels();
        syncHidden();
    });

    if (toggleAllUsaDetailsBtn) {
        toggleAllUsaDetailsBtn.addEventListener('click', () => {
            if (!selectAllUsa) return;
            allUsaDetailsOpen = !allUsaDetailsOpen;
            updateAllUsaUi();
            if (allUsaDetailsOpen) {
                renderPanels();
            }
        });
    }

    if (selectAllUsaBtn) {
        selectAllUsaBtn.addEventListener('click', () => {
            if (selectAllUsa) {
                allUsaDetailsOpen = false;
                updateAllUsaUi();
                syncHidden();
                return;
            }
            clearSelectedMaps();
            allUsaDetailsOpen = false;
            markSelectAllUsa(true);
            renderPanels();
            syncHidden();
        });
    }

    form.addEventListener('submit', (e) => {
        syncHidden();
        if (!selectAllUsa && totalSelected() === 0) {
            e.preventDefault();
            alert(i18n.selectAtLeastOneZip);
        }
    });

    if (initialSelectAllUsa || isNationwideSelection()) {
        clearSelectedMaps();
        allUsaDetailsOpen = false;
        markSelectAllUsa(true);
    }

    renderPanels();
    syncHidden();
})();
</script>
@endpush
