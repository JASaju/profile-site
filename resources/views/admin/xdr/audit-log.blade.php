<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Log | XDR</title>
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
            <a href="{{ route('admin.xdr.requests') }}" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">⌁ &nbsp; Request activity</a>
           
            <a href="{{ route('admin.xdr.audit-log') }}" class="{{ $nav }} bg-cyan-400/10 text-cyan-200 ring-1 ring-cyan-400/20">▤ &nbsp; Audit log</a>
        </nav>
        <div class="mt-4 border-t border-slate-800 pt-4 text-sm lg:mt-auto">
            <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-white">← Back to admin</a>
        </div>
    </aside>
    <main class="min-w-0 flex-1 p-4 sm:p-6 lg:ml-64 lg:p-9">
        <div class="mx-auto max-w-7xl">
            <header class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <a href="{{ route('admin.xdr') }}" class="text-xs text-cyan-300 hover:text-cyan-200">← Security overview</a>
                    <h1 class="mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">Audit log</h1>
                    <p class="mt-1 text-sm text-slate-400">Authentication outcomes and profile/content changes made through the admin panel.</p>
                </div>
                <span class="self-start rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1.5 text-xs font-medium text-cyan-200 sm:self-auto">ADMIN ACTIVITY</span>
            </header>

            @unless($hasAuditLogTable)
                <div role="status" class="mt-6 rounded-xl border border-amber-400/20 bg-amber-400/[0.06] p-4 text-sm text-amber-100">
                    <p class="font-semibold">Audit logging is not set up yet.</p>
                    <p class="mt-1 text-amber-100/70">Run <code class="rounded bg-slate-950 px-1.5 py-0.5 font-mono text-amber-200">php artisan migrate</code> to create the audit log table. Admin actions will continue normally and will not be recorded until then.</p>
                </div>
            @endunless

            <section class="{{ $card }} mt-6 overflow-hidden">
                <form method="GET" class="grid gap-3 border-b border-slate-800 p-4 sm:grid-cols-[1fr_170px_220px_auto]">
                    <label class="sr-only" for="q">Search audit log</label>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="Search IP, path, action, or description" class="min-w-0 rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-600 focus:border-cyan-400 focus:outline-none">
                    <label class="sr-only" for="severity">Severity</label>
                    <select id="severity" name="severity" class="rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-300 focus:border-cyan-400 focus:outline-none">
                        <option value="">All severities</option>
                        @foreach(['info' => 'Info', 'warning' => 'Warning', 'critical' => 'Critical'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('severity') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <label class="sr-only" for="action">Action</label>
                    <select id="action" name="action" class="rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-300 focus:border-cyan-400 focus:outline-none">
                        <option value="">All actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-lg bg-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-cyan-300">Filter</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1100px] text-left text-sm">
                        <thead class="bg-slate-950/60 text-xs uppercase tracking-wide text-slate-500">
                            <tr><th class="px-5 py-3 font-medium">Timestamp</th><th class="px-4 py-3 font-medium">Severity</th><th class="px-4 py-3 font-medium">Actor</th><th class="px-4 py-3 font-medium">Action</th><th class="px-4 py-3 font-medium">Source / request</th><th class="px-4 py-3 font-medium">Approx. location</th><th class="px-5 py-3 font-medium">Changes</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                        @forelse($auditLogs as $entry)
                            <tr class="align-top hover:bg-white/[0.02]">
                                <td class="whitespace-nowrap px-5 py-4 text-xs text-slate-400">{{ $entry->created_at->timezone('Asia/Dhaka')->format('Y-m-d H:i:s') }}</td>
                                <td class="px-4 py-4"><span class="rounded-md px-2 py-1 text-[10px] font-semibold uppercase {{ $entry->severity === 'critical' ? 'bg-rose-400/10 text-rose-300' : ($entry->severity === 'warning' ? 'bg-amber-400/10 text-amber-300' : 'bg-emerald-400/10 text-emerald-300') }}">{{ $entry->severity }}</span></td>
                                <td class="px-4 py-4 text-xs text-slate-300">{{ $entry->user?->name ?? 'Unauthenticated' }}</td>
                                <td class="max-w-64 px-4 py-4"><div class="font-mono text-xs text-cyan-200">{{ $entry->action }}</div><p class="mt-1 text-xs leading-5 text-slate-500">{{ $entry->description }}</p></td>
                                <td class="max-w-64 px-4 py-4"><div class="font-mono text-xs text-slate-300">{{ $entry->ip_address ?: 'unknown' }}</div><div class="mt-1 break-all font-mono text-[10px] text-slate-500">{{ $entry->method }} {{ $entry->path }}</div></td>
                                <td class="px-4 py-4 text-xs">
                                    @php($geoService = app(\App\Services\IpGeolocationService::class))
                                    <div class="text-slate-300">
                                        @if($geoService->isNonPublicIp($entry->ip_address))
                                            Local/private IP — no public location
                                        @else
                                            {{ collect([$entry->city, $entry->region, $entry->country])->filter()->implode(', ') ?: 'Unavailable' }}
                                        @endif
                                    </div>
                                    @if($geoService->isPublicIp($entry->ip_address) && $entry->latitude !== null && $entry->longitude !== null)
                                        <a href="https://www.google.com/maps?q={{ $entry->latitude }},{{ $entry->longitude }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-block text-cyan-300 hover:text-cyan-200">Approximate map ↗</a>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if($entry->old_values || $entry->new_values)
                                        <details class="max-w-xs">
                                            <summary class="cursor-pointer text-xs text-cyan-300 hover:text-cyan-200">View values</summary>
                                            <div class="mt-2 space-y-2">
                                                @if($entry->old_values)<div><span class="text-[10px] font-semibold uppercase text-slate-500">Before</span><pre class="mt-1 whitespace-pre-wrap break-all rounded bg-slate-950 p-2 text-[10px] text-slate-300">{{ json_encode($entry->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre></div>@endif
                                                @if($entry->new_values)<div><span class="text-[10px] font-semibold uppercase text-slate-500">After</span><pre class="mt-1 whitespace-pre-wrap break-all rounded bg-slate-950 p-2 text-[10px] text-slate-300">{{ json_encode($entry->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre></div>@endif
                                            </div>
                                        </details>
                                    @else
                                        <span class="text-xs text-slate-600">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-5 py-14 text-center text-sm text-slate-500">{{ $hasAuditLogTable ? 'No audit events match the selected filters.' : 'Audit events will appear here after the audit table is created.' }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($auditLogs->hasPages())
                    <div class="border-t border-slate-800 px-5 py-4 text-sm text-slate-400">{{ $auditLogs->links() }}</div>
                @endif
            </section>

            <p class="mt-4 text-xs leading-5 text-slate-600">Locations are approximate IP-based estimates, not device or street addresses. Public visitor locations appear only when the site is accessed publicly, geolocation is enabled with <code>IP_GEOLOCATION_ENABLED=true</code>, and trusted proxies are configured correctly. Enabling lookup sends public IPs server-side to the configured provider; results are cached. Restrict database access, publish an appropriate privacy notice, and define a retention/deletion schedule for audit data.</p>
        </div>
    </main>
</div>
</body>
</html>
