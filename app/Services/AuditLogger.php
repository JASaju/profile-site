<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuditLogger
{
    public function record(
        Request $request,
        string $action,
        string $description,
        string $severity = 'info',
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): void {
        if (! Schema::hasTable((new AuditLog)->getTable())) {
            return;
        }

        $ip = $request->ip();
        $location = app(IpGeolocationService::class)->forTable('audit_logs', $ip) ?? [];

        AuditLog::create(array_merge([
            'user_id' => $userId ?? Auth::id(),
            'ip_address' => $ip,
            'method' => Str::limit($request->method(), 10, ''),
            'path' => Str::limit('/'.ltrim($request->path(), '/'), 2048, ''),
            'action' => Str::limit($action, 100, ''),
            'description' => Str::limit($description, 500, ''),
            'old_values' => $this->sanitizeValues($oldValues),
            'new_values' => $this->sanitizeValues($newValues),
            'severity' => $severity,
        ], $location));
    }

    private function sanitizeValues(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        foreach (array_keys($values) as $key) {
            if (preg_match('/password|token|secret|image|photo|binary|data/i', $key)) {
                unset($values[$key]);
            }
        }

        return $values;
    }
}
