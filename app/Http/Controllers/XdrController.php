<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\RequestActivity;
use App\Models\SecurityBenefit;
use App\Services\SecurityBenefitsService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class XdrController extends Controller
{
    public function auditLog(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'severity' => ['nullable', 'in:info,warning,critical'],
            'action' => ['nullable', 'string', 'max:100'],
        ]);
        $hasAuditLogTable = Schema::hasTable((new AuditLog)->getTable());
        $query = $hasAuditLogTable ? AuditLog::with('user')->latest() : null;

        if ($query && ! empty($filters['q'])) {
            $term = $filters['q'];
            $query->where(function ($builder) use ($term) {
                $builder->where('ip_address', 'like', '%'.$term.'%')
                    ->orWhere('path', 'like', '%'.$term.'%')
                    ->orWhere('action', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%');
            });
        }

        if ($query && ! empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }

        if ($query && ! empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        return view('admin.xdr.audit-log', [
            'hasAuditLogTable' => $hasAuditLogTable,
            'auditLogs' => $query
                ? $query->paginate(25)->withQueryString()
                : new LengthAwarePaginator([], 0, 25, LengthAwarePaginator::resolveCurrentPage(), [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]),
            'actions' => $hasAuditLogTable
                ? AuditLog::query()->distinct()->orderBy('action')->pluck('action')
                : collect(),
        ]);
    }

    public function benefits(SecurityBenefitsService $securityBenefits)
    {
        $this->authorize('viewAny', SecurityBenefit::class);

        return view('admin.xdr.benefits', $securityBenefits->adminPageData());
    }

    public function dashboard()
    {
        $today = now()->startOfDay();
        $lastDay = now()->subDay();
        $hasRequestActivityTable = Schema::hasTable((new RequestActivity)->getTable());

        return view('admin.xdr.dashboard', [
            'hasRequestActivityTable' => $hasRequestActivityTable,
            'requestsToday' => $hasRequestActivityTable
                ? RequestActivity::where('created_at', '>=', $today)->count()
                : 0,
            'uniqueIps' => $hasRequestActivityTable
                ? RequestActivity::where('created_at', '>=', $lastDay)
                    ->whereNotNull('ip_address')
                    ->distinct('ip_address')
                    ->count('ip_address')
                : 0,
            'errorRequests' => $hasRequestActivityTable
                ? RequestActivity::where('created_at', '>=', $lastDay)
                    ->where('status_code', '>=', 400)
                    ->count()
                : 0,
            'recentRequests' => $hasRequestActivityTable
                ? RequestActivity::latest()->limit(8)->get()
                : collect(),
            'alerts' => [
                [
                    'severity' => 'Critical',
                    'title' => 'Repeated authentication failures',
                    'detail' => 'Multiple failed sign-in attempts detected',
                    'ip' => '198.51.100.24',
                    'time' => now()->subMinutes(4),
                ],
                [
                    'severity' => 'High',
                    'title' => 'Unusual request pattern',
                    'detail' => 'Automated path discovery pattern flagged',
                    'ip' => '203.0.113.17',
                    'time' => now()->subMinutes(18),
                ],
                [
                    'severity' => 'Medium',
                    'title' => 'Web policy event',
                    'detail' => 'Request returned a client error response',
                    'ip' => '192.0.2.42',
                    'time' => now()->subMinutes(36),
                ],
            ],
            'agents' => [
                ['name' => 'PROFILE-WEB-01', 'platform' => 'Laravel web node', 'state' => 'Online'],
                ['name' => 'PROFILE-DB-01', 'platform' => 'Database node', 'state' => 'Online'],
                ['name' => 'EDGE-GATEWAY', 'platform' => 'Reverse proxy', 'state' => 'Online'],
            ],
        ]);
    }

    public function requests(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', 'between:100,599'],
        ]);
        $hasRequestActivityTable = Schema::hasTable((new RequestActivity)->getTable());
        $query = $hasRequestActivityTable ? RequestActivity::query()->latest() : null;

        if ($query && ! empty($filters['q'])) {
            $term = $filters['q'];
            $query->where(function ($builder) use ($term) {
                $builder->where('ip_address', 'like', '%'.$term.'%')
                    ->orWhere('path', 'like', '%'.$term.'%')
                    ->orWhere('method', 'like', '%'.$term.'%');
            });
        }

        if ($query && ! empty($filters['status'])) {
            $query->where('status_code', (int) $filters['status']);
        }

        return view('admin.xdr.requests', [
            'hasRequestActivityTable' => $hasRequestActivityTable,
            'requests' => $query
                ? $query->paginate(25)->withQueryString()
                : new LengthAwarePaginator([], 0, 25, LengthAwarePaginator::resolveCurrentPage(), [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]),
            'totalRequests' => $hasRequestActivityTable ? RequestActivity::count() : 0,
            'uniqueIps' => $hasRequestActivityTable
                ? RequestActivity::whereNotNull('ip_address')->distinct('ip_address')->count('ip_address')
                : 0,
        ]);
    }
}
