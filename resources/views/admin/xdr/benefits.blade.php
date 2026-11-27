<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>XDR &amp; SIEM Benefits | Admin</title>
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
            <span><span class="block text-sm font-semibold tracking-wide">SENTINEL XDR</span><span class="text-xs text-slate-500">Wazuh security console</span></span>
        </a>
        <nav class="mt-7 flex gap-2 lg:mt-10 lg:flex-col">
            <a href="{{ route('admin.xdr') }}" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">◈ &nbsp; Overview</a>
            <a href="{{ route('admin.xdr.requests') }}" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">⌁ &nbsp; Request activity</a>
            <a href="{{ route('admin.xdr.benefits') }}" class="{{ $nav }} bg-cyan-400/10 text-cyan-200 ring-1 ring-cyan-400/20">✦ &nbsp; XDR &amp; SIEM benefits</a>
            <a href="{{ route('admin.xdr.audit-log') }}" class="{{ $nav }} text-slate-400 hover:bg-slate-900 hover:text-white">▤ &nbsp; Audit log</a>
        </nav>
        <div class="mt-6 rounded-xl border border-slate-800 bg-slate-900 p-4 lg:mt-auto">
            <div class="flex items-center gap-2 text-xs font-medium text-cyan-300"><span class="h-2 w-2 rounded-full bg-cyan-400"></span> MANUALLY CURATED</div>
            <p class="mt-2 text-xs leading-5 text-slate-500">These are general security benefits and intended outcomes—not live metrics, configured capabilities, or a Wazuh integration status.</p>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-800 pt-4 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-white">← Admin</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-slate-400 hover:text-white">Sign out</button></form>
        </div>
    </aside>

    <main class="min-w-0 flex-1 p-4 sm:p-6 lg:ml-64 lg:p-9">
        <div class="mx-auto max-w-7xl">
            <header class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <a href="{{ route('admin.xdr') }}" class="text-xs text-cyan-300 hover:text-cyan-200">← Security overview</a>
                    <p class="mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">Security outcomes</p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">XDR &amp; SIEM benefits</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-400">A practical, manually maintained guide to potential extended detection and response (XDR) and security information and event management (SIEM) outcomes.</p>
                </div>
                <span class="flex shrink-0 items-center gap-2 self-start rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1.5 text-xs font-medium text-cyan-200 sm:self-auto"><span class="h-2 w-2 rounded-full bg-cyan-400"></span> MANUAL CONTENT</span>
            </header>

            @if (! $benefitsSchemaAvailable)
            <section class="mt-7 rounded-2xl border border-amber-400/20 bg-amber-400/[0.04] p-5 text-sm leading-6 text-slate-300">
                Benefits storage is not set up yet. Run <code class="text-amber-200">php artisan migrate</code> after safely reconciling existing migration history.
            </section>
            @elseif (! $benefitsAvailable)
            <section class="mt-7 rounded-2xl border border-amber-400/20 bg-amber-400/[0.04] p-5 text-sm leading-6 text-slate-300">
                The benefit tables are ready, but the curated catalog has not been seeded. Run <code class="text-amber-200">php artisan db:seed --class="Database\Seeders\SecurityBenefitsSeeder"</code>.
            </section>
            @else
            <nav aria-label="Benefits sections" class="mt-6 flex flex-wrap gap-2 text-xs">
                <a href="#xdr-benefits" class="rounded-full border border-violet-400/20 bg-violet-400/5 px-3 py-2 text-violet-200 hover:bg-violet-400/10">XDR benefits</a>
                <a href="#siem-benefits" class="rounded-full border border-cyan-400/20 bg-cyan-400/5 px-3 py-2 text-cyan-200 hover:bg-cyan-400/10">SIEM benefits</a>
                <a href="#comparison" class="rounded-full border border-slate-700 px-3 py-2 text-slate-300 hover:bg-slate-900">Compare XDR and SIEM</a>
                <a href="#business-value" class="rounded-full border border-slate-700 px-3 py-2 text-slate-300 hover:bg-slate-900">Business value</a>
            </nav>

            <section id="xdr-benefits" class="mt-7 scroll-mt-6">
                <div class="mb-4 flex items-end justify-between gap-4">
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-violet-300">{{ $xdrModule->name }}</p><h2 class="mt-1 text-xl font-semibold">XDR benefits</h2></div>
                    <span class="hidden text-xs text-slate-500 sm:block">{{ $xdrModule->subtitle }}</span>
                </div>
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($xdrBenefits as $benefit)
                    <article class="{{ $card }} flex flex-col p-5">
                        <div class="flex items-center justify-between gap-3"><span class="rounded-md bg-violet-400/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider text-violet-300">{{ $benefit->label }}</span><span class="text-violet-300/70">✦</span></div>
                        <h3 class="mt-4 text-base font-semibold">{{ $benefit->title }}</h3>
                        <p class="mt-2 flex-1 text-sm leading-6 text-slate-400">{{ $benefit->description }}</p>
                        <div class="mt-5 border-t border-slate-800 pt-4"><span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Potential value</span><p class="mt-1 text-sm text-violet-200">{{ $benefit->potential_value }}</p></div>
                    </article>
                    @endforeach
                </div>
            </section>

            <section id="siem-benefits" class="mt-10 scroll-mt-6">
                <div class="mb-4 flex items-end justify-between gap-4">
                    <div><p class="text-xs font-semibold uppercase tracking-wider text-cyan-300">{{ $siemModule->name }}</p><h2 class="mt-1 text-xl font-semibold">SIEM benefits</h2></div>
                    <span class="hidden text-xs text-slate-500 sm:block">{{ $siemModule->subtitle }}</span>
                </div>
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($siemBenefits as $benefit)
                    <article class="{{ $card }} flex flex-col p-5">
                        <div class="flex items-center justify-between gap-3"><span class="rounded-md bg-cyan-400/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider text-cyan-300">{{ $benefit->label }}</span><span class="text-cyan-300/70">◈</span></div>
                        <h3 class="mt-4 text-base font-semibold">{{ $benefit->title }}</h3>
                        <p class="mt-2 flex-1 text-sm leading-6 text-slate-400">{{ $benefit->description }}</p>
                        <div class="mt-5 border-t border-slate-800 pt-4"><span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Potential value</span><p class="mt-1 text-sm text-cyan-200">{{ $benefit->potential_value }}</p></div>
                    </article>
                    @endforeach
                </div>
            </section>

            <section id="comparison" class="mt-10 scroll-mt-6">
                <div class="mb-4"><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">At a glance</p><h2 class="mt-1 text-xl font-semibold">{{ $comparisonCategory->name }}</h2></div>
                <div class="{{ $card }} overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] text-left text-sm">
                            <thead class="bg-slate-950/60 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 font-medium">Dimension</th><th class="px-5 py-3 font-medium text-violet-300">XDR</th><th class="px-5 py-3 font-medium text-cyan-300">SIEM</th></tr></thead>
                            <tbody class="divide-y divide-slate-800">
                                @foreach($comparison as $row)
                                <tr><th scope="row" class="whitespace-nowrap px-5 py-4 font-medium text-slate-200">{{ $row->title }}</th><td class="px-5 py-4 leading-6 text-slate-400">{{ $row->context['xdr'] ?? '' }}</td><td class="px-5 py-4 leading-6 text-slate-400">{{ $row->context['siem'] ?? '' }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="border-t border-slate-800 px-5 py-4 text-sm leading-6 text-slate-400">XDR emphasizes connecting detections and response across selected security layers; SIEM emphasizes broad event collection, analysis, search, and retention. Depending on architecture, the tools and workflows may overlap and complement one another.</p>
                </div>
            </section>

            <section id="business-value" class="mt-10 scroll-mt-6">
                <div class="mb-4"><p class="text-xs font-semibold uppercase tracking-wider text-emerald-300">{{ $businessCategory->name }}</p><h2 class="mt-1 text-xl font-semibold">Business and operational value</h2></div>
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach($businessBenefits as $benefit)
                    <article class="{{ $card }} p-5">
                        <span class="text-emerald-300">✦</span>
                        <h3 class="mt-3 font-semibold">{{ $benefit->title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">{{ $benefit->description }}</p>
                    </article>
                    @endforeach
                </div>
            </section>
            @endif

            <aside class="mt-8 rounded-2xl border border-amber-400/15 bg-amber-400/[0.04] p-5 sm:p-6">
                <h2 class="font-semibold text-amber-100">Curated reference—not deployment evidence</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">This page is manually maintained and describes potential benefits, not measured results or features enabled in this project. Real outcomes depend on product selection, licensed capabilities, connected data sources, configuration, staffing, and ongoing tuning. This project’s request activity is collected by its own Laravel app; the Wazuh alerts and agent inventory shown on the overview are illustrative demo content. No live Wazuh, XDR, or SIEM service is connected by this page.</p>
            </aside>
        </div>
    </main>
</div>
</body>
</html>
