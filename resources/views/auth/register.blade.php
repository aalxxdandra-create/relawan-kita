@extends('layouts.app')
@section('title', 'Daftar Akun')
@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gradient-to-br from-blue-50 to-emerald-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 py-8 px-8 text-white text-center">
            <i class="fas fa-user-plus text-4xl mb-3"></i>
            <h1 class="text-2xl font-bold">Buat Akun Baru</h1>
            <p class="text-emerald-100 text-sm mt-1">Mulai perjalanan kerelawananmu</p>
        </div>
        <div class="p-8">
            @if($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    @foreach($errors->all() as $e)
                        <p class="text-sm text-red-700">{{ $e }}</p>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition"
                        placeholder="Nama Lengkap Anda">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition"
                        placeholder="nama@email.com">
                    <p class="text-xs text-gray-500 mt-2">Gunakan email <strong>@gmail.com</strong> untuk user, <strong>@relawankita.com</strong> untuk admin, atau <strong>@developer.com</strong> untuk developer.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition"
                        placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition"
                        placeholder="Ulangi password">
                </div>
                <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i> Buat Akun
                </button>
            </form>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection