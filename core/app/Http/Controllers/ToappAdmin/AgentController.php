<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Models\Agent;
use App\Models\AgentZipCode;
use App\Services\AgentAssignmentService;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $agents = Agent::query()
            ->withCount(['users', 'zipCodes'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%' . $request->string('search')->toString() . '%';
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', $search)
                        ->orWhere('username', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('license_no', 'like', $search);
                });
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('toapp_admin.agents.index', [
            'pageTitle' => 'Agents',
            'agents' => $agents,
        ]);
    }

    public function store(Request $request, AgentAssignmentService $assignmentService)
    {
        $data = $this->validatedData($request);
        $zipCodes = $assignmentService->parseZipCodes($data['zip_codes']);

        if (empty($zipCodes)) {
            return back()->withErrors(['zip_codes' => 'Enter at least one valid US ZIP code.'])->withInput();
        }

        DB::transaction(function () use ($data, $zipCodes, $request) {
            $agent = Agent::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'license_no' => strtoupper(trim($data['license_no'])),
                'password' => Hash::make($data['password']),
                'status' => $request->boolean('status', true),
            ]);

            foreach ($zipCodes as $zip) {
                AgentZipCode::create([
                    'agent_id' => $agent->id,
                    'zip_code' => $zip,
                ]);
            }

            AdminAudit::record('agent.created', $agent, [
                'license_no' => $agent->license_no,
                'zip_codes' => $zipCodes,
                'status' => (bool) $agent->status,
            ]);
        });

        return redirect()
            ->route('toapp.admin.agents.edit', $agent)
            ->with('status', 'Agent account created.');
    }

    public function create()
    {
        return view('toapp_admin.agents.create', [
            'pageTitle' => 'New Agent',
            'agent' => null,
        ]);
    }

    public function edit(Agent $agent)
    {
        return view('toapp_admin.agents.edit', [
            'pageTitle' => 'Edit Agent',
            'agent' => $agent,
        ]);
    }

    public function update(Request $request, Agent $agent, AgentAssignmentService $assignmentService)
    {
        $data = $this->validatedData($request, $agent);
        $zipCodes = $assignmentService->parseZipCodes($data['zip_codes']);

        if (empty($zipCodes)) {
            return back()->withErrors(['zip_codes' => 'Enter at least one valid US ZIP code.'])->withInput();
        }

        DB::transaction(function () use ($request, $agent, $data, $zipCodes) {
            $agent->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'license_no' => strtoupper(trim($data['license_no'])),
                'status' => $request->boolean('status'),
            ]);

            if (!empty($data['password'])) {
                $agent->password = Hash::make($data['password']);
            }

            $agent->save();

            $agent->zipCodes()->delete();

            foreach ($zipCodes as $zip) {
                AgentZipCode::create([
                    'agent_id' => $agent->id,
                    'zip_code' => $zip,
                ]);
            }

            AdminAudit::record('agent.updated', $agent, [
                'license_no' => $agent->license_no,
                'zip_codes' => $zipCodes,
                'status' => (bool) $agent->status,
                'password_changed' => !empty($data['password']),
            ]);
        });

        return redirect()
            ->route('toapp.admin.agents.edit', $agent)
            ->with('status', 'Agent account updated.');
    }

    private function validatedData(Request $request, ?Agent $agent = null): array
    {
        $agentId = $agent?->id;

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('agents', 'email')->ignore($agentId)],
            'username' => ['required', 'string', 'alpha_dash', 'max:80', Rule::unique('agents', 'username')->ignore($agentId)],
            'license_no' => ['required', 'string', 'max:80', Rule::unique('agents', 'license_no')->ignore($agentId)],
            'password' => [$agent ? 'nullable' : 'required', 'string', 'min:8', 'max:128'],
            'zip_codes' => ['required', 'string', 'max:5000'],
            'status' => ['nullable', 'boolean'],
        ]);
    }
}
