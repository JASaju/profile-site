<?php

namespace Tests\Feature;

use App\Http\Controllers\XdrController;
use App\Http\Middleware\LogRequestActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RequestActivityUnavailableTest extends TestCase
{
    public function test_request_logging_skips_cleanly_when_activity_table_is_missing(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('request_activities')
            ->andReturnFalse();

        $request = Request::create('/');
        $response = new Response('ok');
        $middleware = app(LogRequestActivity::class);

        $this->assertSame($response, $middleware->handle($request, fn () => $response));
        $middleware->terminate($request, $response);
    }

    public function test_request_page_shows_migration_instructions_when_activity_table_is_missing(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('request_activities')
            ->andReturnFalse();

        $response = app(XdrController::class)->requests(Request::create('/admin/xdr/requests'));

        $this->assertStringContainsString('php artisan migrate', $response->render());
    }

    public function test_overview_renders_when_activity_table_is_missing(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('request_activities')
            ->andReturnFalse();

        $response = app(XdrController::class)->dashboard();

        $this->assertStringContainsString('php artisan migrate', $response->render());
    }
}
