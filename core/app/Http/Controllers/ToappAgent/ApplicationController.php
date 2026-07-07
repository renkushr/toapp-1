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
            ->where('agent_id', $agent->id)
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
        abort_unless((int) $application->agent_id === (int) $agent->id, 403);

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
        abort_unless((int) $application->agent_id === (int) $agent->id, 403);

        $application->update(['status' => InsuranceApplication::STATUS_PAYMENT_PENDING]);

        return back()->with('status', 'Marked as awaiting payment confirmation.');
    }

    public function confirm(Request $request, InsuranceApplication $application)
    {
        $agent = Auth::guard('agent')->user();
        abort_unless((int) $application->agent_id === (int) $agent->id, 403);

        $request->validate(['notes' => ['nullable', 'string', 'max:2000']]);

        $application->update([
            'status' => InsuranceApplication::STATUS_CONFIRMED,
            'payment_confirmed_at' => now(),
            'confirmed_by_agent_id' => $agent->id,
            'admin_notes' => $request->notes,
        ]);

        return back()->with('status', 'Policy confirmed after customer payment.');
    }
}
