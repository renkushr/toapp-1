<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Agent;
use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use App\Models\InsuranceApplication;
use App\Models\Investment;
use App\Models\User;
use App\Models\UserLogin;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AgentReportService
{
    public function periodStart(?int $days = null): ?Carbon
    {
        $days = $days ?? 30;

        if ($days <= 0) {
            return null;
        }

        return now()->subDays($days)->startOfDay();
    }

    public function summaryForAgent(Agent $agent, ?Carbon $since = null): array
    {
        $userIds = User::query()
            ->forAgentTerritory($agent)
            ->pluck('id');

        $clientsTotal = $userIds->count();
        $clientsActive = User::query()
            ->forAgentTerritory($agent)
            ->where('status', Status::USER_ACTIVE)
            ->count();
        $clientsApproved = User::query()
            ->forAgentTerritory($agent)
            ->where('approval_status', 'approved')
            ->count();

        $investmentSalesQuery = Investment::query()
            ->whereIn('user_id', $userIds)
            ->whereIn('status', [Status::RUNNING, Status::COMPLETED]);
        $investmentPendingQuery = Investment::query()
            ->whereIn('user_id', $userIds)
            ->where('status', Status::INVESTMENT_PENDING);

        if ($since) {
            $investmentSalesQuery->where('created_at', '>=', $since);
            $investmentPendingQuery->where('created_at', '>=', $since);
        }

        $insuranceConfirmedQuery = InsuranceApplication::query()
            ->forAgentTerritory($agent)
            ->where('status', InsuranceApplication::STATUS_CONFIRMED);
        $insurancePipelineQuery = InsuranceApplication::query()
            ->forAgentTerritory($agent)
            ->whereNotIn('status', [
                InsuranceApplication::STATUS_CONFIRMED,
                InsuranceApplication::STATUS_REJECTED,
            ]);

        if ($since) {
            $insuranceConfirmedQuery->where('created_at', '>=', $since);
            $insurancePipelineQuery->where('created_at', '>=', $since);
        }

        $loginsQuery = UserLogin::query()->whereIn('user_id', $userIds);
        if ($since) {
            $loginsQuery->where('created_at', '>=', $since);
        }

        $conversationIds = AgentConversation::query()
            ->forAgentTerritory($agent)
            ->pluck('id');

        $chatUserQuery = AgentConversationMessage::query()
            ->whereIn('conversation_id', $conversationIds)
            ->where('sender_type', 'user');
        $chatAgentQuery = AgentConversationMessage::query()
            ->whereIn('conversation_id', $conversationIds)
            ->where('sender_type', 'agent');

        if ($since) {
            $chatUserQuery->where('created_at', '>=', $since);
            $chatAgentQuery->where('created_at', '>=', $since);
        }

        $activeProducts = Investment::query()
            ->whereIn('user_id', $userIds)
            ->where('status', Status::RUNNING)
            ->count();

        $investmentSalesAmount = (float) ((clone $investmentSalesQuery)->sum('amount') ?? 0);
        $insuranceConfirmedAnnual = (float) ((clone $insuranceConfirmedQuery)->sum('quoted_annual') ?? 0);
        $feePercent = (float) $agent->fee_percent;
        $platformFeeAmount = round(($investmentSalesAmount + $insuranceConfirmedAnnual) * $feePercent / 100, 2);

        return [
            'clients_total' => $clientsTotal,
            'clients_active' => $clientsActive,
            'clients_approved' => $clientsApproved,
            'active_products' => $activeProducts,
            'investment_sales_count' => (clone $investmentSalesQuery)->count(),
            'investment_sales_amount' => $investmentSalesAmount,
            'investment_pending_count' => (clone $investmentPendingQuery)->count(),
            'investment_pending_amount' => (float) ((clone $investmentPendingQuery)->sum('amount') ?? 0),
            'insurance_confirmed_count' => (clone $insuranceConfirmedQuery)->count(),
            'insurance_confirmed_annual' => $insuranceConfirmedAnnual,
            'agent_fee_percent' => $feePercent,
            'platform_fee_amount' => $platformFeeAmount,
            'insurance_pipeline_count' => (clone $insurancePipelineQuery)->count(),
            'logins_count' => $loginsQuery->count(),
            'chat_messages_user' => $chatUserQuery->count(),
            'chat_messages_agent' => $chatAgentQuery->count(),
            'conversations_count' => $conversationIds->count(),
        ];
    }

    public function summariesForAllAgents(?Carbon $since = null, ?string $search = null): Collection
    {
        $agents = Agent::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('license_no', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return $agents->map(function (Agent $agent) use ($since) {
            return [
                'agent' => $agent,
                'metrics' => $this->summaryForAgent($agent, $since),
            ];
        });
    }

    public function aggregateTotals(Collection $rows): array
    {
        return [
            'agents' => $rows->count(),
            'clients_total' => $rows->sum(fn ($row) => $row['metrics']['clients_total']),
            'clients_active' => $rows->sum(fn ($row) => $row['metrics']['clients_active']),
            'investment_sales_amount' => $rows->sum(fn ($row) => $row['metrics']['investment_sales_amount']),
            'insurance_confirmed_annual' => $rows->sum(fn ($row) => $row['metrics']['insurance_confirmed_annual']),
            'platform_fee_amount' => $rows->sum(fn ($row) => $row['metrics']['platform_fee_amount']),
            'logins_count' => $rows->sum(fn ($row) => $row['metrics']['logins_count']),
            'chat_messages_user' => $rows->sum(fn ($row) => $row['metrics']['chat_messages_user']),
        ];
    }
}
