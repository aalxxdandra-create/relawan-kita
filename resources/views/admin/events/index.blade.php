@extends('layouts.admin')
@section('title', 'Kelola Event')
@section('content')

{{-- STATS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border">
        <p class="text-xs text-gray-500 uppercase font-bold">Total Event</p>
        <p class="text-3xl font-black text-gray-900 mt-1">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-green-50 rounded-2xl p-5 shadow-sm border border-green-100">
        <p class="text-xs text-green-600 uppercase font-bold">Approved</p>
        <p class="text-3xl font-black text-green-700 mt-1">{{ $stats['approved'] }}</p>
    </div>
    <div class="bg-yellow-50 rounded-2xl p-5 shadow-sm border border-yellow-100">
        <p class="text-xs text-yellow-600 uppercase font-bold">Pending</p>
        <p class="text-3xl font-black text-yellow-700 mt-1">{{ $stats['pending'] }}</p>
    </div>
    <div class="bg-red-50 rounded-2xl p-5 shadow-sm border border-red-100">
        <p class="text-xs text-red-600 uppercase font-bold">Rejected</p>
        <p class="text-3xl font-black text-red-700 mt-1">{{ $stats['rejected'] }}</p>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
    {{-- HEADER --}}
    <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Semua Event</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola event dan lihat status persetujuan dengan mudah.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-2xl font-semibold hover:bg-blue-700 transition text-sm shadow-sm">
            <i class="fas fa-plus"></i> Tambah Event
        </a>
    </div>

    {{-- FILTER --}}
    <div class="p-8 border-b border-gray-100">
        <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event..." class="flex-1 px-5 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none text-sm text-gray-700">
            <select name="status" class="w-full max-w-xs px-5 py-3 border border-gray-300 rounded-2xl text-sm text-gray-700 outline-none">
                <option value="">Semua Status</option>
                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
            </select>
            <button type="submit" class="bg-gray-900 text-white px-6 py-3 rounded-2xl text-sm font-semibold hover:bg-gray-800 transition shadow-sm">Filter</button>
            @if(request()->hasAny(['search','status','category']))
                <a href="{{ route('admin.events.index') }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-6 py-3 rounded-2xl text-sm font-semibold hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pengaju</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($events as $event)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900 text-sm">{{ Str::limit($event->title, 45) }}</p>
                        <p class="text-xs text-gray-400">{{ $event->organizer }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-semibold">{{ $event->category }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $event->formatted_date }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = ['approved'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','rejected'=>'bg-red-100 text-red-700'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $colors[$event->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $event->user?->name ?? 'Admin' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.events.show', $event) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Detail</a>
                            <a href="{{ route('admin.events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-semibold">Edit</a>
                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Hapus event ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada event.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $events->links() }}</div>
</div>
@endsection