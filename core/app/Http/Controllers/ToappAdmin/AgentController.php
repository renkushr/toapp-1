<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Models\Agent;
use App\Models\AgentZipCode;
use App\Services\AgentAssignmentService;
use App\Support\AdminAudit;
use App\Support\UsZipCatalog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    public function zipStates(Request $request)
    {
        return response()->json([
            'states' => UsZipCatalog::states($request->string('q')->toString() ?: null),
        ]);
    }

    public function zipAll()
    {
        return response()->json(UsZipCatalog::allZipCodesByState());
    }

    public function zipStateDetail(Request $request, string $state)
    {
        $state = strtoupper(trim($state));
        $states = UsZipCatalog::all();

        abort_unless(isset($states[$state]), 404);

        return response()->json([
            'abbr' => $state,
            'name' => $states[$state]['name'] ?? $state,
            'zips' => UsZipCatalog::zipsForState($state, $request->string('q')->toString() ?: null),
            'count' => count($states[$state]['zips'] ?? []),
        ]);
    }

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

        $previewIds = $agents->getCollection()
            ->filter(fn (Agent $agent) => (int) $agent->zip_codes_count > 0 && (int) $agent->zip_codes_count <= 8)
            ->pluck('id')
            ->all();

        if ($previewIds !== []) {
            $grouped = AgentZipCode::query()
                ->whereIn('agent_id', $previewIds)
                ->orderBy('zip_code')
                ->get()
                ->groupBy('agent_id');

            foreach ($agents as $agent) {
                if ($grouped->has($agent->id)) {
                    $agent->setRelation('zipCodes', $grouped->get($agent->id));
                }
            }
        }

        return view('toapp_admin.agents.index', [
            'pageTitle' => __('Agents'),
            'agents' => $agents,
            'zipCatalogTotal' => (int) (UsZipCatalog::allZipCodesByState()['total'] ?? 0),
        ]);
    }

    public function store(Request $request, AgentAssignmentService $assignmentService)
    {
        $data = $this->validatedData($request);
        $zipCodes = $this->resolveZipCodes($request, $assignmentService, $data['zip_codes'] ?? null);

        if (empty($zipCodes)) {
            return back()->withErrors(['zip_codes' => 'Enter at least one valid US ZIP code.'])->withInput();
        }

        $agent = null;

        DB::transaction(function () use ($data, $zipCodes, $request, &$agent) {
            $agent = Agent::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'license_no' => strtoupper(trim($data['license_no'])),
                'fee_percent' => $data['fee_percent'],
                'password' => Hash::make($data['password']),
                'status' => $request->boolean('status', true),
            ]);

            $this->syncAgentZipCodes($agent, $zipCodes);

            AdminAudit::record('agent.created', $agent, [
                'license_no' => $agent->license_no,
                'fee_percent' => (float) $agent->fee_percent,
                'zip_codes_count' => count($zipCodes),
                'zip_select_all_usa' => $request->boolean('zip_select_all_usa'),
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
            'pageTitle' => __('New Agent'),
            'agent' => null,
            'selectedZipsByState' => [],
        ]);
    }

    public function edit(Agent $agent)
    {
        $selectedCount = $agent->zipCodes()->count();
        $catalogTotal = (int) (UsZipCatalog::allZipCodesByState()['total'] ?? 0);
        $isNationwide = $catalogTotal > 0 && $selectedCount >= $catalogTotal;

        return view('toapp_admin.agents.edit', [
            'pageTitle' => __('Edit Agent'),
            'agent' => $agent,
            'selectedZipsByState' => $isNationwide
                ? []
                : UsZipCatalog::groupZipsByState($agent->zipCodes()->orderBy('zip_code')->pluck('zip_code')->all()),
            'zipSelectAllUsa' => $isNationwide,
            'zipCatalogTotal' => $catalogTotal,
        ]);
    }

    public function update(Request $request, Agent $agent, AgentAssignmentService $assignmentService)
    {
        $data = $this->validatedData($request, $agent);
        $zipCodes = $this->resolveZipCodes($request, $assignmentService, $data['zip_codes'] ?? null);

        if (empty($zipCodes)) {
            return back()->withErrors(['zip_codes' => 'Enter at least one valid US ZIP code.'])->withInput();
        }

        DB::transaction(function () use ($request, $agent, $data, $zipCodes) {
            $agent->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'license_no' => strtoupper(trim($data['license_no'])),
                'fee_percent' => $data['fee_percent'],
                'status' => $request->boolean('status'),
            ]);

            if (!empty($data['password'])) {
                $agent->password = Hash::make($data['password']);
            }

            $agent->save();

            $this->syncAgentZipCodes($agent, $zipCodes);

            AdminAudit::record('agent.updated', $agent, [
                'license_no' => $agent->license_no,
                'fee_percent' => (float) $agent->fee_percent,
                'zip_codes_count' => count($zipCodes),
                'zip_select_all_usa' => $request->boolean('zip_select_all_usa'),
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
            'fee_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'password' => [$agent ? 'nullable' : 'required', 'string', 'min:8', 'max:128'],
            'zip_codes' => [Rule::requiredIf(fn () => !$request->boolean('zip_select_all_usa')), 'nullable', 'string'],
            'zip_select_all_usa' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ]);
    }

    private function resolveZipCodes(Request $request, AgentAssignmentService $assignmentService, ?string $zipCodesInput): array
    {
        if ($request->boolean('zip_select_all_usa')) {
            return UsZipCatalog::allZipCodes();
        }

        return $assignmentService->parseZipCodes((string) $zipCodesInput);
    }

    private function syncAgentZipCodes(Agent $agent, array $zipCodes): void
    {
        $agent->zipCodes()->delete();

        $unique = [];
        foreach ($zipCodes as $zip) {
            $zip = trim((string) $zip);
            if ($zip === '') {
                continue;
            }
            $unique[$zip] = $zip;
        }
        $zipCodes = array_values($unique);

        if ($zipCodes === []) {
            return;
        }

        $now = now();
        $rows = array_map(static fn (string $zip) => [
            'agent_id' => $agent->id,
            'zip_code' => $zip,
            'created_at' => $now,
            'updated_at' => $now,
        ], $zipCodes);

        foreach (array_chunk($rows, 1000) as $chunk) {
            AgentZipCode::insert($chunk);
        }
    }
}
