<?php

namespace App\Http\Controllers\CheckInsure;

use App\Models\AdminNotification;
use App\Models\AgentZipCode;
use App\Models\InsuranceApplication;
use App\Models\Plan;
use App\Models\User;
use App\Services\AgentAssignmentService;
use App\Services\PlanMatchingService;
use App\Support\UsZip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends PortalController
{
    public function __construct(
        private PlanMatchingService $matcher,
        private AgentAssignmentService $agentAssignment
    ) {
    }

    public function profile()
    {
        $user = Auth::user();
        $application = $this->applicationFor($user);

        return view('check_insure.profile', [
            'pageTitle' => 'Personal Information',
            'portal' => $this->portal(),
            'user' => $user,
            'application' => $application,
        ]);
    }

    public function saveProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'sex' => ['required', 'in:1,2'],
            'birthday' => ['required', 'date_format:m/d/Y', 'before:' . now()->subYears(18)->format('m/d/Y')],
            'zipcode' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
        ]);

        $birthday = Carbon::createFromFormat('m/d/Y', $data['birthday'])->format('Y-m-d');

        $zip = UsZip::normalize($data['zipcode']);

        $user->sex = $data['sex'];
        $user->birthday = $birthday;
        $user->zipcode = $zip;
        $user->save();

        session([
            'checkinsure_profile_step' => [
                'sex' => (int) $data['sex'],
                'birthday' => $birthday,
                'zipcode' => $zip,
            ],
        ]);

        if (!AgentZipCode::where('zip_code', $zip)->whereHas('agent', fn ($q) => $q->where('status', true))->exists()) {
            session()->flash('checkinsure_no_agent', true);
        }

        return redirect()->route('checkinsure.survey');
    }

    public function survey()
    {
        $user = Auth::user();

        if (!session('checkinsure_profile_step')) {
            return redirect()->route('checkinsure.profile');
        }

        return view('check_insure.survey', [
            'pageTitle' => 'Short Survey',
            'portal' => $this->portal(),
            'user' => $user,
            'productTypes' => $this->matcher->productTypesForPortal($this->portal()),
        ]);
    }

    public function saveSurvey(Request $request)
    {
        $user = Auth::user();
        $application = $this->applicationFor($user);
        $profileStep = session('checkinsure_profile_step');

        if (!$profileStep) {
            return redirect()->route('checkinsure.profile');
        }

        $data = $request->validate([
            'health' => ['required', 'in:1,2'],
            'smoking' => ['required', 'in:0,1'],
            'chronic_disease' => ['nullable', 'boolean'],
            'pay_type' => ['required', 'in:month,year'],
            'product_category' => ['required', 'string', 'in:' . implode(',', PlanMatchingService::PRODUCT_CATEGORIES)],
            'price_range' => ['nullable', 'integer', 'min:1', 'max:8'],
            'coverage_length' => [
                $request->input('product_category') === 'Term Life' ? 'required' : 'nullable',
                'integer',
                'in:' . implode(',', $this->coverageLengthYears()),
            ],
        ]);

        $productTypes = collect($this->matcher->productTypesForPortal($this->portal()))
            ->pluck('category')
            ->all();

        if (!in_array($data['product_category'], $productTypes, true)) {
            return back()->withErrors(['product_category' => 'Selected insurance type is not available right now.'])->withInput();
        }

        $user->chronic_disease = $request->boolean('chronic_disease');
        $user->health = $data['health'];
        $user->nicotin = $data['smoking'];
        $user->profile_complete = 1;
        $user->save();

        $criteria = array_merge($profileStep, [
            'chronic_disease' => $user->chronic_disease,
            'smoking' => (int) $data['smoking'],
            'health' => (int) $data['health'],
            'pay_type' => $data['pay_type'],
            'product_category' => $data['product_category'],
            'price_range' => (int) ($data['price_range'] ?? 1),
            'coverage_length' => $data['product_category'] === 'Term Life' ? (int) $data['coverage_length'] : null,
        ]);

        $application?->update([
            'sex' => $criteria['sex'],
            'birthday' => $criteria['birthday'],
            'zipcode' => $profileStep['zipcode'],
            'chronic_disease' => $criteria['chronic_disease'],
            'smoking' => $criteria['smoking'],
            'health' => $criteria['health'],
            'pay_type' => $criteria['pay_type'],
            'price_range' => $criteria['price_range'],
            'coverage_length' => $criteria['coverage_length'],
            'status' => InsuranceApplication::STATUS_QUOTED,
        ]);

        session(['checkinsure_criteria' => $criteria]);

        return redirect()->route('checkinsure.results');
    }

    public function results()
    {
        $user = Auth::user();
        $criteria = session('checkinsure_criteria');

        if (!$criteria) {
            return redirect()->route('checkinsure.profile');
        }

        $plans = $this->matcher->matchForPortal($this->portal(), $criteria);

        return view('check_insure.results', [
            'pageTitle' => 'Your Result',
            'portal' => $this->portal(),
            'user' => $user,
            'plans' => $plans,
            'criteria' => $criteria,
        ]);
    }

    public function showPlan(Plan $plan)
    {
        $criteria = session('checkinsure_criteria') ?? [];
        abort_unless($this->matcher->assertEligible($plan, $this->portal(), $criteria), 404);

        $plan->matched_price = ($criteria['pay_type'] ?? 'year') === 'year'
            ? $plan->annualPriceForRange((int) ($criteria['price_range'] ?? 1))
            : $plan->monthlyPriceForRange((int) ($criteria['price_range'] ?? 1));

        return view('check_insure.plan_detail', [
            'pageTitle' => $plan->name,
            'portal' => $this->portal(),
            'plan' => $plan,
            'criteria' => $criteria,
        ]);
    }

    public function selectPlan(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $criteria = session('checkinsure_criteria') ?? [];
        abort_unless($this->matcher->assertEligible($plan, $this->portal(), $criteria), 404);

        $validated = $request->validate([
            'price_range' => ['nullable', 'integer', 'min:1', 'max:8'],
        ]);

        if (isset($validated['price_range'])) {
            $criteria['price_range'] = (int) $validated['price_range'];
            session(['checkinsure_criteria' => $criteria]);
        }

        $application = $this->applicationFor($user);
        $range = (int) ($criteria['price_range'] ?? 1);
        $payType = ($criteria['pay_type'] ?? 'year') === 'month' ? 'month' : 'year';
        $coverageLength = isset($criteria['coverage_length']) ? (int) $criteria['coverage_length'] : null;

        $application?->update([
            'plan_id' => $plan->id,
            'status' => InsuranceApplication::STATUS_CHATTING,
            'quoted_monthly' => $plan->monthlyPriceForRange($range),
            'quoted_annual' => $plan->annualPriceForRange($range),
            'pay_type' => $payType,
            'price_range' => $range,
            'coverage_length' => $coverageLength,
            'terms_accepted_at' => now(),
        ]);

        return $this->connectToAgentOrWaiting($application, $user);
    }

    public function terms(InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        $application->load('plan');

        return view('check_insure.terms', [
            'pageTitle' => 'Terms',
            'portal' => $this->portal(),
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

            return redirect()->route('checkinsure.chat', $application)
                ->with('status', 'You are now connected with your agent. Payment happens outside this system.');
        }

        $application->update([
            'status' => InsuranceApplication::STATUS_TERMS_ACCEPTED,
            'terms_accepted_at' => now(),
        ]);

        $this->notifyAdminUnassignedUser($user);

        return redirect()->route('checkinsure.waiting_agent', $application)
            ->with('status', 'Your application is saved. We will connect you with an agent for your ZIP code soon.');
    }

    public function waitingAgent(InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        abort_unless($application->status === InsuranceApplication::STATUS_TERMS_ACCEPTED, 404);

        return view('check_insure.waiting_agent', [
            'pageTitle' => 'Waiting for Agent',
            'portal' => $this->portal(),
            'application' => $application,
            'user' => Auth::user(),
        ]);
    }

    public function chat(InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        $user = Auth::user();
        $agent = $user->agent;

        abort_unless($agent, 404);

        $conversation = \App\Models\AgentConversation::findOrCreateFor($agent->id, $user->id);
        $messages = $conversation->messages()->with('attachments')->oldest('id')->get();

        return view('check_insure.chat', [
            'pageTitle' => 'Chat with Agent',
            'portal' => $this->portal(),
            'application' => $application,
            'user' => $user,
            'agent' => $agent,
            'conversation' => $conversation,
            'messages' => $messages,
            'pusherAuthEndpoint' => url('/broadcasting/auth'),
        ]);
    }

    public function sendChat(Request $request, InsuranceApplication $application)
    {
        $this->authorizeApplication($application);
        $user = Auth::user();

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file'],
        ]);

        $chatService = app(\App\Services\AgentChatService::class);
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

    protected function applicationFor($user): ?InsuranceApplication
    {
        return InsuranceApplication::where('user_id', $user->id)
            ->where('third_party_portal_id', $this->portal()->id)
            ->latest('id')
            ->first();
    }

    protected function authorizeApplication(InsuranceApplication $application): void
    {
        abort_unless((int) $application->user_id === (int) Auth::id(), 403);
        abort_unless((int) $application->third_party_portal_id === (int) $this->portal()->id, 403);
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

            return redirect()->route('checkinsure.chat', $application)
                ->with('status', 'You are now connected with your agent. Payment happens outside this system.');
        }

        $application->update([
            'status' => InsuranceApplication::STATUS_TERMS_ACCEPTED,
        ]);

        $this->notifyAdminUnassignedUser($user);

        return redirect()->route('checkinsure.waiting_agent', $application)
            ->with('status', 'Your application is saved. We will connect you with an agent for your ZIP code soon.');
    }
}
