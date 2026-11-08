<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceApplication extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'chronic_disease' => 'boolean',
        'living_benefits' => 'boolean',
        'birthday' => 'date',
        'terms_accepted_at' => 'datetime',
        'chat_started_at' => 'datetime',
        'payment_confirmed_at' => 'datetime',
        'quoted_monthly' => 'decimal:2',
        'quoted_annual' => 'decimal:2',
        'payment_monthly' => 'decimal:2',
    ];

    public const STATUS_OTP_PENDING = 'otp_pending';
    public const STATUS_PROFILE_PENDING = 'profile_pending';
    public const STATUS_QUOTED = 'quoted';
    public const STATUS_PLAN_SELECTED = 'plan_selected';
    public const STATUS_TERMS_ACCEPTED = 'terms_accepted';
    public const STATUS_CHATTING = 'chatting';
    public const STATUS_PAYMENT_PENDING = 'payment_pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Start an additional package for a user, carrying over the customer's
     * underwriting conditions from their latest application.
     */
    public static function startPackageFor(User $user, ?int $agentId = null): self
    {
        $latest = static::where('user_id', $user->id)->latest('id')->first();
        $portalId = $latest?->third_party_portal_id
            ?? ThirdPartyPortal::findBySlug('check-insure')?->id
            ?? ThirdPartyPortal::query()->where('status', true)->value('id');
        abort_unless($portalId, 503, 'Insurance portal is not configured yet.');

        $agentId = $agentId ?: $user->agent_id;

        return static::create([
            'third_party_portal_id' => $portalId,
            'user_id' => $user->id,
            'agent_id' => $agentId,
            'reference' => 'TA-' . strtoupper(substr(md5($user->id . microtime()), 0, 10)),
            'status' => $agentId ? self::STATUS_CHATTING : self::STATUS_TERMS_ACCEPTED,
            'sex' => $latest?->sex ?? $user->sex,
            'birthday' => $latest?->birthday ?? $user->birthday,
            'zipcode' => $latest?->zipcode ?? $user->zipcode,
            'health' => $latest?->health ?? $user->health,
            'smoking' => $latest?->smoking ?? $user->nicotin,
            'chronic_disease' => (bool) ($latest?->chronic_disease ?? $user->chronic_disease),
            'pay_type' => $latest?->pay_type ?? 'year',
            'price_range' => $latest?->price_range ?? 1,
            'terms_accepted_at' => now(),
            'chat_started_at' => $agentId ? now() : null,
        ]);
    }

    public function portal()
    {
        return $this->belongsTo(ThirdPartyPortal::class, 'third_party_portal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function confirmedByAgent()
    {
        return $this->belongsTo(Agent::class, 'confirmed_by_agent_id');
    }

    public function confirmedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'confirmed_by_admin_id');
    }

    public function scopeForAgentTerritory($query, Agent $agent)
    {
        return $query
            ->where('agent_id', $agent->id)
            ->whereHas('user', fn ($user) => $user->forAgentTerritory($agent));
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_OTP_PENDING => 'Awaiting email OTP',
            self::STATUS_PROFILE_PENDING => 'Profile incomplete',
            self::STATUS_QUOTED => 'Viewing quotes',
            self::STATUS_PLAN_SELECTED => 'Plan selected',
            self::STATUS_TERMS_ACCEPTED => 'Terms accepted',
            self::STATUS_CHATTING => 'Chatting with agent',
            self::STATUS_PAYMENT_PENDING => 'Awaiting payment confirmation',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_REJECTED => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function canUserEditConditions(): bool
    {
        return ! in_array($this->status, [
            self::STATUS_CONFIRMED,
            self::STATUS_REJECTED,
            self::STATUS_OTP_PENDING,
            self::STATUS_PROFILE_PENDING,
        ], true);
    }

    public static function generateReference(): string
    {
        return 'INS-' . strtoupper(bin2hex(random_bytes(4)));
    }
}
