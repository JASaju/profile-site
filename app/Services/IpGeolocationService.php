<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;

class IpGeolocationService
{
    private const NON_PUBLIC_CIDRS = [
        '192.0.2.0/24',
        '198.51.100.0/24',
        '203.0.113.0/24',
        '2001:db8::/32',
    ];

    public function forTable(string $table, ?string $ip): ?array
    {
        if (! config('services.ip_geolocation.enabled')
            || ! Schema::hasColumns($table, ['country', 'region', 'city', 'latitude', 'longitude'])) {
            return null;
        }

        return $this->locate($ip);
    }

    public function locate(?string $ip): ?array
    {
        if (! config('services.ip_geolocation.enabled') || ! $this->isPublicIp($ip)) {
            return null;
        }

        $cacheKey = 'ip-geolocation:'.hash('sha256', $ip);
        $cached = Cache::get($cacheKey);

        if (is_array($cached)) {
            return $cached['available'] ? $cached['location'] : null;
        }

        $result = RateLimiter::attempt(
            'ip-geolocation-provider',
            (int) config('services.ip_geolocation.requests_per_minute', 30),
            fn () => $this->fetchLocation($ip),
            60
        );

        if (! is_array($result)) {
            $result = ['available' => false, 'location' => null];
        }

        $cacheSeconds = $result['available']
            ? max(1, (int) config('services.ip_geolocation.cache_days', 30)) * 86400
            : 60;

        Cache::put($cacheKey, $result, $cacheSeconds);

        return $result['available'] ? $result['location'] : null;
    }

    public function isPublicIp(?string $ip): bool
    {
        if ($ip === null
            || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }

        foreach (self::NON_PUBLIC_CIDRS as $range) {
            if ($this->matchesCidr($ip, $range)) {
                return false;
            }
        }

        return true;
    }

    public function isNonPublicIp(?string $ip): bool
    {
        return $ip !== null && filter_var($ip, FILTER_VALIDATE_IP) !== false && ! $this->isPublicIp($ip);
    }

    private function matchesCidr(string $ip, string $range): bool
    {
        [$network, $prefix] = explode('/', $range, 2);
        $addressBytes = inet_pton($ip);
        $networkBytes = inet_pton($network);

        if ($addressBytes === false || $networkBytes === false || strlen($addressBytes) !== strlen($networkBytes)) {
            return false;
        }

        $prefix = (int) $prefix;
        $wholeBytes = intdiv($prefix, 8);
        $remainingBits = $prefix % 8;

        if (substr($addressBytes, 0, $wholeBytes) !== substr($networkBytes, 0, $wholeBytes)) {
            return false;
        }

        if ($remainingBits === 0) {
            return true;
        }

        $mask = (0xFF << (8 - $remainingBits)) & 0xFF;

        return (ord($addressBytes[$wholeBytes]) & $mask) === (ord($networkBytes[$wholeBytes]) & $mask);
    }

    private function fetchLocation(string $ip): array
    {
        $url = str_replace(
            '{ip}',
            rawurlencode($ip),
            (string) config('services.ip_geolocation.url')
        );

        try {
            $response = Http::acceptJson()
                ->timeout((int) config('services.ip_geolocation.timeout', 3))
                ->get($url);
        } catch (ConnectionException $exception) {
            Log::warning('IP geolocation provider connection failed.', [
                'exception' => $exception->getMessage(),
            ]);

            return ['available' => false, 'location' => null];
        }

        if (! $response->successful()) {
            Log::warning('IP geolocation provider returned an unsuccessful response.', [
                'status' => $response->status(),
            ]);

            return ['available' => false, 'location' => null];
        }

        $data = $response->json();
        if (! is_array($data) || ($data['success'] ?? false) !== true) {
            Log::warning('IP geolocation provider returned an invalid response.');

            return ['available' => false, 'location' => null];
        }

        $latitude = $this->coordinate($data['latitude'] ?? null, -90, 90);
        $longitude = $this->coordinate($data['longitude'] ?? null, -180, 180);
        $location = [
            'country' => $this->text($data['country'] ?? null),
            'region' => $this->text($data['region'] ?? null),
            'city' => $this->text($data['city'] ?? null),
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        if (! $location['country'] && ! $location['region'] && ! $location['city'] && $latitude === null) {
            return ['available' => false, 'location' => null];
        }

        return ['available' => true, 'location' => $location];
    }

    private function text(mixed $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return mb_substr(trim($value), 0, 100);
    }

    private function coordinate(mixed $value, float $minimum, float $maximum): ?float
    {
        if (! is_numeric($value)) {
            return null;
        }

        $coordinate = (float) $value;

        return $coordinate >= $minimum && $coordinate <= $maximum ? $coordinate : null;
    }
}
