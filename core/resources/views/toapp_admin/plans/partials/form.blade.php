@php
    $portals = $portals ?? \App\Models\ThirdPartyPortal::where('status', true)->get();
    $publishedIds = isset($plan) && $plan ? $plan?->thirdPartyPortals->pluck('id')->all() : [];
    $isEdit = (bool) $plan;
@endphp

<form method="POST" action="{{ $action }}">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="row row-cards">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Basic information</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Product name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $plan?->name ?? '') }}" required maxlength="255">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" name="category" value="{{ old('category', $plan?->category ?? '') }}" maxlength="250" placeholder="Term Life, Whole Life, IUL...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Short description</label>
                            <input type="text" class="form-control" name="descript" value="{{ old('descript', $plan?->descript ?? '') }}" maxlength="250">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Min amount</label>
                            <input type="number" class="form-control" name="min_amount" step="0.01" min="0.01" value="{{ old('min_amount', $plan?->min_amount ?? 100) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Max amount</label>
                            <input type="number" class="form-control" name="max_amount" step="0.01" min="0.01" value="{{ old('max_amount', $plan?->max_amount ?? 10000) }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Interest type</label>
                            <select class="form-select" name="interest_type">
                                <option value="1" @selected(old('interest_type', $plan?->interest_type ?? 1) == 1)>Percent</option>
                                <option value="2" @selected(old('interest_type', $plan?->interest_type ?? 1) == 2)>Fixed</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Interest</label>
                            <input type="number" class="form-control" name="interest" step="0.01" min="0.01" value="{{ old('interest', $plan?->interest ?? 1) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Total return</label>
                            <input type="number" class="form-control" name="total_return" min="1" value="{{ old('total_return', $plan?->total_return ?? 1) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header portal-card-header-stacked">
                    <h3 class="card-title">Eligibility rules</h3>
                    <p class="card-subtitle text-secondary mb-0">Used when matching third-party applicants</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Age from</label>
                            <input type="number" class="form-control" name="agefrom" min="18" value="{{ old('agefrom', $plan?->agefrom ?? 18) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Age to</label>
                            <input type="number" class="form-control" name="ageto" min="18" value="{{ old('ageto', $plan?->ageto ?? 99) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="sex">
                                <option value="0" @selected(old('sex', $plan?->sex ?? 0) == 0)>Any</option>
                                <option value="1" @selected(old('sex', $plan?->sex ?? 0) == 1)>Male only</option>
                                <option value="2" @selected(old('sex', $plan?->sex ?? 0) == 2)>Female only</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Health</label>
                            <select class="form-select" name="health">
                                <option value="0" @selected(old('health', $plan?->health ?? 0) == 0)>Any</option>
                                <option value="1" @selected(old('health', $plan?->health ?? 0) == 1)>Good health</option>
                                <option value="2" @selected(old('health', $plan?->health ?? 0) == 2)>Fair health</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Smoking</label>
                            <select class="form-select" name="nicotin">
                                <option value="2" @selected(old('nicotin', $plan?->nicotin ?? 2) == 2)>Any</option>
                                <option value="0" @selected(old('nicotin', $plan?->nicotin ?? 2) == 0)>Non-smoker</option>
                                <option value="1" @selected(old('nicotin', $plan?->nicotin ?? 2) == 1)>Smoker</option>
                            </select>
                        </div>
                    </div>
                    <div class="portal-check-grid">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" name="allows_chronic" value="1" @checked(old('allows_chronic', $plan?->allows_chronic ?? true))>
                            <span class="form-check-label">Allow applicants with chronic conditions</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header portal-card-header-stacked">
                    <h3 class="card-title">Price ranges</h3>
                    <p class="card-subtitle text-secondary mb-0">8 tiers for monthly and annual quotes</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Range</th>
                                <th>Monthly</th>
                                <th>Annual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 8; $i++)
                                <tr>
                                    <td class="text-secondary">Tier {{ $i }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" name="monthprice{{ $i }}" step="0.01" min="0" value="{{ old('monthprice'.$i, $plan?->{'monthprice'.$i} ?? 0) }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" name="annualprice{{ $i }}" step="0.01" min="0" value="{{ old('annualprice'.$i, $plan?->{'annualprice'.$i} ?? 0) }}">
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Terms & conditions</h3>
                </div>
                <div class="card-body">
                    <label class="form-label">Terms HTML</label>
                    <textarea class="form-control" name="term" rows="6" placeholder="Policy terms shown before applicant confirms">{{ old('term', $plan?->term ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3 position-sticky" style="top: 1rem;">
                <div class="card-header">
                    <h3 class="card-title">Publish</h3>
                </div>
                <div class="card-body">
                    <p class="text-secondary mb-3">Select third-party portals where this product should appear.</p>
                    <div class="portal-check-grid">
                    @forelse($portals as $portal)
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" name="portals[]" value="{{ $portal->id }}" @checked(in_array($portal->id, old('portals', $publishedIds)))>
                            <span class="form-check-label">
                                <strong>{{ $portal->name }}</strong>
                                <span class="d-block text-secondary">{{ $portal->base_path }}</span>
                            </span>
                        </label>
                    @empty
                        <p class="text-secondary mb-0">No third-party portals configured.</p>
                    @endforelse
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary w-100" type="submit">
                        {{ $isEdit ? 'Save changes' : 'Create product' }}
                    </button>
                    @if($isEdit)
                        <a href="{{ route('toapp.admin.plans.index') }}" class="btn w-100 mt-2">Cancel</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
