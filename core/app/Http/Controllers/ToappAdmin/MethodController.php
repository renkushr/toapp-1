<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Models\Gateway;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MethodController extends Controller
{
    public function index()
    {
        return view('toapp_admin.methods.index', [
            'pageTitle' => __('Payment Methods'),
            'depositGateways' => Gateway::with('singleCurrency')->orderBy('code')->get(),
            'withdrawMethods' => WithdrawMethod::orderBy('name')->get(),
        ]);
    }

    public function updateDepositGateway(Request $request, Gateway $gateway)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:2000'],
            'currency' => ['nullable', 'string', 'max:40'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'gte:min_amount'],
            'fixed_charge' => ['nullable', 'numeric', 'min:0'],
            'percent_charge' => ['nullable', 'numeric', 'between:0,100'],
            'rate' => ['nullable', 'numeric', 'min:0'],
        ]);

        $gateway->name = $validated['name'];
        $gateway->description = $validated['description'] ?? '';
        $gateway->save();

        $currency = $gateway->singleCurrency;
        if ($currency) {
            $currency->name = $validated['name'];
            $currency->currency = $validated['currency'] ?? $currency->currency;
            $currency->min_amount = $validated['min_amount'] ?? $currency->min_amount;
            $currency->max_amount = $validated['max_amount'] ?? $currency->max_amount;
            $currency->fixed_charge = $validated['fixed_charge'] ?? $currency->fixed_charge;
            $currency->percent_charge = $validated['percent_charge'] ?? $currency->percent_charge;
            $currency->rate = $validated['rate'] ?? $currency->rate;
            $currency->save();
        }

        return redirect()
            ->route('toapp.admin.methods.deposit.edit', $gateway)
            ->with('status', 'Deposit gateway updated.');
    }

    public function editDepositGateway(Gateway $gateway)
    {
        $gateway->load('singleCurrency');

        return view('toapp_admin.methods.deposit_edit', [
            'pageTitle' => __('Edit Deposit Gateway'),
            'gateway' => $gateway,
        ]);
    }

    public function editWithdrawMethod(WithdrawMethod $method)
    {
        return view('toapp_admin.methods.withdraw_edit', [
            'pageTitle' => __('Edit Withdrawal Method'),
            'method' => $method,
        ]);
    }

    public function updateWithdrawMethod(Request $request, WithdrawMethod $method)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:2000'],
            'currency' => ['required', 'string', 'max:40'],
            'min_limit' => ['required', 'numeric', 'min:0'],
            'max_limit' => ['required', 'numeric', 'gte:min_limit'],
            'fixed_charge' => ['required', 'numeric', 'min:0'],
            'percent_charge' => ['required', 'numeric', 'between:0,100'],
            'rate' => ['required', 'numeric', 'gt:0'],
        ]);

        foreach ($validated as $key => $value) {
            $method->{$key} = $value;
        }
        $method->save();

        return redirect()
            ->route('toapp.admin.methods.withdraw.edit', $method)
            ->with('status', 'Withdrawal method updated.');
    }

    public function toggleDepositGateway(Gateway $gateway)
    {
        $gateway->status = $gateway->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $gateway->save();

        return back()->with('status', 'Deposit gateway status updated.');
    }

    public function toggleWithdrawMethod(WithdrawMethod $method)
    {
        $method->status = $method->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $method->save();

        return back()->with('status', 'Withdrawal method status updated.');
    }
}
