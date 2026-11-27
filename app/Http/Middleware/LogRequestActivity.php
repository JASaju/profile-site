<?php

namespace App\Http\Middleware;

use App\Models\RequestActivity;
use App\Services\IpGeolocationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class LogRequestActivity
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if ($request->is('media/*', 'file/*', 'robots.txt', 'sitemap.xml', 'google67ba2e4f33e282d3.html')) {
            return;
        }

        if (! Schema::hasTable((new RequestActivity)->getTable())) {
            return;
        }

        $ip = $request->ip();
        $location = app(IpGeolocationService::class)->forTable('request_activities', $ip) ?? [];

        RequestActivity::create(array_merge([
            'ip_address' => $ip,
            'method' => substr($request->method(), 0, 10),
            'path' => substr('/'.ltrim($request->path(), '/'), 0, 2048),
            'status_code' => $response->getStatusCode(),
        ], $location));
    }
}
