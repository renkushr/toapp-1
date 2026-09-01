<?php

namespace App\Support;

class UsZipCatalog
{
    private static ?array $data = null;

    public static function path(): string
    {
        return database_path('data/us_zips_by_state.json');
    }

    public static function all(): array
    {
        if (self::$data !== null) {
            return self::$data;
        }

        $path = self::path();

        if (!is_file($path)) {
            return self::$data = [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return self::$data = is_array($decoded) ? $decoded : [];
    }

    /**
     * @return list<array{abbr:string,name:string,count:int}>
     */
    public static function states(?string $query = null): array
    {
        $query = trim((string) $query);
        $needle = strtolower($query);
        $rows = [];

        foreach (self::all() as $abbr => $info) {
            $name = (string) ($info['name'] ?? $abbr);
            $count = count($info['zips'] ?? []);

            if ($needle !== '') {
                $hay = strtolower($abbr . ' ' . $name);
                if (!str_contains($hay, $needle)) {
                    continue;
                }
            }

            $rows[] = [
                'abbr' => $abbr,
                'name' => $name,
                'count' => $count,
            ];
        }

        usort($rows, fn ($a, $b) => strcmp($a['name'], $b['name']));

        return $rows;
    }

    /**
     * @return list<array{zip:string,city:string}>
     */
    public static function zipsForState(string $state, ?string $query = null): array
    {
        $state = strtoupper(trim($state));
        $info = self::all()[$state] ?? null;

        if (!$info) {
            return [];
        }

        $needle = strtolower(trim((string) $query));
        $rows = [];

        foreach ($info['zips'] ?? [] as $pair) {
            $zip = (string) ($pair[0] ?? '');
            $city = (string) ($pair[1] ?? '');

            if ($zip === '') {
                continue;
            }

            if ($needle !== '') {
                $hay = strtolower($zip . ' ' . $city);
                if (!str_contains($hay, $needle)) {
                    continue;
                }
            }

            $rows[] = [
                'zip' => $zip,
                'city' => $city,
            ];
        }

        return $rows;
    }

    /**
     * @return list<string>
     */
    public static function zipCodesForState(string $state): array
    {
        return array_values(array_unique(array_column(self::zipsForState($state), 'zip')));
    }

    public static function stateName(string $abbr): string
    {
        $abbr = strtoupper(trim($abbr));
        if ($abbr === 'OTHER') {
            return 'Other / unknown';
        }

        return (string) (self::all()[$abbr]['name'] ?? $abbr);
    }

    public static function stateForZip(string $zip): ?string
    {
        $zip = UsZip::normalize($zip);

        if (!$zip) {
            return null;
        }

        static $lookup = null;

        if ($lookup === null) {
            $lookup = [];
            foreach (self::all() as $abbr => $info) {
                foreach ($info['zips'] ?? [] as $pair) {
                    $code = (string) ($pair[0] ?? '');
                    if ($code !== '' && !isset($lookup[$code])) {
                        $lookup[$code] = $abbr;
                    }
                }
            }
        }

        return $lookup[$zip] ?? null;
    }

    /**
     * Map a list of ZIPs to the states they belong to (for edit prefill).
     *
     * @param  list<string>  $zips
     * @return array<string, list<string>>
     */
    public static function groupZipsByState(array $zips): array
    {
        $grouped = [];

        foreach ($zips as $zip) {
            $normalized = UsZip::normalize((string) $zip);
            if (!$normalized) {
                continue;
            }

            $abbr = self::stateForZip($normalized) ?? 'OTHER';
            $grouped[$abbr][] = $normalized;
        }

        foreach ($grouped as $abbr => $list) {
            $grouped[$abbr] = array_values(array_unique($list));
            sort($grouped[$abbr]);
        }

        ksort($grouped);

        return $grouped;
    }

    /**
     * Compact map of state abbr => list of ZIP codes (no cities).
     *
     * @return array{by_state: array<string, list<string>>, total: int}
     */
    public static function allZipCodesByState(): array
    {
        $byState = [];
        $total = 0;

        foreach (self::all() as $abbr => $info) {
            $codes = [];
            foreach ($info['zips'] ?? [] as $pair) {
                $zip = (string) ($pair[0] ?? '');
                if ($zip === '') {
                    continue;
                }
                $codes[] = $zip;
            }
            if ($codes === []) {
                continue;
            }
            $byState[$abbr] = $codes;
            $total += count($codes);
        }

        ksort($byState);

        return [
            'by_state' => $byState,
            'total' => $total,
        ];
    }

    /**
     * @return list<string>
     */
    public static function allZipCodes(): array
    {
        $codes = [];
        foreach (self::allZipCodesByState()['by_state'] as $list) {
            foreach ($list as $zip) {
                $codes[$zip] = $zip;
            }
        }

        return array_values($codes);
    }
}
