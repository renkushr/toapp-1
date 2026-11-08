<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\AgentZipCode;
use App\Models\InsuranceApplication;
use App\Models\Plan;
use App\Models\ThirdPartyPortal;
use App\Models\User;
use App\Services\AgentChatService;
use App\Services\PlanMatchingService;
use App\Support\UsZip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InsuranceQuoteController extends Controller
{
    public function __construct(private PlanMatchingService $matcher)
    {
    }

    public function entry()
    {
        return redirect()->route('user.plans.survey');
    }

    public function myPackages(Request $request)
    {
        $user = Auth::user();
        $draftStatuses = [
            InsuranceApplication::STATUS_OTP_PENDING,
            InsuranceApplication::STATUS_PROFILE_PENDING,
            InsuranceApplication::STATUS_QUOTED,
        ];

        $applications = InsuranceApplication::with(['plan', 'agent'])
            ->where('user_id', $user->id)
            ->where(fn ($q) => $q->whereNotNull('plan_id')->orWhereNotIn('status', $draftStatuses))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest('id')
            ->paginate(getPaginate())
            ->withQueryString();

        return view('Template::user.insurance.my_packages', [
            'pageTitle' => 'My Packages',
            'user' => $user,
            'applications' => $applications,
        ]);
    }

    public function profile()
    {
        return redirect()->route('user.plans.survey');
    }

    public function saveProfile(Request $request)
    {
        return redirect()->route('user.plans.survey');
    }

    public function survey()
    {
        $user = Auth::user();
        $this->ensureApplication($user);

        return view('Template::user.insurance.survey', [
            'pageTitle' => 'Choose a Plan',
            'user' => $user,
            'productTypes' => $this->matcher->productTypesForApp(),
            'criteria' => session('toapp_criteria'),
            'coverageAmounts' => $this->coverageAmounts(),
            'needsSex' => blank($user->sex),
            'needsZip' => blank($user->zipcode),
            'hasBirthday' => filled($user->birthday),
        ]);
    }

    public function saveSurvey(Request $request)
    {
        $user = Auth::user();
        $application = $this->ensureApplication($user);

        $coverageAmounts = $this->coverageAmounts();

        $rules = [
            'health' => ['required', 'in:1,2'],
            'smoking' => ['required', 'in:0,1'],
            'chronic_disease' => ['nullable', 'boolean'],
            'living_benefits' => ['required', 'in:0,1'],
            'pay_type' => ['required', 'in:month,year'],
            'product_category' => ['required', 'string', 'in:' . implode(',', PlanMatchingService::PRODUCT_CATEGORIES)],
            'price_range' => ['nullable', 'integer', 'min:1', 'max:8'],
            'coverage_amount' => ['required', 'integer', 'in:' . implode(',', $coverageAmounts)],
            'coverage_length' => [
                Rule::requiredIf(fn () => $request->input('product_category') === 'Term Life'),
                'nullable',
                'integer',
                'in:' . implode(',', $this->coverageLengthYears()),
            ],
        ];

        if (blank($user->sex)) {
            $rules['sex'] = ['required', 'in:1,2'];
        }

        if (blank($user->zipcode)) {
            $rules['zipcode'] = ['required', 'regex:/^\d{5}(-\d{4})?$/'];
        }

        if (blank($user->birthday)) {
            $rules['birthday'] = ['required', 'date_format:Y-m-d', 'before:' . now()->subYears(18)->format('Y-m-d')];
        }

        $data = $request->validate($rules);

        $productTypes = collect($this->matcher->productTypesForApp())->pluck('category')->all();
        if (!in_array($data['product_category'], $productTypes, true)) {
            return back()->withErrors(['product_category' => 'Selected insurance type is not available right now.'])->withInput();
        }

        if (isset($data['sex'])) {
            $user->sex = (int) $data['sex'];
        }
        if (isset($data['zipcode'])) {
            $user->zipcode = UsZip::normalize($data['zipcode']);
        }
        if (isset($data['birthday'])) {
            $user->birthday = $data['birthday'];
        }

        $user->chronic_disease = $request->boolean('chronic_disease');
        $user->health = $data['health'];
        $user->nicotin = $data['smoking'];
        $user->profile_complete = 1;
        $user->save();

        $birthday = $user->birthday
            ? Carbon::parse($user->birthday)->format('Y-m-d')
            : null;
        $zip = UsZip::normalize((string) $user->zipcode);
        $livingBenefits = $request->boolean('living_benefits');
        $coverageAmount = (int) $data['coverage_amount'];

        $criteria = [
            'sex' => (int) $user->sex,
            'birthday' => $birthday,
            'zipcode' => $zip,
            'chronic_disease' => (bool) $user->chronic_disease,
            'smoking' => (int) $data['smoking'],
            'health' => (int) $data['health'],
            'pay_type' => $data['pay_type'],
            'product_category' => $data['product_category'],
            'price_range' => (int) ($data['price_range'] ?? 1),
            'coverage_amount' => $coverageAmount,
            'living_benefits' => $livingBenefits,
            'coverage_length' => $data['product_category'] === 'Term Life'
                ? (int) ($data['coverage_length'] ?? 0)
                : null,
        ];

        session([
            'toapp_profile_step' => [
                'sex' => $criteria['sex'],
                'birthday' => $criteria['birthday'],
                'zipcode' => $criteria['zipcode'],
            ],
            'toapp_criteria' => $criteria,
        ]);

        $matched = $this->matcher->matchForApp($criteria);
        $plan = $matched->first();
        if (!$plan) {
            return back()->withErrors([
                'product_category' => 'No matching coverage is available for your selections. Please adjust and try again.',
            ])->withInput();
        }

        $range = (int) $criteria['price_range'];
        $payType = $criteria['pay_type'] === 'month' ? 'month' : 'year';

        $application->update([
            'sex' => $criteria['sex'],
            'birthday' => $criteria['birthday'],
            'zipcode' => $criteria['zipcode'],
            'chronic_disease' => $criteria['chronic_disease'],
            'smoking' => $criteria['smoking'],
            'health' => $criteria['health'],
            'pay_type' => $payType,
            'price_range' => $range,
            'coverage_amount' => $coverageAmount,
            'coverage_length' => $criteria['coverage_length'],
            'living_benefits' => $livingBenefits,
            'plan_id' => $plan->id,
            'quoted_monthly' => $plan->monthlyPriceForRange($range),
            'quoted_annual' => $plan->annualPriceForRange($range),
            'terms_accepted_at' => now(),
            'status' => InsuranceApplication::STATUS_CHATTING,
        ]);

        return $this->connectToAgentOrWaiting($application, $user);
    }

    public function results()
    {
        return redirect()->route('user.plans.survey');
    }

    public function showPlan(Plan $plan)
    {
        return redirect()->route('user.plans.survey');
    }

    public function selectPlan(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $criteria = $this->criteria($user) ?? [];
        if (!$criteria || empty($criteria['product_category'])) {
            return redirect()->route('user.plans.survey');
        }
        abort_unless($this->matcher->assertEligibleForApp($plan, $criteria), 404);

        $validated = $request->validate([
            'price_range' => ['nullable', 'integer', 'min:1', 'max:8'],
        ]);

        if (isset($validated['price_range'])) {
            $criteria['price_range'] = (int) $validated['price_range'];
            session(['toapp_criteria' => $criteria]);
        }

        $application = $this->ensureApplication($user);
        $range = (int) ($criteria['price_range'] ?? 1);
        $payType = ($criteria['pay_type'] ?? 'year') === 'month' ? 'month' : 'year';
        $coverageLength = isset($criteria['coverage_length']) ? (int) $criteria['coverage_length'] : null;

        $application->update([
            'plan_id' => $plan->id,
            'status' => InsuranceApplication::STATUS_CHATTING,
            'quoted_monthly' => $plan->monthlyPriceForRange($range),
            'quoted_annual' => $plan->annualPriceForRange($range),
            'pay_type' => $payType,
            'price_range' => $range,
            'coverage_amount' => isset($criteria['coverage_amount']) ? (int) $criteria['coverage_amount'] : $application->coverage_amount,
            'coverage_length' => $coverageLength,
            'living_benefits' => (bool) ($criteria['living_benefits'] ?? $application->living_benefits),
            'terms_accepted_at' => now(),
        ]);

        return $this->connectToAgentOrWaiting($application, $user);
    }

    public function terms(InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        $application->load('plan');

        return view('Template::user.insurance.terms', [
            'pageTitle' => 'Terms',
            'application' => $application,
        ]);
    }

    public function confirmTerms(Request $request, InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        $request->validate(['accept' => ['accepted']]);

        $user = Auth::user();
        $zip = UsZip::normalize($user->zipcode);
        $agentZip = AgentZipCode::where('zip_code', $zip)->whereHas('agent', fn ($q) => $q->where('status', true))->first();

        if ($agentZip) {
            $user->agent_id = $agentZip->agent_id;
            $user->agent_license_no = $agentZip->agent->license_no;
            $user->save();

            $application->update([
                'agent_id' => $agentZip->agent_id,
                'status' => InsuranceApplication::STATUS_CHATTING,
                'terms_accepted_at' => now(),
                'chat_started_at' => now(),
            ]);

            $notify[] = ['success', 'You are now connected with your agent. Payment happens outside this system.'];
            return redirect()->route('user.plans.chat', $application)->withNotify($notify);
        }

        $application->update([
            'status' => InsuranceApplication::STATUS_TERMS_ACCEPTED,
            'terms_accepted_at' => now(),
        ]);

        $this->notifyAdminUnassignedUser($user);

        $notify[] = ['success', 'Your application is saved. We will connect you with an agent for your ZIP code soon.'];
        return redirect()->route('user.plans.waiting', $application)->withNotify($notify);
    }

    public function waiting(InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        abort_unless($application->status === InsuranceApplication::STATUS_TERMS_ACCEPTED, 404);

        $user = Auth::user();
        if ($user->agent_id) {
            $application->update([
                'agent_id' => $user->agent_id,
                'status' => InsuranceApplication::STATUS_CHATTING,
                'chat_started_at' => $application->chat_started_at ?: now(),
            ]);

            return redirect()->route('user.plans.chat', $application);
        }

        return view('Template::user.insurance.waiting', [
            'pageTitle' => 'Waiting for Agent',
            'application' => $application,
            'user' => $user,
        ]);
    }

    public function chat(InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        $user = Auth::user();
        $agent = $user->agent;
        abort_unless($agent, 404);

        if ($application->status === InsuranceApplication::STATUS_TERMS_ACCEPTED && $user->agent_id) {
            $application->update([
                'agent_id' => $user->agent_id,
                'status' => InsuranceApplication::STATUS_CHATTING,
                'chat_started_at' => $application->chat_started_at ?: now(),
            ]);
        }

        $conversation = \App\Models\AgentConversation::findOrCreateFor($agent->id, $user->id);
        $messages = $conversation->messages()->with('attachments')->oldest('id')->get();
        app(AgentChatService::class)->markReadForRecipient($conversation, 'user');

        return view('Template::user.insurance.chat', [
            'pageTitle' => 'Chat with Agent',
            'application' => $application,
            'user' => $user,
            'agent' => $agent,
            'conversation' => $conversation,
            'messages' => $messages,
            'pusherAuthEndpoint' => url('/broadcasting/auth'),
            'canEditConditions' => $application->canUserEditConditions(),
        ]);
    }

    public function editConditions(InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        abort_unless($application->canUserEditConditions(), 403, 'This application can no longer be edited.');

        $user = Auth::user();
        $criteria = [
            'sex' => (int) ($application->sex ?: $user->sex ?: 0),
            'health' => (int) ($application->health ?: $user->health ?: 1),
            'smoking' => (int) ($application->smoking ?? $user->nicotin ?? 0),
            'chronic_disease' => (bool) ($application->chronic_disease ?? $user->chronic_disease),
            'pay_type' => $application->pay_type ?: 'year',
            'product_category' => $application->plan?->category
                ?? ($application->coverage_length ? 'Term Life' : (optional($this->matcher->productTypesForApp()[0] ?? null)['category'] ?? '')),
            'coverage_amount' => (int) ($application->coverage_amount ?: 100000),
            'coverage_length' => (int) ($application->coverage_length ?: 20),
            'living_benefits' => (bool) $application->living_benefits,
            'price_range' => (int) ($application->price_range ?: 1),
        ];

        return view('Template::user.insurance.edit_conditions', [
            'pageTitle' => 'Edit plan conditions',
            'user' => $user,
            'application' => $application,
            'productTypes' => $this->matcher->productTypesForApp(),
            'criteria' => $criteria,
            'coverageAmounts' => $this->coverageAmounts(),
        ]);
    }

    public function updateConditions(Request $request, InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        abort_unless($application->canUserEditConditions(), 403, 'This application can no longer be edited.');

        $user = Auth::user();
        $coverageAmounts = $this->coverageAmounts();

        $data = $request->validate([
            'sex' => ['required', 'in:1,2'],
            'health' => ['required', 'in:1,2'],
            'smoking' => ['required', 'in:0,1'],
            'chronic_disease' => ['nullable', 'boolean'],
            'living_benefits' => ['required', 'in:0,1'],
            'pay_type' => ['required', 'in:month,year'],
            'product_category' => ['required', 'string', 'in:' . implode(',', PlanMatchingService::PRODUCT_CATEGORIES)],
            'price_range' => ['nullable', 'integer', 'min:1', 'max:8'],
            'coverage_amount' => ['required', 'integer', 'in:' . implode(',', $coverageAmounts)],
            'coverage_length' => [
                Rule::requiredIf(fn () => $request->input('product_category') === 'Term Life'),
                'nullable',
                'integer',
                'in:' . implode(',', $this->coverageLengthYears()),
            ],
        ]);

        $productTypes = collect($this->matcher->productTypesForApp())->pluck('category')->all();
        if (! in_array($data['product_category'], $productTypes, true)) {
            return back()->withErrors(['product_category' => 'Selected insurance type is not available right now.'])->withInput();
        }

        $user->sex = (int) $data['sex'];
        $user->chronic_disease = $request->boolean('chronic_disease');
        $user->health = $data['health'];
        $user->nicotin = $data['smoking'];
        $user->save();

        $birthday = $user->birthday
            ? Carbon::parse($user->birthday)->format('Y-m-d')
            : (optional($application->birthday)->format('Y-m-d'));
        $zip = UsZip::normalize((string) ($user->zipcode ?: $application->zipcode));

        $criteria = [
            'sex' => (int) $data['sex'],
            'birthday' => $birthday,
            'zipcode' => $zip,
            'chronic_disease' => $request->boolean('chronic_disease'),
            'smoking' => (int) $data['smoking'],
            'health' => (int) $data['health'],
            'pay_type' => $data['pay_type'],
            'product_category' => $data['product_category'],
            'price_range' => (int) ($data['price_range'] ?? $application->price_range ?? 1),
            'coverage_amount' => (int) $data['coverage_amount'],
            'living_benefits' => $request->boolean('living_benefits'),
            'coverage_length' => $data['product_category'] === 'Term Life'
                ? (int) ($data['coverage_length'] ?? 20)
                : null,
        ];

        $matched = $this->matcher->matchForApp($criteria);
        $plan = $matched->first();
        if (! $plan) {
            return back()->withErrors([
                'product_category' => 'No matching coverage is available for your selections. Please adjust and try again.',
            ])->withInput();
        }

        $range = max(1, min(8, (int) $criteria['price_range']));
        $payType = $criteria['pay_type'] === 'month' ? 'month' : 'year';
        $previousStatus = $application->status;

        $application->update([
            'sex' => $criteria['sex'],
            'chronic_disease' => $criteria['chronic_disease'],
            'smoking' => $criteria['smoking'],
            'health' => $criteria['health'],
            'pay_type' => $payType,
            'price_range' => $range,
            'coverage_amount' => $criteria['coverage_amount'],
            'coverage_length' => $criteria['coverage_length'],
            'living_benefits' => $criteria['living_benefits'],
            'plan_id' => $plan->id,
            'quoted_monthly' => $plan->monthlyPriceForRange($range),
            'quoted_annual' => $plan->annualPriceForRange($range),
            // Keep chatting / payment_pending; lift waiting/selected into chatting when agent exists.
            'status' => in_array($previousStatus, [
                InsuranceApplication::STATUS_CHATTING,
                InsuranceApplication::STATUS_PAYMENT_PENDING,
            ], true)
                ? $previousStatus
                : ($user->agent_id
                    ? InsuranceApplication::STATUS_CHATTING
                    : InsuranceApplication::STATUS_TERMS_ACCEPTED),
            'chat_started_at' => $application->chat_started_at ?: ($user->agent_id ? now() : null),
            'agent_id' => $application->agent_id ?: $user->agent_id,
        ]);

        session(['toapp_criteria' => $criteria]);

        if ($user->agent_id) {
            $conversation = \App\Models\AgentConversation::findOrCreateFor($user->agent_id, $user->id);
            app(AgentChatService::class)->send(
                $conversation,
                'user',
                $user->id,
                'I updated my plan conditions. Please review the new quote.'
            );

            $notify[] = ['success', 'Plan conditions updated. Your agent has been notified.'];
            return redirect()->route('user.plans.chat', $application)->withNotify($notify);
        }

        $notify[] = ['success', 'Plan conditions updated.'];
        return redirect()->route('user.plans.waiting', $application)->withNotify($notify);
    }

    public function sendChat(Request $request, InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        $user = Auth::user();
        abort_unless($user->agent_id, 403);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file'],
        ]);

        $chatService = app(AgentChatService::class);
        $chatService->validateOutgoing($validated['message'] ?? null, $request->file('attachments'));

        $conversation = \App\Models\AgentConversation::findOrCreateFor($user->agent_id, $user->id);
        $message = $chatService->send(
            $conversation,
            'user',
            $user->id,
            $validated['message'] ?? null,
            $request->file('attachments', [])
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $chatService->payload($message)]);
        }

        return back();
    }

    protected function portalId(): ?int
    {
        return ThirdPartyPortal::findBySlug('check-insure')?->id
            ?? ThirdPartyPortal::query()->where('status', true)->value('id');
    }

    /**
     * Users may buy any number of packages: reuse only an unfinished draft,
     * otherwise start a fresh application.
     */
    protected function ensureApplication(User $user): InsuranceApplication
    {
        $draft = $this->applicationFor($user);
        if ($draft && blank($draft->plan_id) && in_array($draft->status, [
            InsuranceApplication::STATUS_OTP_PENDING,
            InsuranceApplication::STATUS_PROFILE_PENDING,
            InsuranceApplication::STATUS_QUOTED,
        ], true)) {
            return $draft;
        }

        $portalId = $this->portalId();
        abort_unless($portalId, 503, 'Insurance portal is not configured yet.');

        return InsuranceApplication::create([
            'third_party_portal_id' => $portalId,
            'user_id' => $user->id,
            'reference' => 'TA-' . strtoupper(substr(md5($user->id . microtime()), 0, 10)),
            'status' => InsuranceApplication::STATUS_PROFILE_PENDING,
            'sex' => $user->sex,
            'birthday' => $user->birthday,
            'zipcode' => $user->zipcode,
        ]);
    }

    protected function applicationFor(User $user): ?InsuranceApplication
    {
        $portalId = $this->portalId();

        $query = InsuranceApplication::where('user_id', $user->id)->latest('id');
        if ($portalId) {
            $query->where('third_party_portal_id', $portalId);
        }

        return $query->first();
    }

    protected function authorizeApplication(InsuranceApplication $application): void
    {
        abort_unless((int) $application->user_id === (int) Auth::id(), 403);
    }

    protected function profileStep(User $user): ?array
    {
        if (session('toapp_profile_step')) {
            return session('toapp_profile_step');
        }

        if ($user->sex && $user->birthday && $user->zipcode) {
            $step = [
                'sex' => (int) $user->sex,
                'birthday' => Carbon::parse($user->birthday)->format('Y-m-d'),
                'zipcode' => UsZip::normalize($user->zipcode),
            ];
            session(['toapp_profile_step' => $step]);
            return $step;
        }

        return null;
    }

    protected function criteria(User $user): ?array
    {
        if (session('toapp_criteria')) {
            return session('toapp_criteria');
        }

        $application = $this->applicationFor($user);
        if (!$application || !$application->pay_type) {
            return null;
        }

        $criteria = [
            'sex' => (int) ($application->sex ?: $user->sex),
            'birthday' => optional($application->birthday)->format('Y-m-d') ?: ($user->birthday ? Carbon::parse($user->birthday)->format('Y-m-d') : null),
            'zipcode' => $application->zipcode ?: $user->zipcode,
            'chronic_disease' => (bool) $application->chronic_disease,
            'smoking' => (int) $application->smoking,
            'health' => (int) $application->health,
            'pay_type' => $application->pay_type,
            'coverage_amount' => $application->coverage_amount ? (int) $application->coverage_amount : null,
            'living_benefits' => (bool) $application->living_benefits,
            'coverage_length' => $application->coverage_length ? (int) $application->coverage_length : null,
            'product_category' => $application->plan?->category ?? ($application->coverage_length ? 'Term Life' : null),
            'price_range' => (int) ($application->price_range ?: 1),
        ];

        if (!$criteria['birthday'] || !$criteria['product_category']) {
            return null;
        }

        session(['toapp_criteria' => $criteria]);
        return $criteria;
    }

    protected function notifyAdminUnassignedUser(User $user): void
    {
        if ($user->agent_id || blank($user->zipcode)) {
            return;
        }

        $notice = new AdminNotification();
        $notice->user_id = $user->id;
        $notice->title = 'Customer without agent coverage (ZIP code ' . $user->zipcode . ')';
        $notice->click_url = adminPortalUrl('users/' . $user->id);
        $notice->save();
    }

    protected function coverageLengthYears(): array
    {
        return [10, 15, 20, 25, 30];
    }

    protected function coverageAmounts(): array
    {
        return range(100000, 1000000, 100000);
    }

    protected function connectToAgentOrWaiting(InsuranceApplication $application, User $user)
    {
        $zip = UsZip::normalize($user->zipcode);
        $agentZip = AgentZipCode::where('zip_code', $zip)->whereHas('agent', fn ($q) => $q->where('status', true))->first();

        if ($agentZip) {
            $user->agent_id = $agentZip->agent_id;
            $user->agent_license_no = $agentZip->agent->license_no;
            $user->save();

            $application->update([
                'agent_id' => $agentZip->agent_id,
                'status' => InsuranceApplication::STATUS_CHATTING,
                'chat_started_at' => $application->chat_started_at ?: now(),
            ]);

            return redirect()->route('user.plans.chat', $application);
        }

        $application->update([
            'status' => InsuranceApplication::STATUS_TERMS_ACCEPTED,
        ]);
        $this->notifyAdminUnassignedUser($user);

        return redirect()->route('user.plans.waiting', $application);
    }
}
