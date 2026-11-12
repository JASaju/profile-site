<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <form method="POST" action="{{ url('/login') }}" class="bg-white w-full max-w-sm rounded-2xl shadow p-6 space-y-4">
        @csrf
        <h1 class="text-xl font-bold">Admin Login</h1>

        <div>
            <label class="block text-sm mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-400">
            @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-400">
        </div>

        <button class="w-full bg-slate-900 text-white rounded-lg py-2 font-medium hover:bg-slate-800">Log in</button>
        <a href="{{ route('home') }}" class="block text-center text-sm text-slate-500 hover:underline">&larr; Back to site</a>
    </form>
</body>
</html>
