@php $isEdit = (bool) $admin; @endphp
<form method="POST" action="{{ $action }}">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ $isEdit ? 'Admin account' : 'New admin' }}</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Name</label>
                    <input class="form-control" name="name" value="{{ old('name', $admin?->name) }}" required maxlength="80">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', $admin?->email) }}" required maxlength="255">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Username</label>
                    <input class="form-control" name="username" value="{{ old('username', $admin?->username) }}" required maxlength="80">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ $isEdit ? 'New password' : 'Password' }}</label>
                    <input class="form-control" type="password" name="password" @unless($isEdit) required @endunless minlength="8">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Role preset</label>
                    <select class="form-select" name="role" required>
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', $admin?->role) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <label class="form-check">
                        <input class="form-check-input" type="checkbox" name="status" value="1" @checked(old('status', $isEdit ? (int) $admin->status === 1 : true))>
                        <span class="form-check-label">Active admin account</span>
                    </label>
                </div>
            </div>
            <div class="portal-form-section">
                <label class="form-label">Extra permissions</label>
                <div class="portal-check-grid">
                    @foreach($permissions as $value => $label)
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $value }}" @checked(in_array($value, old('permissions', $admin?->permissions ?: []), true))>
                            <span class="form-check-label">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Save changes' : 'Create admin' }}</button>
            <a class="btn" href="{{ route('toapp.admin.admins.index') }}">Cancel</a>
        </div>
    </div>
</form>
