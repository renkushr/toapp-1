<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Plan;
use App\Models\ThirdPartyPortal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PlanMatchingService
{
    public const PRODUCT_CATEGORIES = ['Term Life', 'IUL'];

    public function productTypesForPortal(ThirdPartyPortal $portal): array
    {
        $types = [];

        foreach (self::PRODUCT_CATEGORIES as $category) {
            $plans = Plan::query()
                ->where('status', Status::ENABLE)
                ->where('category', $category)
                ->whereHas('thirdPartyPortals', function ($query) use ($portal) {
                    $query->where('third_party_portals.id', $portal->id)
                        ->where('plan_third_party_portal.published', true);
                })
                ->get();

            if ($plans->isEmpty()) {
                continue;
            }

            $fromAnnual = $plans
                ->map(fn (Plan $plan) => $plan->annualPriceForRange(1))
                ->filter(fn ($price) => $price > 0)
                ->min();
            $fromMonthly = $plans
                ->map(fn (Plan $plan) => $plan->monthlyPriceForRange(1))
                ->filter(fn ($price) => $price > 0)
                ->min();

            $types[] = [
                'category' => $category,
                'plan_count' => $plans->count(),
                'from_annual' => $fromAnnual ?: null,
                'from_monthly' => $fromMonthly ?: null,
            ];
        }

        return $types;
    }

    public function matchForPortal(ThirdPartyPortal $portal, array $criteria): Collection
    {
        $age = $this->resolveAge($criteria['birthday'] ?? null);
        $sex = (int) ($criteria['sex'] ?? 0);
        $smoking = (int) ($criteria['smoking'] ?? 0);
        $health = (int) ($criteria['health'] ?? 1);
        $chronic = (bool) ($criteria['chronic_disease'] ?? false);
        $payType = ($criteria['pay_type'] ?? 'year') === 'month' ? 'month' : 'year';
        $range = max(1, min(8, (int) ($criteria['price_range'] ?? 1)));
        $priceColumn = $payType === 'year' ? "annualprice{$range}" : "monthprice{$range}";

        return Plan::query()
            ->where('status', Status::ENABLE)
            ->whereHas('thirdPartyPortals', function ($query) use ($portal) {
                $query->where('third_party_portals.id', $portal->id)
                    ->where('plan_third_party_portal.published', true);
            })
            ->when($age, function ($query) use ($age) {
                $query->where(function ($inner) use ($age) {
                    $inner->where('agefrom', '<=', $age)->orWhere('agefrom', 0)->orWhereNull('agefrom');
                })->where(function ($inner) use ($age) {
                    $inner->where('ageto', '>=', $age)->orWhere('ageto', 0)->orWhereNull('ageto');
                });
            })
            ->where(function ($query) use ($sex) {
                $query->where('sex', (string) $sex)
                    ->orWhere('sex', '0')
                    ->orWhere('sex', '');
            })
            ->where(function ($query) use ($smoking) {
                $query->where('nicotin', (string) $smoking)
                    ->orWhere('nicotin', '2')
                    ->orWhere('nicotin', '');
            })
            ->where(function ($query) use ($health) {
                $query->where('health', (string) $health)
                    ->orWhere('health', '0')
                    ->orWhere('health', '');
            })
            ->when($chronic, fn ($query) => $query->where('allows_chronic', true))
            ->when(!empty($criteria['product_category']), function ($query) use ($criteria) {
                $query->where('category', $criteria['product_category']);
            })
            ->where($priceColumn, '>', 0)
            ->orderBy($priceColumn)
            ->get()
            ->each(function (Plan $plan) use ($payType, $range) {
                $plan->matched_monthly = $plan->monthlyPriceForRange($range);
                $plan->matched_annual = $plan->annualPriceForRange($range);
                $plan->matched_pay_type = $payType;
                $plan->matched_range = $range;
                $plan->matched_price = $payType === 'year' ? $plan->matched_annual : $plan->matched_monthly;
            });
    }

    public function resolveAge(?string $birthday): ?int
    {
        if (!$birthday) {
            return null;
        }

        try {
            return Carbon::parse($birthday)->age;
        } catch (\Throwable) {
            return null;
        }
    }

    public function assertEligible(Plan $plan, ThirdPartyPortal $portal, array $criteria): bool
    {
        return $this->matchForPortal($portal, $criteria)->contains('id', $plan->id);
    }

    /**
     * To-App Plans uses the same insurance catalog as Check Insure when that portal exists;
     * otherwise matches any enabled plan in the insurance categories.
     */
    public function productTypesForApp(): array
    {
        $portal = ThirdPartyPortal::findBySlug('check-insure');
        if ($portal) {
            return $this->productTypesForPortal($portal);
        }

        return $this->productTypesWithoutPortal();
    }

    public function matchForApp(array $criteria): Collection
    {
        $portal = ThirdPartyPortal::findBySlug('check-insure');
        if ($portal) {
            return $this->matchForPortal($portal, $criteria);
        }

        return $this->matchWithoutPortal($criteria);
    }

    public function assertEligibleForApp(Plan $plan, array $criteria): bool
    {
        return $this->matchForApp($criteria)->contains('id', $plan->id);
    }

    protected function productTypesWithoutPortal(): array
    {
        $types = [];

        foreach (self::PRODUCT_CATEGORIES as $category) {
            $plans = Plan::query()
                ->where('status', Status::ENABLE)
                ->where('category', $category)
                ->get();

            if ($plans->isEmpty()) {
                continue;
            }

            $fromAnnual = $plans
                ->map(fn (Plan $plan) => $plan->annualPriceForRange(1))
                ->filter(fn ($price) => $price > 0)
                ->min();
            $fromMonthly = $plans
                ->map(fn (Plan $plan) => $plan->monthlyPriceForRange(1))
                ->filter(fn ($price) => $price > 0)
                ->min();

            $types[] = [
                'category' => $category,
                'plan_count' => $plans->count(),
                'from_annual' => $fromAnnual ?: null,
                'from_monthly' => $fromMonthly ?: null,
            ];
        }

        return $types;
    }

    protected function matchWithoutPortal(array $criteria): Collection
    {
        $age = $this->resolveAge($criteria['birthday'] ?? null);
        $sex = (int) ($criteria['sex'] ?? 0);
        $smoking = (int) ($criteria['smoking'] ?? 0);
        $health = (int) ($criteria['health'] ?? 1);
        $chronic = (bool) ($criteria['chronic_disease'] ?? false);
        $payType = ($criteria['pay_type'] ?? 'year') === 'month' ? 'month' : 'year';
        $range = max(1, min(8, (int) ($criteria['price_range'] ?? 1)));
        $priceColumn = $payType === 'year' ? "annualprice{$range}" : "monthprice{$range}";

        return Plan::query()
            ->where('status', Status::ENABLE)
            ->whereIn('category', self::PRODUCT_CATEGORIES)
            ->when($age, function ($query) use ($age) {
                $query->where(function ($inner) use ($age) {
                    $inner->where('agefrom', '<=', $age)->orWhere('agefrom', 0)->orWhereNull('agefrom');
                })->where(function ($inner) use ($age) {
                    $inner->where('ageto', '>=', $age)->orWhere('ageto', 0)->orWhereNull('ageto');
                });
            })
            ->where(function ($query) use ($sex) {
                $query->where('sex', (string) $sex)
                    ->orWhere('sex', '0')
                    ->orWhere('sex', '');
            })
            ->where(function ($query) use ($smoking) {
                $query->where('nicotin', (string) $smoking)
                    ->orWhere('nicotin', '2')
                    ->orWhere('nicotin', '');
            })
            ->where(function ($query) use ($health) {
                $query->where('health', (string) $health)
                    ->orWhere('health', '0')
                    ->orWhere('health', '');
            })
            ->when($chronic, fn ($query) => $query->where('allows_chronic', true))
            ->when(!empty($criteria['product_category']), function ($query) use ($criteria) {
                $query->where('category', $criteria['product_category']);
            })
            ->where($priceColumn, '>', 0)
            ->orderBy($priceColumn)
            ->get()
            ->each(function (Plan $plan) use ($payType, $range) {
                $plan->matched_monthly = $plan->monthlyPriceForRange($range);
                $plan->matched_annual = $plan->annualPriceForRange($range);
                $plan->matched_pay_type = $payType;
                $plan->matched_range = $range;
                $plan->matched_price = $payType === 'year' ? $plan->matched_annual : $plan->matched_monthly;
            });
    }
}
