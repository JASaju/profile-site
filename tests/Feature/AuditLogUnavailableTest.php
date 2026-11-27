<?php

namespace Tests\Feature;

use App\Http\Controllers\XdrController;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditLogUnavailableTest extends TestCase
{
    public function test_audit_logger_skips_when_audit_table_is_missing(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('audit_logs')
            ->andReturnFalse();

        app(AuditLogger::class)->record(
            Request::create('/admin/profile', 'PUT'),
            'admin.profile.updated',
            'Profile details updated.'
        );
    }

    public function test_audit_page_displays_migration_notice_when_table_is_missing(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('audit_logs')
            ->andReturnFalse();

        $response = app(XdrController::class)->auditLog(Request::create('/admin/xdr/audit-log'));

        $this->assertStringContainsString('Audit logging is not set up yet', $response->render());
        $this->assertStringContainsString('php artisan migrate', $response->render());
    }
}
