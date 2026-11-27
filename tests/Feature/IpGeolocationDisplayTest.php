<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\RequestActivity;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class IpGeolocationDisplayTest extends TestCase
{
    public function test_private_ip_is_labeled_without_fabricating_a_map_location(): void
    {
        $activity = new RequestActivity([
            'ip_address' => '127.0.0.1',
            'method' => 'GET',
            'path' => '/',
            'status_code' => 200,
        ]);
        $activity->setAttribute('created_at', now());

        $requestHtml = view('admin.xdr.requests', [
            'hasRequestActivityTable' => true,
            'requests' => new LengthAwarePaginator([$activity], 1, 25, 1, ['path' => '/admin/xdr/requests']),
            'totalRequests' => 1,
            'uniqueIps' => 1,
        ])->render();

        $entry = new AuditLog([
            'ip_address' => '10.0.0.12',
            'method' => 'POST',
            'path' => '/login',
            'action' => 'auth.login.failure',
            'description' => 'A sign-in attempt failed.',
            'severity' => 'warning',
        ]);
        $entry->setAttribute('created_at', now());

        $auditHtml = view('admin.xdr.audit-log', [
            'hasAuditLogTable' => true,
            'auditLogs' => new LengthAwarePaginator([$entry], 1, 25, 1, ['path' => '/admin/xdr/audit-log']),
            'actions' => collect(['auth.login.failure']),
        ])->render();

        $this->assertStringContainsString('Local/private IP — no public location', $requestHtml);
        $this->assertStringContainsString('Local/private IP — no public location', $auditHtml);
        $this->assertStringNotContainsString('Approximate map', $requestHtml);
        $this->assertStringNotContainsString('Approximate map', $auditHtml);
        $this->assertStringContainsString('trusted proxies are configured correctly', $requestHtml);
        $this->assertStringContainsString('trusted proxies are configured correctly', $auditHtml);
    }
}
