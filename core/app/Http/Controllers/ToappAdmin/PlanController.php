<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Models\Plan;
use App\Models\ThirdPartyPortal;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = Plan::query()
            ->with('thirdPartyPortals')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('toapp_admin.plans.index', [
            'pageTitle' => 'Insurance Products',
            'plans' => $plans,
        ]);
    }

    public function create()
    {
        return view('toapp_admin.plans.create', [
            'pageTitle' => 'New Product',
            'plan' => null,
            'portals' => ThirdPartyPortal::where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function edit(Plan $plan)
    {
        $plan->load('thirdPartyPortals');

        return view('toapp_admin.plans.edit', [
            'pageTitle' => 'Edit Product',
            'plan' => $plan,
            'portals' => ThirdPartyPortal::where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $plan = new Plan();
        $this->savePlan($request, $plan);

        return redirect()
            ->route('toapp.admin.plans.edit', $plan)
            ->with('status', 'Product created successfully.');
    }

    public function update(Request $request, Plan $plan)
    {
        $this->savePlan($request, $plan);

        return redirect()
            ->route('toapp.admin.plans.edit', $plan)
            ->with('status', 'Product updated successfully.');
    }

    public function status(Plan $plan)
    {
        $plan->status = $plan->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $plan->save();

        return back()->with('status', 'Product status updated.');
    }

    private function savePlan(Request $request, Plan $plan): void
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'numeric', 'gt:0'],
            'max_amount' => ['required', 'numeric', 'gt:min_amount'],
            'total_return' => ['required', 'integer', 'gt:0'],
            'interest_type' => ['required', 'in:1,2'],
            'interest' => ['required', 'numeric', 'gt:0'],
            'descript' => ['nullable', 'string', 'max:250'],
            'category' => ['nullable', 'string', 'max:250'],
            'term' => ['nullable', 'string', 'max:8000'],
            'monthprice' => ['nullable', 'numeric', 'min:0'],
            'annualprice' => ['nullable', 'numeric', 'min:0'],
            'agefrom' => ['nullable', 'integer', 'min:18', 'max:120'],
            'ageto' => ['nullable', 'integer', 'min:18', 'max:120'],
            'sex' => ['nullable', 'in:0,1,2'],
            'health' => ['nullable', 'in:0,1,2'],
            'nicotin' => ['nullable', 'in:0,1,2'],
            'allows_chronic' => ['nullable', 'boolean'],
            'portals' => ['nullable', 'array'],
            'portals.*' => ['integer', 'exists:third_party_portals,id'],
        ]);

        foreach (['name', 'min_amount', 'max_amount', 'total_return', 'interest_type', 'interest', 'descript', 'category', 'term', 'monthprice', 'annualprice', 'agefrom', 'ageto', 'sex', 'health', 'nicotin'] as $field) {
            if (array_key_exists($field, $validated)) {
                $plan->{$field} = $validated[$field] ?? ($plan->{$field} ?? 0);
            }
        }

        for ($i = 1; $i <= 8; $i++) {
            $plan->{"monthprice{$i}"} = (float) $request->input("monthprice{$i}", $plan->{"monthprice{$i}"} ?? 0);
            $plan->{"annualprice{$i}"} = (float) $request->input("annualprice{$i}", $plan->{"annualprice{$i}"} ?? 0);
        }

        $plan->allows_chronic = $request->boolean('allows_chronic', true);
        $plan->status ??= Status::ENABLE;
        $plan->save();

        $sync = [];
        foreach ((array) $request->input('portals', []) as $portalId) {
            $sync[$portalId] = ['published' => true];
        }
        $plan->thirdPartyPortals()->sync($sync);
    }
}
