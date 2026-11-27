<?php

namespace Tests\Feature;

use App\Http\Controllers\XdrController;
use App\Models\RequestActivity;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LoginRequestActivityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.key' => 'base64:'.base64_encode(str_repeat('a', 32)),
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'services.ip_geolocation.enabled' => false,
            'trustedproxy.proxies' => ['10.0.0.10'],
        ]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        Schema::create('request_activities', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->string('method', 10);
            $table->string('path', 2048);
            $table->unsignedSmallInteger('status_code');
            $table->timestamps();
        });
    }

    public function test_guest_login_visit_records_trusted_client_ip_and_appears_in_activity_table(): void
    {
        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '10.0.0.10',
            'HTTP_X_FORWARDED_FOR' => '198.51.100.42',
        ])->get('/login');

        $response->assertOk();

        $activity = RequestActivity::query()->firstOrFail();
        $this->assertSame('198.51.100.42', $activity->ip_address);
        $this->assertSame('GET', $activity->method);
        $this->assertSame('/login', $activity->path);
        $this->assertSame(200, $activity->status_code);
        $this->assertNotNull($activity->created_at);

        $page = app(XdrController::class)->requests(Request::create('/admin/xdr/requests'))->render();
        $this->assertStringContainsString('198.51.100.42', $page);
        $this->assertStringContainsString('/login', $page);
        $this->assertStringContainsString('200', $page);
    }

    public function test_untrusted_forwarded_ip_is_not_used_for_request_activity(): void
    {
        $this->withServerVariables([
            'REMOTE_ADDR' => '198.51.100.24',
            'HTTP_X_FORWARDED_FOR' => '203.0.113.99',
        ])->get('/login')->assertOk();

        $this->assertSame('198.51.100.24', RequestActivity::query()->firstOrFail()->ip_address);
    }
}
