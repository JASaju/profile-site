<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Command Center | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
@php
    $nav = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition';
    $muted = 'text-slate-400';
    $card = 'rounded-2xl border border-slate-800 bg-slate-900/80';
@endphp
<div class="min-h-screen lg:flex">
    <aside class="border-b border-slate-800 bg-slate-950 p-4 lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col lg:border-b-0 lg:border-r">
        <a href="{{ route('admin.xdr') }}" class="flex items-center gap-3 px-2 py-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-300 ring-1 ring-cyan-400/20">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 19 6v5c0 4.8-3 8-7 10-4-2-7-5.2-7-10V6l7-3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
            </span>
           
        </a>
        <div class="mt-7 flex gap-2 lg:mt-10 lg:flex-col">
            <a href="{{ route('admin.xdr') }}" class="{{ $nav }} bg-cyan-400/10 text-cyan-200 ring-1 ring-cyan-400/20">
                <span>◈</span> Overview
            </a>
            <a href="{{ route('admin.xdr.requests') }}" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">
                <span>⌁</span> Request activity
            </a>
           
            <a href="{{ route('admin.xdr.audit-log') }}" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">
                <span>▤</span> Audit log
            </a>
            <a href="#agents" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">
                <span>▣</span> agents
            </a>
            <a href="#alerts" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">
                <span>⚑</span> Threat alerts
            </a>
        </div>
        <div class="mt-6 hidden rounded-xl border border-slate-800 bg-slate-900 p-4 lg:mt-auto lg:block">
            <div class="flex items-center gap-2 text-xs font-medium text-amber-300"><span class="h-2 w-2 rounded-full bg-amber-400"></span> </div>
            
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-800 pt-4 text-sm lg:mt-5">
            <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-white">← Admin</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-slate-400 hover:text-white">Sign out</button></form>
        </div>
    </aside>

    <main class="min-w-0 flex-1 p-4 sm:p-6 lg:ml-64 lg:p-9">
        <div class="mx-auto max-w-7xl">
            <header class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                                <div class="flex items-center gap-2 self-start rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1.5 text-xs font-medium text-amber-200 sm:self-auto">
                    
            </header>

            <section class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="{{ $card }} p-5">
                    <div class="flex items-center justify-between text-sm text-slate-400"><span>Requests today</span><span class="text-cyan-300">↗</span></div>
                    <div class="mt-4 text-3xl font-semibold">{{ number_format($requestsToday) }}</div>
                    <div class="mt-2 text-xs text-slate-500">Live site traffic</div>
                </article>
                <article class="{{ $card }} p-5">
                    <div class="flex items-center justify-between text-sm text-slate-400"><span>Unique IPs</span><span class="text-violet-300">◎</span></div>
                    <div class="mt-4 text-3xl font-semibold">{{ number_format($uniqueIps) }}</div>
                    <div class="mt-2 text-xs text-slate-500">Last 24 hours</div>
                </article>
                <article class="{{ $card }} p-5">
                    <div class="flex items-center justify-between text-sm text-slate-400"><span>HTTP errors</span><span class="text-rose-300">!</span></div>
                    <div class="mt-4 text-3xl font-semibold">{{ number_format($errorRequests) }}</div>
                    <div class="mt-2 text-xs text-slate-500">Responses ≥ 400 · last 24h</div>
                </article>
                <article class="{{ $card }} p-5">
                    <div class="flex items-center justify-between text-sm text-slate-400"><span>Sample alerts</span><span class="text-amber-300">⚑</span></div>
                    <div class="mt-4 text-3xl font-semibold">{{ count($alerts) }}</div>
                                    </article>
            </section>

            @unless($hasRequestActivityTable)
                <div role="status" class="mt-6 rounded-xl border border-amber-400/20 bg-amber-400/[0.06] p-4 text-sm text-amber-100">
                    <p class="font-semibold">Request activity is not set up yet.</p>
                    <p class="mt-1 text-amber-100/70">Run <code class="rounded bg-slate-950 px-1.5 py-0.5 font-mono text-amber-200">php artisan migrate</code> to create its database table. Site requests will continue normally and will not be recorded until then.</p>
                </div>
            @endunless

            <section class="mt-6 grid gap-6 xl:grid-cols-[1.4fr_1fr]">
                <article class="{{ $card }} overflow-hidden">
                    <div class="flex items-center justify-between border-b border-slate-800 px-5 py-4">
                        <div><h2 class="font-semibold">Recent request activity</h2><p class="mt-1 text-xs text-slate-500">IP, path, method, response code and time</p></div>
                        <a href="{{ route('admin.xdr.requests') }}" class="text-sm text-cyan-300 hover:text-cyan-200">View all →</a>
                    </div>
                    @if($recentRequests->isEmpty())
                        <div class="px-5 py-12 text-center text-sm text-slate-500">No requests captured yet. New site activity will appear here.</div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px] text-left text-sm">
                            <thead class="text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 font-medium">IP address</th><th class="px-3 py-3 font-medium">Request</th><th class="px-3 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Time</th></tr></thead>
                            <tbody class="divide-y divide-slate-800/80">
                            @foreach($recentRequests as $activity)
                                <tr class="hover:bg-white/[0.02]">
                                    <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-cyan-200">{{ $activity->ip_address ?: 'unknown' }}</td>
                                    <td class="max-w-56 truncate px-3 py-3"><span class="mr-2 text-[10px] font-semibold text-slate-500">{{ $activity->method }}</span>{{ $activity->path }}</td>
                                    <td class="px-3 py-3"><span class="rounded-md px-2 py-1 text-xs {{ $activity->status_code >= 400 ? 'bg-rose-400/10 text-rose-300' : 'bg-emerald-400/10 text-emerald-300' }}">{{ $activity->status_code }}</span></td>
                                    <td class="whitespace-nowrap px-5 py-3 text-xs text-slate-500">{{ $activity->created_at->format('M j, H:i:s') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </article>

                <article id="alerts" class="{{ $card }} overflow-hidden">
                    
                    <div class="divide-y divide-slate-800/80">
                        @foreach($alerts as $alert)
                        <div class="px-5 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div><div class="flex items-center gap-2"><span class="rounded-md px-2 py-0.5 text-[10px] font-bold uppercase {{ $alert['severity'] === 'Critical' ? 'bg-rose-400/10 text-rose-300' : ($alert['severity'] === 'High' ? 'bg-orange-400/10 text-orange-300' : 'bg-amber-400/10 text-amber-300') }}">{{ $alert['severity'] }}</span><span class="text-xs text-slate-500">{{ $alert['time']->diffForHumans() }}</span></div><h3 class="mt-2 text-sm font-medium">{{ $alert['title'] }}</h3><p class="mt-1 text-xs text-slate-500">{{ $alert['detail'] }}</p></div>
                                <span class="text-rose-300">●</span>
                            </div>
                            <div class="mt-3 font-mono text-xs text-slate-400">Source IP <span class="text-cyan-200">{{ $alert['ip'] }}</span></div>
                        </div>
                        @endforeach
                    </div>
                </article>
            </section>

            <section id="agents" class="{{ $card }} mt-6 overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-800 px-5 py-4">
                                        <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-400">SIMULATED</span>
                </div>
                <div class="grid divide-y divide-slate-800/80 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                    @foreach($agents as $agent)
                    <div class="flex items-center justify-between gap-3 px-5 py-4">
                        <div><div class="font-mono text-sm">{{ $agent['name'] }}</div><div class="mt-1 text-xs text-slate-500">{{ $agent['platform'] }}</div></div>
                        <span class="flex items-center gap-1.5 text-xs text-emerald-300"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>{{ $agent['state'] }}</span>
                    </div>
                    @endforeach
                </div>
            </section>
            <p class="mt-5 text-xs leading-5 text-slate-600">Request IPs are collected from incoming web requests. Query strings and request bodies are not stored. Configure trusted proxies before deployment so forwarded client IPs are only accepted from known proxies.</p>
            
                                <span class="text-xl text-cyan-300">→</span>
            </a>
        </div>
    </main>
</div>
</body>
</html>
