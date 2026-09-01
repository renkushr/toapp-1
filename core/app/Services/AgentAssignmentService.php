<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\AgentZipCode;
use App\Support\UsZip;
use Illuminate\Validation\ValidationException;

class AgentAssignmentService
{
    public function resolve(string $licenseNo, string $zipCode): Agent
    {
        $normalizedZip = UsZip::normalize($zipCode);

        if (!$normalizedZip) {
            throw ValidationException::withMessages([
                'zipcode' => 'Enter a valid US ZIP code (5 digits).',
            ]);
        }

        $agent = Agent::query()
            ->whereRaw('UPPER(license_no) = ?', [strtoupper(trim($licenseNo))])
            ->where('status', true)
            ->first();

        if (!$agent) {
            throw ValidationException::withMessages([
                'agent_license_no' => 'No active agent found for this license number.',
            ]);
        }

        $coversZip = $agent->zipCodes()
            ->where('zip_code', $normalizedZip)
            ->exists();

        if (!$coversZip) {
            throw ValidationException::withMessages([
                'zipcode' => 'This ZIP code is not covered by the agent with that license number.',
            ]);
        }

        return $agent;
    }

    /**
     * Assign by license+ZIP code when provided, otherwise try ZIP code-only coverage.
     * Returns null when no agent can be matched.
     */
    public function resolveOptional(?string $licenseNo, string $zipCode): ?Agent
    {
        $normalizedZip = UsZip::normalize($zipCode);

        if (!$normalizedZip) {
            throw ValidationException::withMessages([
                'zipcode' => 'Enter a valid US ZIP code (5 digits).',
            ]);
        }

        $licenseNo = strtoupper(trim((string) $licenseNo));

        if ($licenseNo !== '') {
            return $this->resolve($licenseNo, $normalizedZip);
        }

        $zipRow = AgentZipCode::query()
            ->where('zip_code', $normalizedZip)
            ->whereHas('agent', fn ($query) => $query->where('status', true))
            ->with('agent')
            ->first();

        return $zipRow?->agent;
    }

    /**
     * @param  string|array<int, string>  $input
     * @return list<string>
     */
    public function parseZipCodes(string|array $input): array
    {
        if (is_array($input)) {
            $parts = $input;
        } else {
            $parts = preg_split('/[\s,;]+/', trim($input)) ?: [];
        }

        $normalized = [];

        foreach ($parts as $part) {
            $zip = UsZip::normalize((string) $part);
            if ($zip) {
                $normalized[] = $zip;
            }
        }

        return array_values(array_unique($normalized));
    }
}
