<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Investment;
use App\Models\Referral;
use App\Models\ReferralCommission;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReferralCommissionService
{
    public function payForInvestment(Investment $investment): int
    {
        $investment->loadMissing(['user', 'plan']);
        $buyer = $investment->user;

        if (!$buyer || !$buyer->ref_by) {
            return 0;
        }

        $rules = $this->rulesForPlan((int) $investment->plan_id);
        if ($rules->isEmpty()) {
            return 0;
        }

        return DB::transaction(function () use ($investment, $buyer, $rules) {
            $paidCount = 0;
            $uplineId = (int) $buyer->ref_by;

            foreach ($rules as $rule) {
                $upline = User::find($uplineId);
                if (!$upline) {
                    break;
                }

                $amount = ((float) $investment->amount * (float) $rule->percent) / 100;
                if ($amount > 0 && !$this->alreadyPaid($investment, $upline, (int) $rule->level)) {
                    $transaction = $this->creditUpline($upline, $buyer, $investment, $amount, (float) $rule->percent, (int) $rule->level);

                    ReferralCommission::create([
                        'earner_user_id' => $upline->id,
                        'source_user_id' => $buyer->id,
                        'plan_id' => $investment->plan_id,
                        'investment_id' => $investment->id,
                        'transaction_id' => $transaction->id,
                        'level' => $rule->level,
                        'base_amount' => $investment->amount,
                        'percent' => $rule->percent,
                        'amount' => $amount,
                        'trx' => $transaction->trx,
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    $paidCount++;
                }

                $uplineId = (int) $upline->ref_by;
                if (!$uplineId) {
                    break;
                }
            }

            return $paidCount;
        });
    }

    private function rulesForPlan(int $planId)
    {
        foreach ([
            ['package_commission', $planId],
            ['package_commission', null],
            ['deposit_commission', $planId],
            ['deposit_commission', null],
        ] as [$type, $rulePlanId]) {
            $rules = Referral::query()
                ->where('status', Status::ENABLE)
                ->where('commission_type', $type)
                ->when($rulePlanId, fn ($query) => $query->where('plan_id', $rulePlanId), fn ($query) => $query->whereNull('plan_id'))
                ->orderBy('level')
                ->get();

            if ($rules->isNotEmpty()) {
                return $rules;
            }
        }

        return collect();
    }

    private function alreadyPaid(Investment $investment, User $upline, int $level): bool
    {
        return ReferralCommission::where('investment_id', $investment->id)
            ->where('earner_user_id', $upline->id)
            ->where('level', $level)
            ->exists();
    }

    private function creditUpline(User $upline, User $buyer, Investment $investment, float $amount, float $percent, int $level): Transaction
    {
        $upline->balance += $amount;
        $upline->save();

        $transaction = new Transaction();
        $transaction->user_id = $upline->id;
        $transaction->amount = $amount;
        $transaction->post_balance = $upline->balance;
        $transaction->charge = 0;
        $transaction->trx_type = '+';
        $transaction->remark = 'referral_commission';
        $transaction->details = sprintf('Level %d referral fee %.2f%% from %s package purchase', $level, $percent, $buyer->username ?: $buyer->email);
        $transaction->trx = getTrx();
        $transaction->save();

        return $transaction;
    }
}
