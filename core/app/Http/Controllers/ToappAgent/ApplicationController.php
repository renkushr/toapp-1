<?php

namespace App\Http\Controllers\ToappAgent;

use App\Models\InsuranceApplication;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

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
            'pageTitle' => 'Insurance Applications',
            'agent' => $agent,
            'applications' => $applications,
        ]);
    }

    public function show(InsuranceApplication $application)
    {
        $agent = Auth::guard('agent')->user();
        $this->authorizeApplication($application, $agent);

        $application->load(['user', 'plan']);

        return view('toapp_agent.applications.show', [
            'pageTitle' => $application->reference,
            'agent' => $agent,
            'application' => $application,
        ]);
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
