<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $user = auth()->user();

        if (!$user->status || $this->needsVerification($user)) {
            if ($request->is('api/*')) {
                $notify[] = $user->status
                    ? 'You need to verify your account first.'
                    : 'Your account has been suspended.';

                return response()->json([
                    'remark' => $user->status ? 'unverified' : 'banned',
                    'status' => 'error',
                    'message' => ['error' => $notify],
                    'data' => ['user' => $user],
                ], 403);
            }

            if (!$this->onVerificationRoute($request)) {
                return to_route('user.authorization');
            }

            return $next($request);
        }

        if (gs('user_approval') && in_array($user->approval_status, ['pending', 'rejected'], true)) {
            if ($request->is('api/*')) {
                $notify[] = $user->approval_status === 'pending'
                    ? 'Your account is waiting for admin approval.'
                    : 'Your account was rejected. Please contact support.';

                return response()->json([
                    'remark' => 'approval_required',
                    'status' => 'error',
                    'message' => ['error' => $notify],
                    'data' => ['user' => $user],
                ], 403);
            }

            if (!$request->routeIs('user.approval.pending', 'user.logout')) {
                return to_route('user.approval.pending');
            }

            return $next($request);
        }

        return $next($request);
    }

    protected function needsVerification($user): bool
    {
        return (gs('ev') && !$user->ev)
            || (gs('sv') && !$user->sv)
            || ($user->ts == Status::VERIFIED && !$user->tv);
    }

    protected function onVerificationRoute($request): bool
    {
        return $request->routeIs(
            'user.authorization',
            'user.logout',
            'user.send.verify.code',
            'user.verify.email',
            'user.verify.mobile',
            'user.2fa.verify'
        );
    }
}
