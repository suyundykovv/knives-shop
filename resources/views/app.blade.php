<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
<nav class="bg-gray-800 p-4 text-white">
    <div class="container mx-auto flex justify-between">
        <a href="{{ route('register') }}" class="text-xl font-bold">MyApp</a>
        <ul class="flex space-x-4">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            @auth
                <li><a href="{{ route('knives.index') }}">Knives</a></li>
                @if(Auth::user()->is_admin)
                    <li><a href="{{ route('admin.dashboard') }}" class="text-red-400">User Panel</a></li>
                    <li><a href="{{ route('knives.index2') }}" class="text-blue-400">Knife Panel</a></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endauth
        </ul>
    </div>
</nav>
@inertia
</body>
</html>
