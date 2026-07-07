<?php

namespace App\Http\Controllers\User;

use DB;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\DeviceToken;
use App\Models\Form;
use App\Models\Investment;
use App\Models\Plan;
use App\Models\Year;
use App\Models\Referral;
use App\Models\ReferralCommission;
use App\Models\Transaction;
use App\Models\User;
use App\Services\AgentAssignmentService;
use App\Services\ReferralCommissionService;
use App\Support\UsZip;
use Illuminate\Support\Facades\Hash;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;


class UserController extends Controller
{
    public function approvalPending()
    {
        $pageTitle = 'Account Approval';
        $user = auth()->user();

        return view('Template::user.approval_pending', compact('pageTitle', 'user'));
    }

    public function home()
    {
        $pageTitle     = 'Dashboard';
        $user          = auth()->user();
        $totalDeposit  = Deposit::where('user_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $totalWithdraw = Withdrawal::where('user_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $latestTrx     = Transaction::where('user_id', $user->id)->latest()->limit(10)->get();
        $totalInvest   = Investment::where('user_id', $user->id)->sum('amount');
        $plans         = Plan::where('status', 1)->get();
        $runningInvestments = Investment::where('user_id', $user->id)->where('status', Status::RUNNING);
        $runningInvestmentCount = (clone $runningInvestments)->count();
        $nextReturnDate = (clone $runningInvestments)->orderBy('next_return_date')->value('next_return_date');
        $directReferralCount = User::where('ref_by', $user->id)->count();
        $totalReferralCommission = ReferralCommission::where('earner_user_id', $user->id)->sum('amount');
        $recentReferralCommissions = ReferralCommission::with(['sourceUser', 'plan'])
            ->where('earner_user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        $referralCode = $user->refno ?: $user->username;
        $referralLink = route('home', ['reference' => $referralCode]);

        return view('Template::user.dashboard', compact(
            'pageTitle',
            'user',
            'totalDeposit',
            'totalWithdraw',
            'latestTrx',
            'totalInvest',
            'plans',
            'runningInvestmentCount',
            'nextReturnDate',
            'directReferralCount',
            'totalReferralCommission',
            'recentReferralCommissions',
            'referralCode',
            'referralLink'
        ));
    }
    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->whereNotNull('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.login')->withNotify($notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.login')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'kyc')->first();
        return view('Template::user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view('Template::user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->firstOrFail();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $user = auth()->user();
        foreach (@$user->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $userData = $formProcessor->processFormData($request, $formData);
        $user->kyc_data = $userData;
        $user->kyc_rejection_reason = null;
        $user->kv = Status::KYC_PENDING;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.login')->withNotify($notify);
    }

    public function userData()
    {
        // $user = auth()->user();
        
        // if (@$user->profile_complete == Status::YES) {
        //     return to_route('user.login');
        // }

        // if(!session('user_register')){
        //     return to_route('user.register');
        // }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $year  = Year::all();
      
       return view('Template::user.user_data', compact('pageTitle',  'mobileCode','year'));
    }

    public function register3()
    {
       
        $pageTitle  = 'User Data';
       
        return view('Template::user.register3', compact('pageTitle'));
    }

    public function withdrawSuccess()
    {
        $pageTitle = 'Withdrawal Successful';
        return view('Template::user.withdrawsuccess', compact('pageTitle'));
    }


    public function withdrawUnsuccess()
    {
        $pageTitle = 'Withdrawal Failed';
        return view('Template::user.withdrawunsuccess', compact('pageTitle'));
    }


    public function userDataSubmit(Request $request, AgentAssignmentService $agentAssignment)
    {
       
        $user_register  = session()->get('user_register');

        $request->validate([
            'sex'   => 'required',
            'dob' => 'required|date|date_format:Y-m-d|before:'.now()->subYears(16)->toDateString(),
            'ssn'           => 'required',
            'zipcode'       => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
            'agent_license_no' => ['required', 'string', 'max:80'],
            'mobile'        => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users', 'mobile')],
        ]);

        $agent = $agentAssignment->resolve(
            $request->string('agent_license_no')->toString(),
            $request->string('zipcode')->toString()
        );
        
        $user_register['sex']     =  $request->sex;

        $user_register['dob']     =  $request->dob;

        $user_register['ssn']     =  $request->ssn;

        $user_register['zipcode'] =  UsZip::normalize($request->zipcode);

        $user_register['agent_license_no'] = strtoupper(trim($request->agent_license_no));

        $user_register['agent_id'] = $agent->id;

        $user_register['mobile']  =  $request->mobile;

        $user_register['profile_complete'] = 3;

        session()->put('user_register', $user_register);
        


        return to_route('user.register3');
    }

    


    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function referrals()
    {
    
        $pageTitle = 'My Referrals';
        $referrals = Referral::with('plan')
            ->whereIn('commission_type', ['package_commission', 'deposit_commission'])
            ->orderBy('level')
            ->orderBy('plan_id')
            ->get();
        $user = auth()->user();
        $level = max((int) $referrals->max('level'), 1);
        $totalNetwork = $this->totalLevelCountData($user->id,$level);
        $levelCounts = collect($this->levelCountData($user->id, $level))->keyBy('level');
        $networkRows = collect($this->networkMembersData($user->id, $level));
        $selectedNetworkLevel = request('network_level');

        if (request()->has('network_level') && ($selectedNetworkLevel === null || $selectedNetworkLevel === '')) {
            $cleanQuery = collect(request()->query())
                ->reject(fn ($value, $key) => $key === 'network_level' || $value === null || $value === '')
                ->all();

            return redirect()->route('user.referrals', $cleanQuery);
        }

        if ($selectedNetworkLevel !== null && $selectedNetworkLevel !== '') {
            $networkRows = $networkRows->where('level', (int) $selectedNetworkLevel)->values();
        }

        $networkSummary = [
            'active' => $networkRows->filter(fn ($member) => (int) $member->plan_id !== 0)->count(),
            'inactive' => $networkRows->filter(fn ($member) => (int) $member->plan_id === 0)->count(),
            'pending' => $networkRows->where('approval_status', 'pending')->count(),
            'commission' => $networkRows->sum(fn ($member) => (float) $member->total_commission),
        ];

        $networkPage = LengthAwarePaginator::resolveCurrentPage('network_page');
        $networkMembers = new LengthAwarePaginator(
            $networkRows->forPage($networkPage, 12)->values(),
            $networkRows->count(),
            12,
            $networkPage,
            [
                'path' => route('user.referrals'),
                'pageName' => 'network_page',
                'query' => request()->except('network_page'),
            ]
        );

        $directReferrals = User::where('ref_by', $user->id)
            ->latest()
            ->take(8)
            ->get();
        $commissions = ReferralCommission::with(['sourceUser', 'plan'])
            ->where('earner_user_id', $user->id)
            ->latest()
            ->paginate(10);
        $totalReferralCommission = ReferralCommission::where('earner_user_id', $user->id)->sum('amount');
        $monthlyReferralCommission = ReferralCommission::where('earner_user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $referralCode = $user->refno ?: $user->username;
        $referralLink = route('home', ['reference' => $referralCode]);

        return view('Template::user.referrals', compact(
            'pageTitle',
            'referrals',
            'totalNetwork',
            'levelCounts',
            'networkMembers',
            'networkSummary',
            'selectedNetworkLevel',
            'directReferrals',
            'commissions',
            'totalReferralCommission',
            'monthlyReferralCommission',
            'referralCode',
            'referralLink'
        ));
    }

    protected function networkMembersData($user_id, $level)
    {
        return DB::select("
            WITH RECURSIVE user_level AS (
                SELECT
                    id,
                    ref_by,
                    plan_id,
                    username,
                    firstname,
                    lastname,
                    email,
                    status,
                    approval_status,
                    created_at,
                    1 AS level
                FROM users
                WHERE ref_by = ?

                UNION ALL

                SELECT
                    u.id,
                    u.ref_by,
                    u.plan_id,
                    u.username,
                    u.firstname,
                    u.lastname,
                    u.email,
                    u.status,
                    u.approval_status,
                    u.created_at,
                    ul.level + 1
                FROM users u
                JOIN user_level ul ON u.ref_by = ul.id
                WHERE ul.level < ?
            )
            SELECT
                ul.*,
                ref.username AS referrer_username,
                plans.name AS plan_name,
                COALESCE(commissions.total_commission, 0) AS total_commission,
                COALESCE(commissions.commission_count, 0) AS commission_count
            FROM user_level ul
            LEFT JOIN users ref ON ref.id = ul.ref_by
            LEFT JOIN plans ON plans.id = ul.plan_id
            LEFT JOIN (
                SELECT source_user_id, SUM(amount) AS total_commission, COUNT(*) AS commission_count
                FROM referral_commissions
                WHERE earner_user_id = ?
                GROUP BY source_user_id
            ) commissions ON commissions.source_user_id = ul.id
            ORDER BY ul.level ASC, ul.created_at DESC
        ", [$user_id, $level, $user_id]);
    }

    protected function totalLevelCountData($user_id,$level){
      $totalLevel = DB::select("
        WITH RECURSIVE user_level AS (
            SELECT id, ref_by, plan_id, 1 AS level
            FROM users
            WHERE ref_by = ? 
            UNION ALL
            SELECT u.id, u.ref_by, u.plan_id, ul.level + 1
            FROM users u
            JOIN user_level ul ON u.ref_by = ul.id
            WHERE ul.level < ? -- Limit the recursion based on level
        )
        SELECT 
            COALESCE(SUM(CASE WHEN plan_id != 0 THEN 1 ELSE 0 END), 0) AS total_active_count,
            COALESCE(SUM(CASE WHEN plan_id = 0 OR plan_id IS NULL THEN 1 ELSE 0 END), 0) AS total_inactive_count,
            COALESCE(SUM(CASE WHEN plan_id != 0 THEN 1 ELSE 0 END), 0) + COALESCE(SUM(CASE WHEN plan_id = 0 OR plan_id IS NULL THEN 1 ELSE 0 END), 0) AS total_count
        FROM user_level
    ", [$user_id, $level]);
     return $totalLevel[0]->total_count ?? 0;
    }
    protected function levelCountData($user_id,$level){
            return DB::select("
            WITH RECURSIVE user_level AS (
                SELECT id, ref_by, 1 AS level, plan_id
                FROM users
                WHERE ref_by = ? 
                UNION ALL
                SELECT u.id, u.ref_by, ul.level + 1, u.plan_id
                FROM users u
                JOIN user_level ul ON u.ref_by = ul.id
                WHERE ul.level < ?
            ),
            
            level_range AS (
                SELECT 1 AS level
                UNION ALL
                SELECT level + 1 FROM level_range WHERE level < ?
            )
        
            -- Main query: gets active and inactive counts per level
            SELECT 
                lr.level, 
                COALESCE(SUM(CASE WHEN ul.plan_id != 0 THEN 1 ELSE 0 END), 0) AS active_count,
                COALESCE(SUM(CASE WHEN ul.plan_id = 0 OR ul.plan_id IS NULL THEN 1 ELSE 0 END), 0) AS inactive_count,
                COALESCE(SUM(CASE WHEN ul.plan_id != 0 THEN 1 ELSE 0 END), 0) + COALESCE(SUM(CASE WHEN ul.plan_id = 0 OR ul.plan_id IS NULL THEN 1 ELSE 0 END), 0) AS total_count
            FROM level_range lr
            LEFT JOIN user_level ul ON ul.level = lr.level
            GROUP BY lr.level;
        ", [$user_id, $level, $level]);
    }
    public function levelCount(Request $request){

        try{
        $validator = Validator::make($request->all(), [
            'level' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()],422);
        }

        $user = auth()->user();

        $maxLevel = max((int) Referral::max('level'), 1);

        $level = $request->level;
        
        $levelCounts = $this->levelCountData($user->id,$level);

        $totalNetwork = $this->totalLevelCountData($user->id,$maxLevel);

        return response()->json(['levels'=>$levelCounts,'total_network'=>$totalNetwork]);
      }catch(\Throwable $e){
        return response()->json(['error' => 'Network level '.$level.' user status failed'],500);
      }
    }


    public function plans()
    {
        $pageTitle = "All Plans";
        $plans     = Plan::where('status', Status::ENABLE)->orderBy("min_amount","ASC")->paginate(getPaginate());
        return view('Template::user.plans', compact('pageTitle', 'plans'));
    }

    public function investment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'id'     => 'required|integer',
        ]);

        $plan = Plan::where('id', $request->id)->where('status', Status::ENABLE)->firstOrFail();

        if ($plan->min_amount > $request->amount || $plan->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow the investment limit'];
            return back()->withNotify($notify);
        }

        $user = auth()->user();
        if ($user->balance < $request->amount) {
            $notify[] = ['error', 'Sorry, You have not sufficient balance'];
            return to_route('user.deposit.index')->withNotify($notify);
        }

        $interest   = 0;
        $nextReturn = Carbon::now()->addDay(1);

        if ($plan->interest_type == Status::FIXED) {
            $interest = $plan->interest;
        } else {
            $interest = ($request->amount * $plan->interest) / 100;
        }

        $user->balance -= $request->amount;
        $user->save();


        $newInvest                   = new Investment();
        $newInvest->trx              = getTrx();
        $newInvest->plan_id          = $plan->id;
        $newInvest->user_id          = $user->id;
        $newInvest->amount           = $request->amount;
        $newInvest->interest_type    = $plan->interest_type;
        $newInvest->interest_amount  = $interest;
        $newInvest->total_return     = $plan->total_return;
        $newInvest->next_return_date = $nextReturn;
        $newInvest->status           = Status::INVESTMENT_PENDING;
        $newInvest->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $request->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->remark       = 'invest_pending';
        $transaction->details      = 'Package purchase request for ' . $plan->name . ' is waiting for admin approval';
        $transaction->trx          = $newInvest->trx;
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'Package purchase waiting approval: ' . $plan->name . ' from ' . $user->username;
        $adminNotification->click_url = url('/admin/reports/investments?status=' . Status::INVESTMENT_PENDING);
        $adminNotification->save();

        $general = gs();

        notify($user, 'INVESTMENT', [
            'currency'     => $general->cur_text,
            'trx'          => $transaction->trx,
            'plan'         => $plan->name,
            'amount'       => showAmount($request->amount, currencyFormat: false),
            'details'      => $transaction->details,
            'post_balance' => $user->balance,
            'interest'     => $interest,
            'total_return' => $newInvest->total_return
        ]);

        $notify[] = ['success', 'Package purchase submitted. Your balance is reserved and admin will review it before activation.'];
        return redirect()->route('user.investment.log')->withNotify($notify);
    }

    public function investmentLog()
    {
        $pageTitle = 'Investments';
        $user      = auth()->user();
        $investments = Investment::where('user_id', auth()->id())->searchable(['trx'])->filter(['interest_type', 'status'])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('Template::user.investment_log', compact('pageTitle', 'investments'));
    }


    public function setting()
    {
       
        $pageTitle = 'Setting';
        $user      = auth()->user();
        return view('Template::user.setting', compact('pageTitle','user'));
    }

public function call()
    {
       
        $pageTitle = 'call';
        return view('Template::user.call', compact('pageTitle'));
    }
}
