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

    /**
     * @return array{since:?Carbon,until:?Carbon,mode:string,year:?int,days:int,label:string}
     */
    public function resolvePeriod(?int $days = 30, ?int $year = null): array
    {
        $currentYear = (int) now()->year;

        if ($year !== null && $year >= 2000 && $year <= ($currentYear + 1)) {
            $since = Carbon::create($year, 1, 1)->startOfDay();
            $until = $year === $currentYear
                ? now()->endOfDay()
                : Carbon::create($year, 12, 31)->endOfDay();

            return [
                'since' => $since,
                'until' => $until,
                'mode' => 'year',
                'year' => $year,
                'days' => 0,
                'label' => (string) $year,
            ];
        }

        $days = max(0, (int) ($days ?? 30));
        $since = $this->periodStart($days);

        return [
            'since' => $since,
            'until' => null,
            'mode' => $days > 0 ? 'days' : 'all',
            'year' => null,
            'days' => $days,
            'label' => $days > 0 ? 'Last ' . $days . ' days' : 'All time',
        ];
    }

    /**
     * @return list<int>
     */
    public function availableYears(?int $earliest = null): array
    {
        $current = (int) now()->year;
        $start = $earliest ?: max(2024, $current - 4);
        $years = [];
        for ($y = $current; $y >= $start; $y--) {
            $years[] = $y;
        }

        return $years;
    }

    public function summaryForAgent(Agent $agent, ?Carbon $since = null, ?Carbon $until = null): array
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

        $this->applyCreatedAtWindow($investmentSalesQuery, $since, $until);
        $this->applyCreatedAtWindow($investmentPendingQuery, $since, $until);

        $insuranceConfirmedQuery = InsuranceApplication::query()
            ->forAgentTerritory($agent)
            ->where('status', InsuranceApplication::STATUS_CONFIRMED);
        $insurancePipelineQuery = InsuranceApplication::query()
            ->forAgentTerritory($agent)
            ->whereNotIn('status', [
                InsuranceApplication::STATUS_CONFIRMED,
                InsuranceApplication::STATUS_REJECTED,
            ]);

        $this->applyCreatedAtWindow($insuranceConfirmedQuery, $since, $until);
        $this->applyCreatedAtWindow($insurancePipelineQuery, $since, $until);

        $loginsQuery = UserLogin::query()->whereIn('user_id', $userIds);
        $this->applyCreatedAtWindow($loginsQuery, $since, $until);

        $conversationIds = AgentConversation::query()
            ->forAgentTerritory($agent)
            ->pluck('id');

        $chatUserQuery = AgentConversationMessage::query()
            ->whereIn('conversation_id', $conversationIds)
            ->where('sender_type', 'user');
        $chatAgentQuery = AgentConversationMessage::query()
            ->whereIn('conversation_id', $conversationIds)
            ->where('sender_type', 'agent');

        $this->applyCreatedAtWindow($chatUserQuery, $since, $until);
        $this->applyCreatedAtWindow($chatAgentQuery, $since, $until);

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

    protected function applyCreatedAtWindow($query, ?Carbon $since, ?Carbon $until): void
    {
        if ($since) {
            $query->where('created_at', '>=', $since);
        }
        if ($until) {
            $query->where('created_at', '<=', $until);
        }
    }

    /**
     * Chart-ready series for the agent performance report.
     *
     * @param  array{since:?Carbon,until:?Carbon,mode:string,year:?int,days:int}|null  $period
     * @return array{
     *   trend: array{labels:list<string>,logins:list<int>,client_messages:list<int>,insurance_confirmed:list<int>,investment_deals:list<int>},
     *   insurance_status: array{labels:list<string>,values:list<int>},
     *   sales_mix: array{labels:list<string>,values:list<float>},
     *   clients: array{labels:list<string>,values:list<int>},
     *   trend_label: string
     * }
     */
    public function chartsForAgent(Agent $agent, ?Carbon $since = null, int $days = 30, ?array $period = null, ?Carbon $until = null): array
    {
        $userIds = User::query()
            ->forAgentTerritory($agent)
            ->pluck('id');

        $mode = $period['mode'] ?? ($days > 0 ? 'days' : 'all');
        $until = $until ?? ($period['until'] ?? null);
        $since = $since ?? ($period['since'] ?? null);

        if ($mode === 'year' && ($period['year'] ?? null)) {
            $year = (int) $period['year'];
            $trendStart = Carbon::create($year, 1, 1)->startOfDay();
            $trendEnd = $until ?: Carbon::create($year, 12, 31)->endOfDay();
            $bucketKeys = [];
            $labels = [];
            for ($month = 1; $month <= 12; $month++) {
                $key = sprintf('%04d-%02d', $year, $month);
                $bucketKeys[] = $key;
                $labels[] = Carbon::create($year, $month, 1)->format('M');
            }
            $groupExpr = "DATE_FORMAT(created_at, '%Y-%m')";
            $insuranceGroupExpr = "DATE_FORMAT(COALESCE(payment_confirmed_at, created_at), '%Y-%m')";
            $trendLabel = (string) $year . ' (monthly)';
        } else {
            $trendDays = $days > 0 ? min($days, 90) : 30;
            $trendStart = now()->subDays($trendDays - 1)->startOfDay();
            $trendEnd = now()->endOfDay();
            $bucketKeys = [];
            $labels = [];
            for ($i = 0; $i < $trendDays; $i++) {
                $day = $trendStart->copy()->addDays($i);
                $key = $day->format('Y-m-d');
                $bucketKeys[] = $key;
                $labels[] = $day->format($trendDays > 45 ? 'M j' : 'D j');
            }
            $groupExpr = 'DATE(created_at)';
            $insuranceGroupExpr = 'DATE(COALESCE(payment_confirmed_at, created_at))';
            $trendLabel = $days > 0 ? 'Last ' . min($days, 90) . ' days' : 'Last 30 days (trend window)';
        }

        $fill = fn (Collection $rows) => collect($bucketKeys)
            ->map(fn ($key) => (int) ($rows[$key] ?? 0))
            ->values()
            ->all();

        $logins = UserLogin::query()
            ->whereIn('user_id', $userIds)
            ->where('created_at', '>=', $trendStart)
            ->where('created_at', '<=', $trendEnd)
            ->selectRaw("{$groupExpr} as d, COUNT(*) as c")
            ->groupByRaw($groupExpr)
            ->pluck('c', 'd');

        $conversationIds = AgentConversation::query()
            ->forAgentTerritory($agent)
            ->pluck('id');

        $clientMessages = AgentConversationMessage::query()
            ->whereIn('conversation_id', $conversationIds)
            ->where('sender_type', 'user')
            ->where('created_at', '>=', $trendStart)
            ->where('created_at', '<=', $trendEnd)
            ->selectRaw("{$groupExpr} as d, COUNT(*) as c")
            ->groupByRaw($groupExpr)
            ->pluck('c', 'd');

        $insuranceConfirmed = InsuranceApplication::query()
            ->forAgentTerritory($agent)
            ->where('status', InsuranceApplication::STATUS_CONFIRMED)
            ->where(function ($query) use ($trendStart, $trendEnd) {
                $query->where(function ($q) use ($trendStart, $trendEnd) {
                    $q->where('payment_confirmed_at', '>=', $trendStart)
                        ->where('payment_confirmed_at', '<=', $trendEnd);
                })->orWhere(function ($inner) use ($trendStart, $trendEnd) {
                    $inner->whereNull('payment_confirmed_at')
                        ->where('created_at', '>=', $trendStart)
                        ->where('created_at', '<=', $trendEnd);
                });
            })
            ->selectRaw("{$insuranceGroupExpr} as d, COUNT(*) as c")
            ->groupByRaw($insuranceGroupExpr)
            ->pluck('c', 'd');

        $investmentDeals = Investment::query()
            ->whereIn('user_id', $userIds)
            ->whereIn('status', [Status::RUNNING, Status::COMPLETED])
            ->where('created_at', '>=', $trendStart)
            ->where('created_at', '<=', $trendEnd)
            ->selectRaw("{$groupExpr} as d, COUNT(*) as c")
            ->groupByRaw($groupExpr)
            ->pluck('c', 'd');

        $statusCountsQuery = InsuranceApplication::query()
            ->forAgentTerritory($agent);
        $this->applyCreatedAtWindow($statusCountsQuery, $since, $until);
        $statusCounts = $statusCountsQuery
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $statusOrder = [
            InsuranceApplication::STATUS_CHATTING => 'Chatting',
            InsuranceApplication::STATUS_PAYMENT_PENDING => 'Awaiting payment',
            InsuranceApplication::STATUS_PLAN_SELECTED => 'Plan selected',
            InsuranceApplication::STATUS_TERMS_ACCEPTED => 'Terms accepted',
            InsuranceApplication::STATUS_QUOTED => 'Quoted',
            InsuranceApplication::STATUS_CONFIRMED => 'Confirmed',
            InsuranceApplication::STATUS_REJECTED => 'Rejected',
        ];

        $statusLabels = [];
        $statusValues = [];
        foreach ($statusOrder as $status => $label) {
            $count = (int) ($statusCounts[$status] ?? 0);
            if ($count <= 0) {
                continue;
            }
            $statusLabels[] = $label;
            $statusValues[] = $count;
        }

        foreach ($statusCounts as $status => $count) {
            if (isset($statusOrder[$status]) || (int) $count <= 0) {
                continue;
            }
            $statusLabels[] = ucfirst(str_replace('_', ' ', (string) $status));
            $statusValues[] = (int) $count;
        }

        $investmentSalesQuery = Investment::query()
            ->whereIn('user_id', $userIds)
            ->whereIn('status', [Status::RUNNING, Status::COMPLETED]);
        $insuranceConfirmedQuery = InsuranceApplication::query()
            ->forAgentTerritory($agent)
            ->where('status', InsuranceApplication::STATUS_CONFIRMED);

        $this->applyCreatedAtWindow($investmentSalesQuery, $since, $until);
        $this->applyCreatedAtWindow($insuranceConfirmedQuery, $since, $until);

        $clientsApproved = User::query()
            ->forAgentTerritory($agent)
            ->where('approval_status', 'approved')
            ->count();
        $clientsPending = User::query()
            ->forAgentTerritory($agent)
            ->where('approval_status', 'pending')
            ->count();
        $clientsRejected = User::query()
            ->forAgentTerritory($agent)
            ->where('approval_status', 'rejected')
            ->count();
        $clientsOther = max(0, $userIds->count() - $clientsApproved - $clientsPending - $clientsRejected);

        return [
            'trend' => [
                'labels' => $labels,
                'logins' => $fill($logins),
                'client_messages' => $fill($clientMessages),
                'insurance_confirmed' => $fill($insuranceConfirmed),
                'investment_deals' => $fill($investmentDeals),
            ],
            'trend_label' => $trendLabel,
            'insurance_status' => [
                'labels' => $statusLabels,
                'values' => $statusValues,
            ],
            'sales_mix' => [
                'labels' => ['Investment sales', 'Insurance (annual)'],
                'values' => [
                    round((float) ($investmentSalesQuery->sum('amount') ?? 0), 2),
                    round((float) ($insuranceConfirmedQuery->sum('quoted_annual') ?? 0), 2),
                ],
            ],
            'clients' => [
                'labels' => ['Approved', 'Pending', 'Rejected', 'Other'],
                'values' => [$clientsApproved, $clientsPending, $clientsRejected, $clientsOther],
            ],
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
