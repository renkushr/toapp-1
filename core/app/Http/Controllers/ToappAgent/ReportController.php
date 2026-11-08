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

        $yearInput = $request->input('year');
        $year = ($yearInput !== null && $yearInput !== '' && $yearInput !== '0')
            ? (int) $yearInput
            : null;

        $requestedDays = max(0, (int) $request->input('days', 30));
        $period = $reports->resolvePeriod($year ? 0 : $requestedDays, $year);

        return view('toapp_agent.reports.index', [
            'pageTitle' => __('My Performance Report'),
            'agent' => $agent,
            'metrics' => $reports->summaryForAgent($agent, $period['since'], $period['until']),
            'charts' => $reports->chartsForAgent($agent, $period['since'], $period['days'], $period, $period['until']),
            'days' => $year ? $requestedDays : $period['days'],
            'year' => $period['year'],
            'period' => $period,
            'availableYears' => $reports->availableYears(
                $agent->created_at ? (int) $agent->created_at->format('Y') : null
            ),
        ]);
    }
}
