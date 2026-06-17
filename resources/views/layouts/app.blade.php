<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RelawanKita')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { opacity: 0; transition: opacity 0.3s ease; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <i class="fas fa-hands-helping text-blue-600 text-2xl"></i>
                    <span class="text-xl font-bold text-gray-900">RelawanKita</span>
                </a>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="font-medium transition {{ request()->routeIs('home') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-blue-600' }}">Beranda</a>
                    <a href="{{ route('events.index') }}" class="font-medium transition {{ request()->routeIs('events.*') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-blue-600' }}">Kegiatan</a>
                    <a href="{{ route('about') }}" class="font-medium transition {{ request()->routeIs('about') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-blue-600' }}">Tentang Kami</a>
                    <a href="{{ route('contact') }}" class="font-medium transition {{ request()->routeIs('contact') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-blue-600' }}">Kontak</a>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.events.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                            </a>
                        @endif
                        <div class="relative group">
                            <button class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition">
                                <i class="fas fa-user-circle text-blue-600"></i>
                                {{ auth()->user()->name }}
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="p-3 border-b">
                                    <p class="text-xs text-gray-500">Login sebagai</p>
                                    <p class="text-sm font-bold text-gray-800">{{ ucfirst(auth()->user()->role) }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="p-2">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-blue-600">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-blue-700 transition shadow-md">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div id="toastContainer" class="fixed top-4 right-4 z-[999] flex flex-col gap-2"></div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="hidden" data-toast="success" data-message="{{ session('success') }}"></div>
    @endif
    @if(session('error'))
        <div class="hidden" data-toast="error" data-message="{{ session('error') }}"></div>
    @endif

    {{-- CONTENT --}}
    <main class="grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-gray-400 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center gap-2 mb-4">
                <i class="fas fa-hands-helping text-blue-500 text-2xl"></i>
                <span class="text-xl font-bold text-white">RelawanKita</span>
            </div>
            <p class="text-sm">Platform penghubung aksi sosial terbesar di Indonesia.</p>
            <p class="text-xs mt-4 text-gray-600">&copy; {{ date('Y') }} RelawanKita. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script>
        function showToast(type, message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const isError = type === 'error';
            toast.className = `max-w-sm w-full rounded-2xl border px-4 py-3 shadow-lg text-sm font-medium ${isError ? 'bg-red-50 border-red-200 text-red-700' : 'bg-green-50 border-green-200 text-green-700'}`;
            toast.innerHTML = `<div class="flex items-center justify-between gap-3"><span><i class="fas ${isError ? 'fa-exclamation-circle' : 'fa-check-circle'} mr-2"></i>${message}</span><button class="text-current opacity-70 hover:opacity-100" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-times"></i></button></div>`;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }

        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { document.body.style.opacity = '1'; }, 50);
            document.querySelectorAll('[data-toast]').forEach(el => {
                showToast(el.dataset.toast, el.dataset.message);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>