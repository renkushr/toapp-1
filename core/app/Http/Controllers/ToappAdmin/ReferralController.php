<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Models\Plan;
use App\Models\Referral;
use App\Models\ReferralCommission;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $rules = Referral::with('plan')
            ->whereIn('commission_type', ['package_commission', 'deposit_commission'])
            ->when($request->filled('plan_id'), function ($query) use ($request) {
                $request->plan_id === 'global'
                    ? $query->whereNull('plan_id')
                    : $query->where('plan_id', $request->integer('plan_id'));
            })
            ->orderByRaw('plan_id is not null')
            ->orderBy('plan_id')
            ->orderBy('level')
            ->get();

        $commissionQuery = $this->commissionQuery($request);

        $stats = [
            'total_paid' => (clone $commissionQuery)->sum('amount'),
            'commission_count' => (clone $commissionQuery)->count(),
            'earner_count' => (clone $commissionQuery)->distinct('earner_user_id')->count('earner_user_id'),
            'source_count' => (clone $commissionQuery)->distinct('source_user_id')->count('source_user_id'),
        ];

        $commissions = $commissionQuery
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('toapp_admin.referrals.index', [
            'pageTitle' => __('Referral System'),
            'plans' => Plan::orderBy('name')->get(),
            'rules' => $rules,
            'commissions' => $commissions,
            'stats' => $stats,
        ]);
    }

    public function export(Request $request)
    {
        $filename = 'referral-commissions-' . now()->format('Ymd-His') . '.csv';
        $commissions = $this->commissionQuery($request)->latest()->get();

        return response()->streamDownload(function () use ($commissions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['TRX', 'Earner', 'Earner Email', 'Source Member', 'Source Email', 'Package', 'Level', 'Percent', 'Base Amount', 'Referral Fee Amount', 'Paid At']);

            foreach ($commissions as $commission) {
                fputcsv($handle, [
                    $commission->trx,
                    optional($commission->earner)->username,
                    optional($commission->earner)->email,
                    optional($commission->sourceUser)->username,
                    optional($commission->sourceUser)->email,
                    optional($commission->plan)->name,
                    $commission->level,
                    $commission->percent,
                    $commission->base_amount,
                    $commission->amount,
                    optional($commission->paid_at ?? $commission->created_at)->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function createRule()
    {
        return view('toapp_admin.referrals.create_rule', [
            'pageTitle' => __('New Referral Rule'),
            'plans' => Plan::orderBy('name')->get(),
        ]);
    }

    public function storeRule(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => ['nullable', 'integer', 'exists:plans,id'],
            'level' => ['required', 'integer', 'min:1', 'max:20'],
            'percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['nullable', 'boolean'],
        ]);

        $rule = Referral::updateOrCreate(
            [
                'commission_type' => 'package_commission',
                'plan_id' => $validated['plan_id'] ?? null,
                'level' => $validated['level'],
            ],
            [
                'percent' => $validated['percent'],
                'status' => (int) ($validated['status'] ?? Status::DISABLE),
            ]
        );

        AdminAudit::record('referral.rule_saved', $rule, [
            'plan_id' => $rule->plan_id,
            'level' => $rule->level,
            'percent' => $rule->percent,
            'status' => $rule->status,
        ]);

        return redirect()
            ->route('toapp.admin.referrals.index')
            ->with('status', 'Referral rule saved successfully.');
    }

    public function destroyRule(Referral $referral)
    {
        AdminAudit::record('referral.rule_deleted', $referral, [
            'plan_id' => $referral->plan_id,
            'level' => $referral->level,
            'percent' => $referral->percent,
        ]);

        $referral->delete();

        return back()->with('status', 'Referral rule deleted.');
    }

    protected function commissionQuery(Request $request)
    {
        return ReferralCommission::with(['earner', 'sourceUser', 'plan'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('trx', 'like', "%{$search}%")
                        ->orWhereHas('earner', fn ($user) => $user->where('username', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('sourceUser', fn ($user) => $user->where('username', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('level'), fn ($query) => $query->where('level', $request->integer('level')))
            ->when($request->filled('plan_id'), function ($query) use ($request) {
                $request->plan_id === 'global'
                    ? $query->whereNull('plan_id')
                    : $query->where('plan_id', $request->integer('plan_id'));
            });
    }
}
