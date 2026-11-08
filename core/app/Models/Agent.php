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
     * Coverage grouped by state for agent UI (states first; ZIP numbers on expand).
     *
     * @return array{is_all_usa:bool,total_zips:int,state_count:int,states:list<array{abbr:string,name:string,count:int,zips:list<string>}>}
     */
    public function zipCoverageByState(): array
    {
        $catalog = \App\Support\UsZipCatalog::allZipCodesByState();
        $catalogTotal = (int) ($catalog['total'] ?? 0);
        $total = array_key_exists('zip_codes_count', $this->getAttributes())
            ? (int) $this->zip_codes_count
            : (int) $this->zipCodes()->count();

        if ($catalogTotal > 0 && $total >= $catalogTotal) {
            $states = [];
            foreach ($catalog['by_state'] as $abbr => $zips) {
                $states[] = [
                    'abbr' => $abbr,
                    'name' => \App\Support\UsZipCatalog::stateName($abbr),
                    'count' => count($zips),
                    'zips' => [],
                ];
            }
            usort($states, fn ($a, $b) => strcmp($a['name'], $b['name']));

            return [
                'is_all_usa' => true,
                'total_zips' => $total,
                'state_count' => count($states),
                'states' => $states,
            ];
        }

        $codes = $this->relationLoaded('zipCodes')
            ? $this->zipCodes->pluck('zip_code')->map(fn ($zip) => (string) $zip)->all()
            : $this->zipCodes()->orderBy('zip_code')->pluck('zip_code')->map(fn ($zip) => (string) $zip)->all();

        $grouped = \App\Support\UsZipCatalog::groupZipsByState($codes);
        $states = [];
        foreach ($grouped as $abbr => $zips) {
            $states[] = [
                'abbr' => $abbr,
                'name' => \App\Support\UsZipCatalog::stateName($abbr),
                'count' => count($zips),
                'zips' => $zips,
            ];
        }

        usort($states, fn ($a, $b) => strcmp($a['name'], $b['name']));

        return [
            'is_all_usa' => false,
            'total_zips' => $total,
            'state_count' => count($states),
            'states' => $states,
        ];
    }

    /**
     * Compact coverage label for admin tables (state-first, never dump thousands of ZIPs).
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

        $coverage = $this->zipCoverageByState();
        $names = array_column($coverage['states'], 'name');

        if ($names === []) {
            return number_format($count) . ' ZIP codes';
        }

        if (count($names) <= 4) {
            return implode(', ', $names) . ' (' . number_format($count) . ' ZIP)';
        }

        return number_format(count($names)) . ' states (' . number_format($count) . ' ZIP codes)';
    }
}
