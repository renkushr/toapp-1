<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\UserNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, UserNotify;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','ver_code','balance','kyc_data'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'kyc_data' => 'object',
        'ver_code_send_at' => 'datetime'
    ];


    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',Status::PAYMENT_INITIATE);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status','!=',Status::PAYMENT_INITIATE);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_by');
    }

    public function directReferrals()
    {
        return $this->hasMany(User::class, 'ref_by');
    }

    public function referralCommissions()
    {
        return $this->hasMany(ReferralCommission::class, 'earner_user_id');
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function applications()
    {
        return $this->hasMany(InsuranceApplication::class);
    }

    public function agentConversation()
    {
        return $this->hasOne(AgentConversation::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn () => $this->dial_code . $this->mobile,
        );
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)->where('approval_status', 'approved')->where('ev',Status::VERIFIED)->where('sv',Status::VERIFIED);
    }

    public function scopeApprovalPending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApprovalRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeKycUnverified($query)
    {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }

    public function scopeWithBalance($query)
    {
        return $query->where('balance','>', 0);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function hasPayoutSetup(): bool
    {
        if ($this->payout_method === 'paypal') {
            return (bool) $this->payout_paypal_email;
        }

        if ($this->payout_method === 'bank') {
            return $this->payout_bank_name
                && $this->payout_account_name
                && $this->payout_account_number
                && $this->payout_routing_number;
        }

        return false;
    }

    public function payoutMethodMatches(?string $methodName): bool
    {
        $name = strtolower($methodName ?? '');

        if ($this->payout_method === 'paypal') {
            return str_contains($name, 'paypal');
        }

        if ($this->payout_method === 'bank') {
            return str_contains($name, 'bank');
        }

        return false;
    }

    public function payoutInformationPayload(): array
    {
        if ($this->payout_method === 'paypal') {
            return [
                ['name' => 'PayPal Email', 'type' => 'email', 'value' => $this->payout_paypal_email],
            ];
        }

        if ($this->payout_method === 'bank') {
            return [
                ['name' => 'Bank Name', 'type' => 'text', 'value' => $this->payout_bank_name],
                ['name' => 'Account Holder', 'type' => 'text', 'value' => $this->payout_account_name],
                ['name' => 'Account Number', 'type' => 'text', 'value' => $this->payout_account_number],
                ['name' => 'Routing Number', 'type' => 'text', 'value' => $this->payout_routing_number],
            ];
        }

        return [];
    }

}
