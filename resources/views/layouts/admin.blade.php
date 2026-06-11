<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Dashboard') | RelawanKita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { opacity: 0; transition: opacity 0.3s ease; }</style>
</head>
<body class="bg-gray-100 min-h-screen" style="">

    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        <aside class="w-64 bg-gray-900 text-white flex flex-col fixed h-full z-40">
            <div class="p-6 border-b border-gray-700">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <i class="fas fa-hands-helping text-blue-400 text-xl"></i>
                    <span class="font-bold text-lg">RelawanKita</span>
                </a>
                <span class="text-xs text-gray-400 mt-1 block">Panel Admin</span>
            </div>

            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.events.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-calendar-alt w-5"></i> Kelola Event
                </a>
                <a href="{{ route('events.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-300 hover:bg-gray-800 transition">
                    <i class="fas fa-eye w-5"></i> Lihat Situs
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-red-900/30 rounded-xl transition">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="ml-64 flex-1 flex flex-col">
            <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-30">
                <h1 class="text-xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
                <span class="text-sm text-gray-500">{{ now()->format('d F Y') }}</span>
            </header>

            @if(session('success'))
                <div class="mx-8 mt-4 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-2xl shadow-sm flex items-center justify-between">
                    <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-800 hover:text-green-900"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="mx-8 mt-4 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-2xl shadow-sm flex items-center justify-between">
                    <span><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-800 hover:text-red-900"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <main class="flex-1 p-10 max-w-7xl mx-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { document.body.style.opacity = '1'; }, 50);
        });
    </script>
    @stack('scripts')
</body>
</html> 