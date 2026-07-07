@php $isEdit = (bool) $agent; @endphp
<form method="POST" action="{{ $action }}">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ $isEdit ? 'Agent profile' : 'New agent' }}</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Full name</label>
                    <input class="form-control" name="name" value="{{ old('name', $agent?->name) }}" required maxlength="120">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">License number</label>
                    <input class="form-control" name="license_no" value="{{ old('license_no', $agent?->license_no) }}" required maxlength="80" placeholder="AG-12345">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', $agent?->email) }}" required maxlength="255">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Username</label>
                    <input class="form-control" name="username" value="{{ old('username', $agent?->username) }}" required maxlength="80">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ $isEdit ? 'New password' : 'Password' }}</label>
                    <input class="form-control" type="password" name="password" @unless($isEdit) required @endunless minlength="8" placeholder="{{ $isEdit ? 'Leave blank to keep current' : '' }}">
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <label class="form-check">
                        <input class="form-check-input" type="checkbox" name="status" value="1" @checked(old('status', $isEdit ? (int) $agent->status === 1 : true))>
                        <span class="form-check-label">Active agent account</span>
                    </label>
                </div>
            </div>
            <div class="mb-0">
                <label class="form-label required">ZIP codes (USA)</label>
                <textarea class="form-control" name="zip_codes" rows="4" required placeholder="90210, 90211, 90212">{{ old('zip_codes', $isEdit ? $agent->zipCodeList() : '') }}</textarea>
                <small class="form-hint">Comma, space, or semicolon separated 5-digit ZIP codes.</small>
                @error('zip_codes')<div class="text-danger mt-2">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Save changes' : 'Create agent' }}</button>
            <a class="btn" href="{{ route('toapp.admin.agents.index') }}">Cancel</a>
        </div>
    </div>
</form>
