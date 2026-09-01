<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WithdrawBalanceService
{
    public const HOLDING_DAYS = 45;

    /** Earnings that must age 45 days before withdrawal. */
    private const VESTING_REMARKS = [
        'interest',
        'referral_commission',
    ];

    /** Credits available without the 45-day hold (still need active plan to withdraw). */
    private const IMMEDIATE_READY_REMARKS = [
        'deposit',
        'balance_add',
        'admin_balance_add',
        'withdraw_reject',
        'invest_reject',
    ];

    public function summaryFor(User $user, ?Carbon $now = null): array
    {
        $now = $now ?? now();
        $cutoff = $now->copy()->subDays(self::HOLDING_DAYS);

        $credits = Transaction::query()
            ->where('user_id', $user->id)
            ->where('trx_type', '+')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get(['id', 'amount', 'remark', 'created_at']);

        $ready = 0.0;
        $pending = 0.0;

        foreach ($credits as $credit) {
            $amount = (float) $credit->amount;
            $remark = (string) $credit->remark;

            if (in_array($remark, self::VESTING_REMARKS, true)) {
                if ($credit->created_at && $credit->created_at->lte($cutoff)) {
                    $ready += $amount;
                } else {
                    $pending += $amount;
                }
                continue;
            }

            if (in_array($remark, self::IMMEDIATE_READY_REMARKS, true)) {
                $ready += $amount;
                continue;
            }

            // Legacy or uncategorized credits count as immediately ready.
            $ready += $amount;
        }

        $withdrawn = (float) Withdrawal::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [Status::PAYMENT_PENDING, Status::PAYMENT_SUCCESS])
            ->sum('amount');

        $ready = max(0, round($ready - $withdrawn, 2));
        $pending = max(0, round($pending, 2));

        $hasActivePlan = Investment::query()
            ->where('user_id', $user->id)
            ->where('status', Status::RUNNING)
            ->exists();

        $withdrawable = $hasActivePlan ? min($ready, (float) $user->balance) : 0.0;
        $withdrawable = max(0, round($withdrawable, 2));

        $nextUnlockAt = $this->nextUnlockDate($credits, $cutoff);

        return [
            'holding_days' => self::HOLDING_DAYS,
            'has_active_plan' => $hasActivePlan,
            'ready_amount' => $ready,
            'pending_amount' => $pending,
            'total_amount' => round($ready + $pending, 2),
            'withdrawable_amount' => $withdrawable,
            'wallet_balance' => round((float) $user->balance, 2),
            'next_unlock_at' => $nextUnlockAt,
        ];
    }

    public function canWithdraw(User $user, float $amount): array
    {
        $summary = $this->summaryFor($user);
        $amount = round($amount, 2);

        if (!$summary['has_active_plan']) {
            return [
                'ok' => false,
                'message' => 'You need at least one active running plan before you can withdraw.',
                'summary' => $summary,
            ];
        }

        if ($amount > $summary['withdrawable_amount']) {
            return [
                'ok' => false,
                'message' => 'Only matured funds over ' . self::HOLDING_DAYS . ' days are ready to withdraw. Pending earnings are still locked.',
                'summary' => $summary,
            ];
        }

        if ($amount > $summary['wallet_balance']) {
            return [
                'ok' => false,
                'message' => 'Insufficient balance for withdrawal.',
                'summary' => $summary,
            ];
        }

        return [
            'ok' => true,
            'message' => null,
            'summary' => $summary,
        ];
    }

    private function nextUnlockDate(Collection $credits, Carbon $cutoff): ?Carbon
    {
        $next = $credits
            ->filter(fn ($credit) => in_array((string) $credit->remark, self::VESTING_REMARKS, true))
            ->filter(fn ($credit) => $credit->created_at && $credit->created_at->gt($cutoff))
            ->sortBy('created_at')
            ->first();

        if (!$next || !$next->created_at) {
            return null;
        }

        return $next->created_at->copy()->addDays(self::HOLDING_DAYS);
    }
}
