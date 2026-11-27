<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Activity | XDR</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
@php
    $card = 'rounded-2xl border border-slate-800 bg-slate-900/80';
    $nav = 'rounded-xl px-3 py-2.5 text-sm transition';
@endphp
<div class="min-h-screen lg:flex">
    <aside class="border-b border-slate-800 bg-slate-950 p-4 lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col lg:border-b-0 lg:border-r">
        <a href="{{ route('admin.xdr') }}" class="flex items-center gap-3 px-2 py-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-300 ring-1 ring-cyan-400/20">◈</span>
            <span><span class="block text-sm font-semibold tracking-wide"></span><span class="text-xs text-slate-500">security console</span></span>
        </a>
        <nav class="mt-7 flex gap-2 lg:mt-10 lg:flex-col">
            <a href="{{ route('admin.xdr') }}" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">◈ &nbsp; Overview</a>
            <a href="{{ route('admin.xdr.requests') }}" class="{{ $nav }} bg-cyan-400/10 text-cyan-200 ring-1 ring-cyan-400/20">⌁ &nbsp; Request activity</a>
          
            <a href="{{ route('admin.xdr.audit-log') }}" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">▤ &nbsp; Audit log</a>
        </nav>
        <a href="{{ route('admin.dashboard') }}" class="mt-4 border-t border-slate-800 pt-4 text-sm text-slate-400 hover:text-white lg:mt-auto">← Back to admin</a>
    </aside>
    <main class="min-w-0 flex-1 p-4 sm:p-6 lg:ml-64 lg:p-9">
        <div class="mx-auto max-w-7xl">
            <header class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div><a href="{{ route('admin.xdr') }}" class="text-xs text-cyan-300 hover:text-cyan-200">← Security overview</a><h1 class="mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">Request activity</h1><p class="mt-1 text-sm text-slate-400">Incoming requests with source IP, path, method, response status and timestamp.</p></div>
                <div class="flex gap-3 text-xs text-slate-400"><span class="rounded-lg border border-slate-800 bg-slate-900 px-3 py-2">{{ number_format($totalRequests) }} requests</span><span class="rounded-lg border border-slate-800 bg-slate-900 px-3 py-2">{{ number_format($uniqueIps) }} unique IPs</span></div>
            </header>
            @unless($hasRequestActivityTable)
                <div role="status" class="mt-6 rounded-xl border border-amber-400/20 bg-amber-400/[0.06] p-4 text-sm text-amber-100">
                    <p class="font-semibold">Request activity is not set up yet.</p>
                    <p class="mt-1 text-amber-100/70">Run <code class="rounded bg-slate-950 px-1.5 py-0.5 font-mono text-amber-200">php artisan migrate</code> to create its database table. Site requests will continue normally and will not be recorded until then.</p>
                </div>
            @endunless
            <section class="{{ $card }} mt-6 overflow-hidden">
                <form method="GET" class="flex flex-col gap-3 border-b border-slate-800 p-4 sm:flex-row">
                    <label class="sr-only" for="q">Filter IP, path, or method</label>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="Filter by IP, path, or method" class="min-w-0 flex-1 rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-600 focus:border-cyan-400 focus:outline-none">
                    <label class="sr-only" for="status">HTTP status</label>
                    <select id="status" name="status" class="rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-300 focus:border-cyan-400 focus:outline-none">
                        <option value="">All statuses</option>
                        @foreach([200, 301, 302, 401, 403, 404, 422, 429, 500] as $status)
                            <option value="{{ $status }}" @selected(request('status') == $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-lg bg-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-cyan-300">Filter</button>
                </form>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[980px] text-left text-sm">
                        <thead class="bg-slate-950/60 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 font-medium">Timestamp</th><th class="px-5 py-3 font-medium">Source IP</th><th class="px-5 py-3 font-medium">Approx. location</th><th class="px-5 py-3 font-medium">Method</th><th class="px-5 py-3 font-medium">Request path</th><th class="px-5 py-3 font-medium">Status</th></tr></thead>
                        <tbody class="divide-y divide-slate-800/80">
                        @forelse($requests as $activity)
                            <tr class="hover:bg-white/[0.02]">
                                <td class="whitespace-nowrap px-5 py-3 text-xs text-slate-400">{{ $activity->created_at->timezone('Asia/Dhaka')->format('Y-m-d H:i:s') }}</td>
                                <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-cyan-200">{{ $activity->ip_address ?: 'unknown' }}</td>
                                <td class="px-5 py-3 text-xs">
                                    @php($geoService = app(\App\Services\IpGeolocationService::class))
                                    <div class="text-slate-300">
                                        @if($geoService->isNonPublicIp($activity->ip_address))
                                            Local/private IP — no public location
                                        @else
                                            {{ collect([$activity->city, $activity->region, $activity->country])->filter()->implode(', ') ?: 'Unavailable' }}
                                        @endif
                                    </div>
                                    @if($geoService->isPublicIp($activity->ip_address) && $activity->latitude !== null && $activity->longitude !== null)
                                        <a href="https://www.google.com/maps?q={{ $activity->latitude }},{{ $activity->longitude }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-block text-cyan-300 hover:text-cyan-200">Approximate map ↗</a>
                                    @endif
                                </td>
                                <td class="px-5 py-3"><span class="rounded bg-slate-800 px-2 py-1 font-mono text-[10px] text-slate-300">{{ $activity->method }}</span></td>
                                <td class="max-w-lg break-all px-5 py-3 font-mono text-xs text-slate-300">/{{ ltrim($activity->path, '/') }}</td>
                                <td class="px-5 py-3"><span class="rounded-md px-2 py-1 text-xs {{ $activity->status_code >= 400 ? 'bg-rose-400/10 text-rose-300' : 'bg-emerald-400/10 text-emerald-300' }}">{{ $activity->status_code }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-14 text-center text-sm text-slate-500">{{ $hasRequestActivityTable ? 'No request records match. Activity will appear after new site requests.' : 'Request records will appear here after the activity table is created.' }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($requests->hasPages())
                <div class="border-t border-slate-800 px-5 py-4 text-sm text-slate-400">{{ $requests->links() }}</div>
                @endif
            </section>
            <p class="mt-4 text-xs leading-5 text-slate-600">Locations are approximate IP-based estimates, not device or street addresses. Public visitor locations appear only when the site is accessed publicly, geolocation is enabled with <code>IP_GEOLOCATION_ENABLED=true</code>, and trusted proxies are configured correctly. When enabled, public IPs are sent server-side to the configured provider and cached. Query strings, request bodies and user-agent values are not stored. Review your privacy notice and retention requirements before enabling this in production.</p>
        </div>
    </main>
</div>
</body>
</html>
