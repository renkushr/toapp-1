<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\GeneralSetting;
use App\Models\Plan;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ReadinessController extends Controller
{
    public function index()
    {
        $general = GeneralSetting::first();
        $checks = [
            $this->check(__('Database connection'), $this->databaseIsReady(), __('Laravel can connect to the active MySQL database.')),
            $this->check(__('Admin auth'), auth('admin')->check(), __('New admin guard is active for /admin.')),
            $this->check(__('Site identity'), filled($general?->site_name) && filled($general?->cur_text), __('Site name and currency text should be set.')),
            $this->check(__('Active plans'), Plan::where('status', Status::ENABLE)->exists(), __('At least one enabled customer plan is required.')),
            $this->check(__('Deposit gateway'), Gateway::where('status', Status::ENABLE)->exists(), __('At least one active deposit method should be available.')),
            $this->check(__('Withdraw method'), WithdrawMethod::where('status', Status::ENABLE)->exists(), __('At least one active withdrawal method should be available.')),
            $this->check(__('Users table'), Schema::hasTable('users') && User::count() >= 0, __('User storage is reachable.')),
            $this->check(__('Pending queue reviewed'), Deposit::pending()->count() === 0 && Withdrawal::pending()->count() === 0, __('No pending finance requests before launch.')),
        ];

        return view('toapp_admin.readiness.index', [
            'pageTitle' => __('Launch Readiness'),
            'checks' => $checks,
            'readyCount' => collect($checks)->where('ok', true)->count(),
        ]);
    }

    private function databaseIsReady(): bool
    {
        try {
            DB::select('select 1');
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    private function check(string $title, bool $ok, string $detail): array
    {
        return compact('title', 'ok', 'detail');
    }
}
