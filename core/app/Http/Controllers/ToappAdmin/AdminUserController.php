<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Models\Admin;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $admins = Admin::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%' . $request->search . '%';
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', $search)
                        ->orWhere('username', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            })
            ->orderByRaw("role = 'super_admin' desc")
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('toapp_admin.admins.index', [
            'pageTitle' => __('Admin Roles'),
            'admins' => $admins,
        ]);
    }

    public function create()
    {
        return view('toapp_admin.admins.create', [
            'pageTitle' => __('New Admin'),
            'admin' => null,
            'roles' => Admin::roleLabels(),
            'permissions' => Admin::permissionLabels(),
        ]);
    }

    public function edit(Admin $admin)
    {
        return view('toapp_admin.admins.edit', [
            'pageTitle' => __('Edit Admin'),
            'admin' => $admin,
            'roles' => Admin::roleLabels(),
            'permissions' => Admin::permissionLabels(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'permissions' => $this->validPermissions($request),
            'status' => $request->boolean('status'),
        ]);

        AdminAudit::record('admin.created', $admin, [
            'role' => $admin->role,
            'permissions' => $admin->permissions,
            'status' => (bool) $admin->status,
        ]);

        return redirect()
            ->route('toapp.admin.admins.edit', $admin)
            ->with('status', 'Admin account created.');
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $this->validatedData($request, $admin);
        $nextStatus = $request->boolean('status');

        if ($this->wouldRemoveLastActiveSuperAdmin($admin, $data['role'], $nextStatus)) {
            return redirect()
                ->route('toapp.admin.admins.edit', $admin)
                ->withErrors(['admin' => 'Keep at least one active Super Admin account.']);
        }

        $admin->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'role' => $data['role'],
            'permissions' => $this->validPermissions($request),
            'status' => $nextStatus,
        ]);

        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        AdminAudit::record('admin.updated', $admin, [
            'role' => $admin->role,
            'permissions' => $admin->permissions,
            'status' => (bool) $admin->status,
            'password_changed' => !empty($data['password']),
        ]);

        return redirect()
            ->route('toapp.admin.admins.edit', $admin)
            ->with('status', 'Admin account updated.');
    }

    private function validatedData(Request $request, ?Admin $admin = null): array
    {
        $adminId = $admin?->id;

        return $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($adminId)],
            'username' => ['required', 'string', 'alpha_dash', 'max:80', Rule::unique('admins', 'username')->ignore($adminId)],
            'password' => [$admin ? 'nullable' : 'required', 'string', 'min:8', 'max:128'],
            'role' => ['required', Rule::in(array_keys(Admin::roleLabels()))],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [Rule::in(array_keys(Admin::permissionLabels()))],
            'status' => ['nullable', 'boolean'],
        ]);
    }

    private function validPermissions(Request $request): array
    {
        return array_values(array_intersect(
            (array) $request->input('permissions', []),
            array_keys(Admin::permissionLabels())
        ));
    }

    private function wouldRemoveLastActiveSuperAdmin(Admin $admin, string $nextRole, bool $nextStatus): bool
    {
        if ($admin->role !== Admin::ROLE_SUPER_ADMIN) {
            return false;
        }

        if ($nextRole === Admin::ROLE_SUPER_ADMIN && $nextStatus) {
            return false;
        }

        return !Admin::query()
            ->where('id', '!=', $admin->id)
            ->where('role', Admin::ROLE_SUPER_ADMIN)
            ->where('status', true)
            ->exists();
    }
}
