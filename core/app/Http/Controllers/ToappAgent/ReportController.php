<?php

namespace App\Http\Controllers\ToappAgent;

use App\Services\AgentReportService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request, AgentReportService $reports)
    {
        $agent = Auth::guard('agent')->user();
        $days = max(0, (int) $request->input('days', 30));
        $since = $reports->periodStart($days);

        return view('toapp_agent.reports.index', [
            'pageTitle' => 'My Performance Report',
            'agent' => $agent,
            'metrics' => $reports->summaryForAgent($agent, $since),
            'days' => $days,
        ]);
    }
}
