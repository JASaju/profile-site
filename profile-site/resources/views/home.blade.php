<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profile->name ?? 'My Profile' }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($profile->headline ?? '', 150) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
        .swiper-slide img { width: 100%; height: 100%; object-fit: cover; }
        .swiper-button-next, .swiper-button-prev { color: #fff; }
        .swiper-pagination-bullet-active { background: #fff; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

{{-- Top bar --}}
<header class="bg-white/90 backdrop-blur sticky top-0 z-30 border-b">
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
        <a href="#top" class="font-semibold">{{ $profile->name ?? 'My Profile' }}</a>
        <nav class="hidden sm:flex gap-5 text-sm text-slate-600">
            @if($documentaries->count())<a href="#documentaries" class="hover:text-slate-900">Documentaries</a>@endif
            @if($education->count())<a href="#education" class="hover:text-slate-900">Education</a>@endif
            @if($gallery->count())<a href="#gallery" class="hover:text-slate-900">Gallery</a>@endif
            <a href="#contact" class="hover:text-slate-900">Contact</a>
            <a href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}" class="text-slate-400 hover:text-slate-900">{{ auth()->check() ? 'Admin' : 'Login' }}</a>
        </nav>
    </div>
</header>

<main id="top">

    {{-- Small slider --}}
    @if($slides->count())
    <section class="max-w-5xl mx-auto px-4 pt-6">
        <div class="swiper heroSwiper rounded-2xl overflow-hidden shadow h-52 sm:h-64 md:h-72 bg-slate-200">
            <div class="swiper-wrapper">
                @foreach($slides as $slide)
                <div class="swiper-slide relative">
                    <img src="{{ $slide->url }}" alt="{{ $slide->title ?? 'Slide' }}">
                    @if($slide->title || $slide->caption)
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4 text-white">
                        @if($slide->title)<div class="font-semibold">{{ $slide->title }}</div>@endif
                        @if($slide->caption)<div class="text-sm text-white/80">{{ $slide->caption }}</div>@endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </section>
    @endif

    {{-- Profile --}}
    <section class="max-w-5xl mx-auto px-4 py-10">
        <div class="bg-white rounded-2xl shadow-sm border p-6 sm:p-8 flex flex-col sm:flex-row gap-6 items-center sm:items-start">
            @if($profile->photo_url)
                <img src="{{ $profile->photo_url }}" alt="{{ $profile->name }}" class="w-36 h-36 rounded-full object-cover ring-4 ring-slate-100 shrink-0">
            @endif
            <div class="text-center sm:text-left">
                <h1 class="text-3xl font-bold">{{ $profile->name ?? 'Your Name' }}</h1>
                @if($profile->headline)<p class="text-slate-500 mt-1">{{ $profile->headline }}</p>@endif
                @if($profile->bio)<p class="mt-4 leading-relaxed whitespace-pre-line">{{ $profile->bio }}</p>@endif

                <div class="mt-5 flex flex-wrap gap-2 justify-center sm:justify-start">
                    @if($profile->youtube_channel_url)
                        <a href="{{ $profile->youtube_channel_url }}" target="_blank" rel="noopener"
                           class="px-4 py-2 rounded-full bg-red-600 text-white text-sm font-medium hover:bg-red-700">&#9654; YouTube Channel</a>
                    @endif
                    @foreach($socials as $s)
                        <a href="{{ $s->url }}" target="_blank" rel="noopener"
                           class="px-4 py-2 rounded-full border text-sm hover:bg-slate-100">{{ $s->platform }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Documentaries --}}
    @if($documentaries->count())
    <section id="documentaries" class="max-w-5xl mx-auto px-4 pb-10">
        <h2 class="text-2xl font-bold mb-4">Documentaries</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($documentaries as $d)
            <article class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                @if($d->embed_url)
                    <div class="aspect-video">
                        <iframe class="w-full h-full" src="{{ $d->embed_url }}" title="{{ $d->title }}" loading="lazy"
                                allow="accelerometer; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold">{{ $d->title }}</h3>
                    @if($d->description)<p class="text-sm text-slate-600 mt-1">{{ $d->description }}</p>@endif
                    <a href="{{ $d->youtube_url }}" target="_blank" rel="noopener" class="inline-block mt-2 text-sm text-red-600 hover:underline">Watch on YouTube</a>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Education --}}
    @if($education->count())
    <section id="education" class="max-w-5xl mx-auto px-4 pb-10">
        <h2 class="text-2xl font-bold mb-4">Education</h2>
        <ol class="relative border-l-2 border-slate-200 ml-2 space-y-6">
            @foreach($education as $e)
            <li class="pl-6 relative">
                <span class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-slate-800 ring-4 ring-slate-50"></span>
                <div class="text-sm text-slate-500">{{ $e->start_year }}@if($e->start_year && $e->end_year) – @endif{{ $e->end_year }}</div>
                <div class="font-semibold">{{ $e->institution }}</div>
                @if($e->degree || $e->field)<div class="text-slate-700">{{ $e->degree }}@if($e->degree && $e->field), @endif{{ $e->field }}</div>@endif
                @if($e->description)<p class="text-sm text-slate-600 mt-1">{{ $e->description }}</p>@endif
            </li>
            @endforeach
        </ol>
    </section>
    @endif

    {{-- Gallery --}}
    @if($gallery->count())
    <section id="gallery" class="max-w-5xl mx-auto px-4 pb-10">
        <h2 class="text-2xl font-bold mb-4">Gallery</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($gallery as $g)
            <a href="{{ $g->url }}" target="_blank" class="group block overflow-hidden rounded-xl bg-slate-200 aspect-square">
                <img src="{{ $g->url }}" alt="{{ $g->title ?? 'Photo' }}" loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Contact --}}
    <section id="contact" class="max-w-5xl mx-auto px-4 pb-14">
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h2 class="text-2xl font-bold mb-3">Contact</h2>
            <ul class="space-y-1 text-slate-700">
                @if($profile?->email)<li>Email: <a class="text-blue-600 hover:underline" href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></li>@endif
                @if($profile?->phone)<li>Phone: {{ $profile->phone }}</li>@endif
                @if($profile?->location)<li>Location: {{ $profile->location }}</li>@endif
            </ul>
        </div>
    </section>
</main>

<footer class="border-t bg-white py-6 text-center text-sm text-slate-500">
    &copy; {{ date('Y') }} {{ $profile->name ?? '' }}
</footer>

<script>
    new Swiper('.heroSwiper', {
        loop: document.querySelectorAll('.heroSwiper .swiper-slide').length > 1,
        autoplay: { delay: 4000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });
</script>
</body>
</html>
