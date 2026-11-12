<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">

@php
    $input = 'w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400';
    $btn   = 'bg-slate-900 text-white rounded-lg px-4 py-2 text-sm font-medium hover:bg-slate-800';
    $card  = 'bg-white rounded-2xl shadow-sm border p-5';
@endphp

<header class="bg-white border-b">
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
        <span class="font-semibold">Admin Dashboard</span>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('home') }}" target="_blank" class="text-blue-600 hover:underline">View site</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="text-slate-600 hover:text-slate-900">Logout</button>
            </form>
        </div>
    </div>
</header>

<main class="max-w-5xl mx-auto px-4 py-6 space-y-6">

    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-2 text-sm">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-2 text-sm">
            <ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- PROFILE --}}
    <section class="{{ $card }}">
        <h2 class="font-semibold mb-3">Profile</h2>
        <form method="POST" action="{{ route('admin.profile') }}" enctype="multipart/form-data" class="grid sm:grid-cols-2 gap-3">
            @csrf @method('PUT')
            <div><label class="text-sm">Name *</label><input class="{{ $input }}" name="name" value="{{ old('name', $profile->name) }}" required></div>
            <div><label class="text-sm">Headline</label><input class="{{ $input }}" name="headline" value="{{ old('headline', $profile->headline) }}"></div>
            <div class="sm:col-span-2"><label class="text-sm">Bio</label><textarea class="{{ $input }}" rows="4" name="bio">{{ old('bio', $profile->bio) }}</textarea></div>
            <div><label class="text-sm">Email</label><input class="{{ $input }}" type="email" name="email" value="{{ old('email', $profile->email) }}"></div>
            <div><label class="text-sm">Phone</label><input class="{{ $input }}" name="phone" value="{{ old('phone', $profile->phone) }}"></div>
            <div><label class="text-sm">Location</label><input class="{{ $input }}" name="location" value="{{ old('location', $profile->location) }}"></div>
            <div><label class="text-sm">YouTube channel link</label><input class="{{ $input }}" type="url" name="youtube_channel_url" placeholder="https://youtube.com/@yourchannel" value="{{ old('youtube_channel_url', $profile->youtube_channel_url) }}"></div>
            <div class="sm:col-span-2 flex items-center gap-3">
                @if($profile->photo_url)<img src="{{ $profile->photo_url }}" class="w-14 h-14 rounded-full object-cover">@endif
                <div class="flex-1"><label class="text-sm">Profile photo</label><input class="{{ $input }}" type="file" name="photo" accept="image/*"></div>
            </div>
            <div class="sm:col-span-2"><button class="{{ $btn }}">Save profile</button></div>
        </form>
    </section>

    {{-- SLIDER --}}
    <section class="{{ $card }}">
        <h2 class="font-semibold mb-3">Slider images</h2>
        <form method="POST" action="{{ route('admin.store', 'slide') }}" enctype="multipart/form-data" class="grid sm:grid-cols-4 gap-2 mb-4">
            @csrf
            <input class="{{ $input }}" name="title" placeholder="Title (optional)">
            <input class="{{ $input }}" name="caption" placeholder="Caption (optional)">
            <input class="{{ $input }}" type="file" name="image" accept="image/*" required>
            <button class="{{ $btn }}">Add slide</button>
        </form>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach($slides as $s)
            <div class="border rounded-lg overflow-hidden">
                <img src="{{ $s->url }}" class="h-24 w-full object-cover">
                <div class="p-2 text-xs flex justify-between items-center">
                    <span class="truncate">{{ $s->title ?: 'Untitled' }}</span>
                    <form method="POST" action="{{ route('admin.destroy', ['slide', $s->id]) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- SOCIAL --}}
    <section class="{{ $card }}">
        <h2 class="font-semibold mb-3">Social links</h2>
        <form method="POST" action="{{ route('admin.store', 'social') }}" class="grid sm:grid-cols-3 gap-2 mb-4">
            @csrf
            <input class="{{ $input }}" name="platform" placeholder="Facebook, Instagram, LinkedIn..." required>
            <input class="{{ $input }}" type="url" name="url" placeholder="https://..." required>
            <button class="{{ $btn }}">Add link</button>
        </form>
        <ul class="divide-y text-sm">
            @foreach($socials as $s)
            <li class="py-2 flex justify-between gap-3">
                <span><strong>{{ $s->platform }}</strong> — <a class="text-blue-600" href="{{ $s->url }}" target="_blank">{{ $s->url }}</a></span>
                <form method="POST" action="{{ route('admin.destroy', ['social', $s->id]) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
            </li>
            @endforeach
        </ul>
    </section>

    {{-- EDUCATION --}}
    <section class="{{ $card }}">
        <h2 class="font-semibold mb-3">Education background</h2>
        <form method="POST" action="{{ route('admin.store', 'education') }}" class="grid sm:grid-cols-3 gap-2 mb-4">
            @csrf
            <input class="{{ $input }}" name="institution" placeholder="Institution *" required>
            <input class="{{ $input }}" name="degree" placeholder="Degree (B.Sc, HSC...)">
            <input class="{{ $input }}" name="field" placeholder="Field / Group">
            <input class="{{ $input }}" name="start_year" placeholder="Start year">
            <input class="{{ $input }}" name="end_year" placeholder="End year or Present">
            <input class="{{ $input }}" name="description" placeholder="Note (optional)">
            <div class="sm:col-span-3"><button class="{{ $btn }}">Add education</button></div>
        </form>
        <ul class="divide-y text-sm">
            @foreach($education as $e)
            <li class="py-2 flex justify-between gap-3">
                <span><strong>{{ $e->institution }}</strong> — {{ $e->degree }} {{ $e->field }} ({{ $e->start_year }}–{{ $e->end_year }})</span>
                <form method="POST" action="{{ route('admin.destroy', ['education', $e->id]) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
            </li>
            @endforeach
        </ul>
    </section>

    {{-- DOCUMENTARIES --}}
    <section class="{{ $card }}">
        <h2 class="font-semibold mb-3">Documentaries (YouTube videos)</h2>
        <form method="POST" action="{{ route('admin.store', 'documentary') }}" class="grid sm:grid-cols-3 gap-2 mb-4">
            @csrf
            <input class="{{ $input }}" name="title" placeholder="Title *" required>
            <input class="{{ $input }}" type="url" name="youtube_url" placeholder="YouTube video link *" required>
            <input class="{{ $input }}" name="description" placeholder="Short description">
            <div class="sm:col-span-3"><button class="{{ $btn }}">Add documentary</button></div>
        </form>
        <ul class="divide-y text-sm">
            @foreach($documentaries as $d)
            <li class="py-2 flex justify-between gap-3">
                <span><strong>{{ $d->title }}</strong> — <a class="text-blue-600" href="{{ $d->youtube_url }}" target="_blank">{{ $d->youtube_url }}</a></span>
                <form method="POST" action="{{ route('admin.destroy', ['documentary', $d->id]) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
            </li>
            @endforeach
        </ul>
    </section>

    {{-- GALLERY --}}
    <section class="{{ $card }}">
        <h2 class="font-semibold mb-3">Public gallery photos</h2>
        <form method="POST" action="{{ route('admin.store', 'gallery') }}" enctype="multipart/form-data" class="grid sm:grid-cols-3 gap-2 mb-4">
            @csrf
            <input class="{{ $input }}" name="title" placeholder="Title (optional)">
            <input class="{{ $input }}" type="file" name="image" accept="image/*" required>
            <button class="{{ $btn }}">Upload photo</button>
        </form>
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
            @foreach($gallery as $g)
            <div class="border rounded-lg overflow-hidden">
                <img src="{{ $g->url }}" class="h-20 w-full object-cover">
                <form class="p-1 text-center text-xs" method="POST" action="{{ route('admin.destroy', ['gallery', $g->id]) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
            </div>
            @endforeach
        </div>
    </section>
</main>
</body>
</html>
