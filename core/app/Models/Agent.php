<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends Authenticatable
{
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function zipCodes()
    {
        return $this->hasMany(AgentZipCode::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function conversations()
    {
        return $this->hasMany(AgentConversation::class);
    }

    public function canAccessUser(User $user): bool
    {
        return (int) $user->agent_id === (int) $this->id
            && filled($user->zipcode)
            && $this->coversZip($user->zipcode);
    }

    public function coversZip(string $zipCode): bool
    {
        $normalized = \App\Support\UsZip::normalize($zipCode);

        if (!$normalized) {
            return false;
        }

        return $this->zipCodes()->where('zip_code', $normalized)->exists();
    }

    public function feePercentLabel(): string
    {
        return rtrim(rtrim(number_format((float) $this->fee_percent, 2), '0'), '.') . '%';
    }

    public function zipCodeList(): string
    {
        return $this->zipCodes()
            ->orderBy('zip_code')
            ->pluck('zip_code')
            ->implode(', ');
    }

    /**
     * Compact coverage label for admin tables (never dump thousands of ZIPs).
     */
    public function zipCoverageSummary(?int $catalogTotal = null): string
    {
        $count = array_key_exists('zip_codes_count', $this->getAttributes())
            ? (int) $this->zip_codes_count
            : (int) $this->zipCodes()->count();

        if ($count <= 0) {
            return '—';
        }

        $catalogTotal = $catalogTotal ?? (int) (\App\Support\UsZipCatalog::allZipCodesByState()['total'] ?? 0);

        if ($catalogTotal > 0 && $count >= $catalogTotal) {
            return 'All USA (' . number_format($count) . ' ZIP codes)';
        }

        if ($count <= 8) {
            $codes = $this->relationLoaded('zipCodes')
                ? $this->zipCodes->sortBy('zip_code')->pluck('zip_code')->all()
                : $this->zipCodes()->orderBy('zip_code')->pluck('zip_code')->all();

            return implode(', ', $codes);
        }

        return number_format($count) . ' ZIP codes';
    }
}
