<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Models\InsuranceApplication;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InsuranceApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = InsuranceApplication::with(['user', 'plan', 'agent', 'portal'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(function ($inner) use ($search) {
                    $inner->where('reference', 'like', $search)
                        ->orWhereHas('user', fn ($u) => $u->where('email', 'like', $search)->orWhere('firstname', 'like', $search));
                });
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('toapp_admin.applications.index', [
            'pageTitle' => 'Insurance Applications',
            'applications' => $applications,
        ]);
    }

    public function show(InsuranceApplication $application)
    {
        $application->load(['user', 'plan', 'agent', 'portal']);

        return view('toapp_admin.applications.show', [
            'pageTitle' => $application->reference,
            'application' => $application,
        ]);
    }

    public function confirm(Request $request, InsuranceApplication $application)
    {
        $request->validate(['notes' => ['nullable', 'string', 'max:2000']]);

        $application->update([
            'status' => InsuranceApplication::STATUS_CONFIRMED,
            'payment_confirmed_at' => now(),
            'confirmed_by_admin_id' => auth('admin')->id(),
            'admin_notes' => $request->notes,
        ]);

        AdminAudit::record('insurance.confirmed', $application, [
            'reference' => $application->reference,
            'plan_id' => $application->plan_id,
        ]);

        return back()->with('status', 'Application confirmed after external payment.');
    }

    public function reject(Request $request, InsuranceApplication $application)
    {
        $validated = $request->validate(['reason' => ['required', 'string', 'max:1000']]);

        $application->update([
            'status' => InsuranceApplication::STATUS_REJECTED,
            'rejection_reason' => $validated['reason'],
        ]);

        AdminAudit::record('insurance.rejected', $application, $validated);

        return back()->with('status', 'Application rejected.');
    }
}
