<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bimbingan Konseling')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.3/dist/tailwind.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900" style="overflow-x: hidden; width: 100vw;">
    @unless (request()->routeIs('login'))
        <header class="bg-purple-700 text-white shadow">
            <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="/" class="font-bold text-lg">BK App</a>
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('student.dashboard') }}" class="hover:text-purple-200">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-purple-200">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-purple-200">Login</a>
                    @endauth
                </nav>
            </div>
        </header>
    @endunless

    <main class="w-full min-h-screen flex flex-col justify-center items-center px-2 py-8">
        @yield('content')
    </main>

    <footer class="text-center text-sm text-slate-500 py-8">
        {{-- &copy; {{ date('Y') }} Bimbingan Konseling --}}
    </footer>
</body>

</html>
