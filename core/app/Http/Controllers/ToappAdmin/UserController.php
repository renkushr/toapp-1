<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Models\Agent;
use App\Models\Deposit;
use App\Models\InsuranceApplication;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ReferralCommission;
use App\Models\Withdrawal;
use App\Support\AdminAudit;
use App\Support\UsZip;
use App\Support\UsZipCatalog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = $this->userQuery($request)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('toapp_admin.users.index', [
            'pageTitle' => __('Users'),
            'users' => $users,
            'unassignedCount' => User::withoutAgent()->count(),
            'stateTabs' => $this->stateTabs(),
            'selectedState' => strtoupper(trim((string) $request->input('state', ''))),
            'states' => UsZipCatalog::states(),
        ]);
    }

    public function export(Request $request)
    {
        $users = $this->userQuery($request)->latest()->get();

        return response()->streamDownload(function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Username', 'Name', 'Email', 'Mobile', 'ZIP code', 'State', 'Balance', 'Status', 'Approval', 'KYC', 'Referrer ID', 'Referral Code', 'Joined']);

            foreach ($users as $user) {
                $zip = UsZip::normalize((string) $user->zipcode) ?: (string) $user->zipcode;
                $state = $zip ? (UsZipCatalog::stateForZip($zip) ?: 'OTHER') : '';
                fputcsv($handle, [
                    $user->username,
                    trim($user->firstname . ' ' . $user->lastname),
                    $user->email,
                    $user->dial_code . $user->mobile,
                    $zip,
                    $state ? UsZipCatalog::stateName($state) : '',
                    $user->balance,
                    $user->status ? 'Active' : 'Banned',
                    $user->approval_status ?? 'approved',
                    [0 => 'Unverified', 1 => 'Verified', 2 => 'Pending'][$user->kv] ?? $user->kv,
                    $user->ref_by,
                    $user->refno,
                    optional($user->created_at)->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, 'users-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv']);
    }

    protected function userQuery(Request $request)
    {
        return User::query()
            ->with('agent')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('zipcode', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->integer('status')))
            ->when($request->filled('approval'), fn ($query) => $query->where('approval_status', $request->approval))
            ->when($request->filled('kyc'), fn ($query) => $query->where('kv', $request->integer('kyc')))
            ->when($request->agent === 'unassigned', fn ($query) => $query->withoutAgent())
            ->when($request->filled('state'), function ($query) use ($request) {
                $this->applyStateFilter($query, strtoupper(trim((string) $request->input('state'))));
            });
    }

    protected function applyStateFilter($query, string $state): void
    {
        if ($state === '' || $state === 'ALL') {
            return;
        }

        if ($state === 'OTHER') {
            $knownZips = [];
            foreach (User::query()
                ->whereNotNull('zipcode')
                ->where('zipcode', '!=', '')
                ->distinct()
                ->pluck('zipcode') as $zip) {
                $normalized = UsZip::normalize((string) $zip);
                if ($normalized && !UsZipCatalog::stateForZip($normalized)) {
                    $knownZips[$normalized] = $normalized;
                    $knownZips[(string) $zip] = (string) $zip;
                }
            }

            if ($knownZips === []) {
                $query->whereRaw('0 = 1');
                return;
            }

            $query->whereIn('zipcode', array_values($knownZips));
            return;
        }

        $zips = UsZipCatalog::zipCodesForState($state);
        if ($zips === []) {
            $query->whereRaw('0 = 1');
            return;
        }

        $query->where(function ($inner) use ($zips) {
            foreach (array_chunk($zips, 800) as $chunk) {
                $inner->orWhereIn('zipcode', $chunk);
            }
        });
    }

    /**
     * @return list<array{abbr:string,name:string,count:int}>
     */
    protected function stateTabs(): array
    {
        $zipCounts = User::query()
            ->whereNotNull('zipcode')
            ->where('zipcode', '!=', '')
            ->selectRaw('zipcode, COUNT(*) as total')
            ->groupBy('zipcode')
            ->pluck('total', 'zipcode');

        $byState = [];
        $unknown = 0;

        foreach ($zipCounts as $zip => $count) {
            $abbr = UsZipCatalog::stateForZip((string) $zip);
            if (!$abbr) {
                $unknown += (int) $count;
                continue;
            }
            $byState[$abbr] = ($byState[$abbr] ?? 0) + (int) $count;
        }

        $tabs = [];
        foreach ($byState as $abbr => $count) {
            $tabs[] = [
                'abbr' => $abbr,
                'name' => UsZipCatalog::stateName($abbr),
                'count' => $count,
            ];
        }

        usort($tabs, fn ($a, $b) => strcmp($a['name'], $b['name']));

        if ($unknown > 0) {
            $tabs[] = [
                'abbr' => 'OTHER',
                'name' => 'Other / unknown',
                'count' => $unknown,
            ];
        }

        return $tabs;
    }

    public function show(User $user)
    {
        $user->load('agent');

        return view('toapp_admin.users.show', [
            'pageTitle' => __('User Detail'),
            'user' => $user,
            'agents' => Agent::where('status', true)->orderBy('name')->get(),
            'totalDeposit' => Deposit::where('user_id', $user->id)->successful()->sum('amount'),
            'totalWithdrawals' => Withdrawal::where('user_id', $user->id)->approved()->sum('amount'),
            'directReferralCount' => User::where('ref_by', $user->id)->count(),
            'referrer' => $user->referrer,
            'totalReferralCommission' => ReferralCommission::where('earner_user_id', $user->id)->sum('amount'),
            'transactions' => Transaction::where('user_id', $user->id)->latest()->take(10)->get(),
            'applications' => InsuranceApplication::with(['plan', 'agent'])->where('user_id', $user->id)->latest('id')->get(),
        ]);
    }

    public function approve(User $user)
    {
        $user->approval_status = 'approved';
        $user->approved_at = now();
        $user->approved_by = auth('admin')->id();
        $user->rejected_at = null;
        $user->rejection_reason = null;
        $user->status = Status::USER_ACTIVE;
        $user->save();

        AdminAudit::record('member.approved', $user, [
            'username' => $user->username,
            'email' => $user->email,
        ]);

        return back()->with('status', 'Member approved successfully.');
    }

    public function reject(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user->approval_status = 'rejected';
        $user->rejected_at = now();
        $user->rejection_reason = $validated['reason'];
        $user->save();

        AdminAudit::record('member.rejected', $user, [
            'username' => $user->username,
            'email' => $user->email,
            'reason' => $validated['reason'],
        ]);

        return back()->with('status', 'Member rejected successfully.');
    }

    public function status(Request $request, User $user)
    {
        if ($user->status == Status::USER_ACTIVE) {
            $validated = $request->validate([
                'reason' => ['required', 'string', 'max:255'],
            ]);

            $user->status = Status::USER_BAN;
            $user->ban_reason = $validated['reason'];
            $message = 'User banned successfully.';
        } else {
            $user->status = Status::USER_ACTIVE;
            $user->ban_reason = null;
            $message = 'User restored successfully.';
        }

        $user->save();

        AdminAudit::record($user->status == Status::USER_ACTIVE ? 'member.restored' : 'member.banned', $user, [
            'username' => $user->username,
            'reason' => $validated['reason'] ?? null,
        ]);

        return back()->with('status', $message);
    }

    public function verification(Request $request, User $user)
    {
        $validated = $request->validate([
            'ev' => ['nullable', 'boolean'],
            'sv' => ['nullable', 'boolean'],
            'kv' => ['nullable', 'integer', 'in:0,1,2'],
        ]);

        $user->ev = (int) ($validated['ev'] ?? 0);
        $user->sv = (int) ($validated['sv'] ?? 0);
        $user->kv = (int) ($validated['kv'] ?? Status::KYC_UNVERIFIED);
        $user->save();

        AdminAudit::record('member.verification_updated', $user, $validated);

        return back()->with('status', 'Verification flags updated.');
    }

    public function balance(Request $request, User $user)
    {
        $validated = $request->validate([
            'act' => ['required', 'in:add,sub'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'remark' => ['required', 'string', 'max:255'],
        ]);

        $amount = (float) $validated['amount'];

        if ($validated['act'] === 'sub' && $amount > $user->balance) {
            return back()->withErrors(['amount' => 'This user does not have enough balance.']);
        }

        DB::transaction(function () use ($user, $amount, $validated) {
            $user->balance = $validated['act'] === 'add'
                ? $user->balance + $amount
                : $user->balance - $amount;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = $validated['act'] === 'add' ? '+' : '-';
            $transaction->remark = $validated['act'] === 'add' ? 'admin_balance_add' : 'admin_balance_subtract';
            $transaction->details = $validated['remark'];
            $transaction->trx = getTrx();
            $transaction->save();
        });

        AdminAudit::record('member.balance_updated', $user, [
            'action' => $validated['act'],
            'amount' => $amount,
            'remark' => $validated['remark'],
        ]);

        return back()->with('status', 'Balance updated and ledger entry created.');
    }

    public function assignAgent(Request $request, User $user)
    {
        $validated = $request->validate([
            'agent_id' => ['required', 'exists:agents,id'],
            'zipcode' => ['nullable', 'regex:/^\d{5}(-\d{4})?$/'],
        ]);

        $agent = Agent::where('id', $validated['agent_id'])->where('status', true)->first();

        if (!$agent) {
            return back()->withErrors(['agent_id' => 'Selected agent is not active.']);
        }

        $zip = UsZip::normalize($validated['zipcode'] ?? $user->zipcode);

        if (!$zip) {
            return back()->withErrors(['zipcode' => 'Enter a valid US ZIP code for this member.']);
        }

        if (!$agent->coversZip($zip)) {
            return back()->withErrors([
                'agent_id' => "Agent {$agent->license_no} does not cover ZIP code {$zip}.",
            ]);
        }

        $previousAgentId = $user->agent_id;

        $user->agent_id = $agent->id;
        $user->agent_license_no = $agent->license_no;
        $user->zipcode = $zip;
        $user->save();

        AdminAudit::record('member.agent_assigned', $user, [
            'previous_agent_id' => $previousAgentId,
            'agent_id' => $agent->id,
            'license_no' => $agent->license_no,
            'zipcode' => $zip,
        ]);

        return back()->with('status', "Agent {$agent->name} ({$agent->license_no}) assigned for ZIP code {$zip}.");
    }
}
