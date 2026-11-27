<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthAuditLoggingTest extends TestCase
{
    public function test_failed_login_is_recorded_as_a_warning(): void
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'admin@example.com',
            'password' => 'incorrect',
        ]);
        $request->setLaravelSession(app('session')->driver());

        Auth::shouldReceive('attempt')->once()->andReturnFalse();
        $auditLogger = \Mockery::mock(AuditLogger::class);
        $auditLogger->shouldReceive('record')->once()->withArgs(
            fn (Request $loggedRequest, string $action, string $description, string $severity) => $loggedRequest === $request
                && $action === 'auth.login.failure'
                && $severity === 'warning'
        );
        $this->app->instance(AuditLogger::class, $auditLogger);

        app(AuthController::class)->login($request);
    }

    public function test_successful_login_is_recorded(): void
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'admin@example.com',
            'password' => 'correct',
        ]);
        $request->setLaravelSession(app('session')->driver());

        Auth::shouldReceive('attempt')->once()->andReturnTrue();
        $auditLogger = \Mockery::mock(AuditLogger::class);
        $auditLogger->shouldReceive('record')->once()->withArgs(
            fn (Request $loggedRequest, string $action, string $description, string $severity) => $loggedRequest === $request
                && $action === 'auth.login.success'
                && $severity === 'info'
        );
        $this->app->instance(AuditLogger::class, $auditLogger);

        app(AuthController::class)->login($request);
    }

    public function test_logout_is_recorded_with_the_user_id(): void
    {
        $request = Request::create('/logout', 'POST');
        $request->setLaravelSession(app('session')->driver());

        Auth::shouldReceive('id')->once()->andReturn(42);
        Auth::shouldReceive('logout')->once();
        $auditLogger = \Mockery::mock(AuditLogger::class);
        $auditLogger->shouldReceive('record')->once()->withArgs(
            fn (Request $loggedRequest, string $action, string $description, string $severity, ?array $oldValues, ?array $newValues, ?int $userId) => $loggedRequest === $request
                && $action === 'auth.logout'
                && $severity === 'info'
                && $userId === 42
        );
        $this->app->instance(AuditLogger::class, $auditLogger);

        app(AuthController::class)->logout($request);
    }
}
