<?php

namespace Tests\Feature;

use App\Services\IpGeolocationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class IpGeolocationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.ip_geolocation.enabled' => true,
            'services.ip_geolocation.url' => 'https://ipwho.is/{ip}',
            'services.ip_geolocation.timeout' => 3,
            'services.ip_geolocation.cache_days' => 30,
            'services.ip_geolocation.requests_per_minute' => 30,
        ]);
        RateLimiter::clear('ip-geolocation-provider');
    }

    public function test_provider_response_is_normalized_and_cached(): void
    {
        $ip = '8.8.4.4';
        Cache::forget('ip-geolocation:'.hash('sha256', $ip));
        Http::fake([
            'https://ipwho.is/*' => Http::response([
                'success' => true,
                'country' => 'United States',
                'region' => 'California',
                'city' => 'Mountain View',
                'latitude' => 37.386,
                'longitude' => -122.0838,
            ]),
        ]);

        $service = app(IpGeolocationService::class);
        $first = $service->locate($ip);
        $second = $service->locate($ip);

        $this->assertSame([
            'country' => 'United States',
            'region' => 'California',
            'city' => 'Mountain View',
            'latitude' => 37.386,
            'longitude' => -122.0838,
        ], $first);
        $this->assertSame($first, $second);
        Http::assertSentCount(1);
    }

    public function test_private_and_loopback_ips_are_not_sent_to_provider(): void
    {
        Http::fake();
        $service = app(IpGeolocationService::class);

        $this->assertNull($service->locate('192.168.1.10'));
        $this->assertNull($service->locate('127.0.0.1'));
        $this->assertNull($service->locate('::1'));
        $this->assertNull($service->locate('203.0.113.8'));
        Http::assertNothingSent();
    }

    public function test_provider_errors_are_graceful_and_briefly_cached(): void
    {
        $ip = '1.1.1.1';
        Cache::forget('ip-geolocation:'.hash('sha256', $ip));
        Http::fake([
            'https://ipwho.is/*' => Http::response(['success' => false], 429),
        ]);

        $service = app(IpGeolocationService::class);

        $this->assertNull($service->locate($ip));
        $this->assertNull($service->locate($ip));
        Http::assertSentCount(1);
    }

    public function test_lookup_waits_until_geolocation_columns_are_migrated(): void
    {
        \Illuminate\Support\Facades\Schema::shouldReceive('hasColumns')
            ->once()
            ->with('request_activities', ['country', 'region', 'city', 'latitude', 'longitude'])
            ->andReturnFalse();
        Http::fake();

        $this->assertNull(app(IpGeolocationService::class)->forTable('request_activities', '8.8.8.8'));
        Http::assertNothingSent();
    }
}
