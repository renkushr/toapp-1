<?php

namespace App\Http\Controllers\ToappAgent;

use App\Constants\Status;
use App\Models\InsuranceApplication;
use App\Models\Plan;
use App\Models\User;
use App\Services\PlanMatchingService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $applications = InsuranceApplication::with(['user', 'plan'])
            ->forAgentTerritory($agent)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('toapp_agent.applications.index', [
            'pageTitle' => __('Insurance Applications'),
            'agent' => $agent,
            'applications' => $applications,
        ]);
    }

    public function show(InsuranceApplication $application)
    {
        $agent = Auth::guard('agent')->user();
        $this->authorizeApplication($application, $agent);

        $application->load(['user', 'plan']);

        $plans = Plan::query()
            ->where('status', Status::ENABLE)
            ->whereIn('category', PlanMatchingService::PRODUCT_CATEGORIES)
            ->orderBy('category')
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'monthprice1', 'annualprice1', 'monthprice2', 'annualprice2', 'monthprice3', 'annualprice3', 'monthprice4', 'annualprice4', 'monthprice5', 'annualprice5', 'monthprice6', 'annualprice6', 'monthprice7', 'annualprice7', 'monthprice8', 'annualprice8']);

        $planPrices = [];
        foreach ($plans as $plan) {
            $tiers = [];
            for ($i = 1; $i <= 8; $i++) {
                $tiers[$i] = [
                    'monthly' => $plan->monthlyPriceForRange($i),
                    'annual' => $plan->annualPriceForRange($i),
                ];
            }
            $planPrices[$plan->id] = [
                'category' => $plan->category,
                'tiers' => $tiers,
            ];
        }

        return view('toapp_agent.applications.show', [
            'pageTitle' => $application->reference,
            'agent' => $agent,
            'application' => $application,
            'plans' => $plans,
            'planPrices' => $planPrices,
            'coverageAmounts' => range(100000, 1000000, 100000),
            'coverageLengths' => [10, 15, 20, 25, 30],
        ]);
    }

    public function updateQuote(Request $request, InsuranceApplication $application)
    {
        $agent = Auth::guard('agent')->user();
        $this->authorizeApplication($application, $agent);

        abort_if(
            $application->status === InsuranceApplication::STATUS_CONFIRMED,
            403,
            'Confirmed applications cannot be edited.'
        );

        $coverageAmounts = range(100000, 1000000, 100000);

        $data = $request->validate([
            'plan_id' => [
                'required',
                'integer',
                Rule::exists('plans', 'id')->where(function ($query) {
                    $query->where('status', Status::ENABLE)
                        ->whereIn('category', PlanMatchingService::PRODUCT_CATEGORIES);
                }),
            ],
            'sex' => ['required', 'integer', 'in:1,2'],
            'smoking' => ['required', 'integer', 'in:0,1'],
            'health' => ['required', 'integer', 'in:1,2'],
            'chronic_disease' => ['required', 'boolean'],
            'pay_type' => ['required', 'in:month,year'],
            'price_range' => ['nullable', 'integer', 'min:1', 'max:8'],
            'coverage_amount' => ['required', 'integer', Rule::in($coverageAmounts)],
            'coverage_length' => ['nullable', 'integer', Rule::in([10, 15, 20, 25, 30])],
            'living_benefits' => ['required', 'boolean'],
            'quoted_monthly' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'quoted_annual' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'agent_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $plan = Plan::query()->findOrFail($data['plan_id']);
        $isTermLife = $plan->category === 'Term Life';
        $range = max(1, min(8, (int) ($data['price_range'] ?? $application->price_range ?? 1)));

        $application->update([
            'plan_id' => $plan->id,
            'sex' => (int) $data['sex'],
            'smoking' => (int) $data['smoking'],
            'health' => (int) $data['health'],
            'chronic_disease' => (int) $data['chronic_disease'] === 1,
            'pay_type' => $data['pay_type'],
            'price_range' => $range,
            'coverage_amount' => (int) $data['coverage_amount'],
            'coverage_length' => $isTermLife ? (int) ($data['coverage_length'] ?? 20) : null,
            'living_benefits' => (int) $data['living_benefits'] === 1,
            'quoted_monthly' => round((float) $data['quoted_monthly'], 2),
            'quoted_annual' => round((float) $data['quoted_annual'], 2),
            'admin_notes' => $data['agent_notes'] ?? $application->admin_notes,
        ]);

        return back()->with('status', 'Quote and customer conditions updated.');
    }

    public function store(User $user)
    {
        $agent = Auth::guard('agent')->user();
        abort_unless($agent->canAccessUser($user), 403);

        $application = InsuranceApplication::startPackageFor($user, $agent->id);

        return redirect()
            ->route('toapp.agent.applications.show', $application)
            ->with('status', 'New package added. Choose the product and set the quote.');
    }

    public function markPaymentPending(InsuranceApplication $application)
    {
        $agent = Auth::guard('agent')->user();
        $this->authorizeApplication($application, $agent);

        $application->update(['status' => InsuranceApplication::STATUS_PAYMENT_PENDING]);

        return back()->with('status', 'Marked as awaiting payment confirmation.');
    }

    public function confirm(Request $request, InsuranceApplication $application)
    {
        $agent = Auth::guard('agent')->user();
        $this->authorizeApplication($application, $agent);

        $request->validate(['notes' => ['nullable', 'string', 'max:2000']]);

        $application->update([
            'status' => InsuranceApplication::STATUS_CONFIRMED,
            'payment_confirmed_at' => now(),
            'confirmed_by_agent_id' => $agent->id,
            'admin_notes' => $request->notes,
        ]);

        return back()->with('status', 'Policy confirmed after customer payment.');
    }

    protected function authorizeApplication(InsuranceApplication $application, $agent): void
    {
        abort_unless(
            InsuranceApplication::query()
                ->forAgentTerritory($agent)
                ->whereKey($application->id)
                ->exists(),
            403
        );
    }
}
