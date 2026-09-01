<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guarded = ['id'];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_OPERATIONS = 'operations';
    public const ROLE_FINANCE = 'finance';
    public const ROLE_SUPPORT = 'support';
    public const ROLE_VIEWER = 'viewer';

    public static function roleLabels(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_OPERATIONS => 'Operations',
            self::ROLE_FINANCE => 'Finance',
            self::ROLE_SUPPORT => 'Support',
            self::ROLE_VIEWER => 'Viewer',
        ];
    }

    public static function permissionLabels(): array
    {
        return [
            'dashboard' => 'Dashboard overview',
            'admins' => 'Admin roles and staff',
            'users' => 'Member approval and account status',
            'balances' => 'Manual member balance adjustments',
            'plans' => 'Packages and plan settings',
            'deposits' => 'Manual deposit review',
            'withdrawals' => 'Manual withdrawal review',
            'reports' => 'Reports and CSV exports',
            'investments' => 'Package purchase approval',
            'referrals' => 'Referral percent rules and ledger',
            'agents' => 'Insurance agents and ZIP code coverage',
            'support' => 'Support tickets',
            'methods' => 'Deposit and withdrawal methods',
            'settings' => 'System settings',
            'readiness' => 'Launch readiness checklist',
        ];
    }

    public static function rolePermissions(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => array_keys(self::permissionLabels()),
            self::ROLE_OPERATIONS => ['dashboard', 'users', 'plans', 'reports', 'referrals', 'agents', 'support', 'readiness'],
            self::ROLE_FINANCE => ['dashboard', 'balances', 'deposits', 'withdrawals', 'reports', 'investments', 'referrals', 'readiness'],
            self::ROLE_SUPPORT => ['dashboard', 'users', 'support', 'readiness'],
            self::ROLE_VIEWER => ['dashboard', 'reports', 'readiness'],
        ];
    }

    public function roleLabel(): string
    {
        return self::roleLabels()[$this->role ?: self::ROLE_SUPER_ADMIN] ?? 'Custom Admin';
    }

    public function canAccess(string $permission): bool
    {
        if (($this->role ?: self::ROLE_SUPER_ADMIN) === self::ROLE_SUPER_ADMIN) {
            return true;
        }

        $allowed = array_unique(array_merge(
            self::rolePermissions()[$this->role] ?? [],
            is_array($this->permissions) ? $this->permissions : []
        ));

        return in_array($permission, $allowed, true);
    }

    public function hasAnyAccess(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->canAccess($permission)) {
                return true;
            }
        }

        return false;
    }
}
