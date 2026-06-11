@extends('layouts.admin')
@section('title', 'Detail Event')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.events.index') }}" class="text-blue-600 hover:underline text-sm font-semibold">← Kembali</a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            @if($event->image_url)
                <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-56 object-cover">
            @endif
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
                    @php $colors = ['approved'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $colors[$event->status] ?? '' }}">{{ ucfirst($event->status) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6 text-sm text-gray-600">
                    <div><i class="fas fa-tag text-blue-500 mr-2"></i>{{ $event->category }}</div>
                    <div><i class="fas fa-map-marker-alt text-red-500 mr-2"></i>{{ $event->location }}</div>
                    <div><i class="fas fa-calendar text-blue-500 mr-2"></i>{{ $event->formatted_date }}</div>
                    <div><i class="fas fa-clock text-blue-500 mr-2"></i>{{ $event->formatted_time }} WIB</div>
                    <div><i class="fas fa-building text-gray-500 mr-2"></i>{{ $event->organizer }}</div>
                    <div><i class="fas fa-user text-gray-500 mr-2"></i>{{ $event->user?->name ?? 'Admin' }}</div>
                </div>
                <p class="text-gray-600 leading-relaxed mb-4">{{ $event->description }}</p>
                @if($event->requirements)
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="font-semibold text-gray-700 mb-2">Persyaratan:</p>
                        <p class="text-gray-600 text-sm">{{ $event->requirements }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pendaftar --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Pendaftar ({{ $event->registrations->count() }})</h2>
            @if($event->registrations->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50"><tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">No HP</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Waktu</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Aksi</th>
                        </tr></thead>
                        <tbody class="divide-y">
                            @foreach($event->registrations as $r)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->name }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $r->email }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $r->phone }}</td>
                                @php $regColors = ['approved'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $regColors[$r->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($r->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-400">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('admin.events.registrations.status', [$event, $r]) }}" class="space-y-2">
                                        @csrf @method('PATCH')
                                        <div class="relative">
                                            <select name="status" class="w-full appearance-none rounded-2xl border border-gray-200 bg-white py-3 pl-4 pr-10 text-sm font-semibold text-gray-700 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                                <option value="approved" {{ $r->status=='approved'?'selected':'' }}>✅ Approved</option>
                                                <option value="pending" {{ $r->status=='pending'?'selected':'' }}>⏳ Pending</option>
                                                <option value="rejected" {{ $r->status=='rejected'?'selected':'' }}>❌ Rejected</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-2xl text-sm font-semibold hover:bg-blue-700 transition">
                                            Simpan Perubahan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-400 italic">Belum ada pendaftar.</p>
            @endif
        </div>
    </div>

    {{-- SIDEBAR AKSI --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-800 mb-4">Aksi</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.events.edit', $event) }}" class="block w-full bg-yellow-500 text-white py-2.5 rounded-xl text-center font-semibold hover:bg-yellow-600 transition text-sm">
                    <i class="fas fa-edit mr-2"></i>Edit Event
                </a>
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Hapus event ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 text-white py-2.5 rounded-xl font-semibold hover:bg-red-600 transition text-sm">
                        <i class="fas fa-trash mr-2"></i>Hapus Event
                    </button>
                </form>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-800 mb-4">Update Status</h3>
            <form method="POST" action="{{ route('admin.events.status', $event) }}" class="space-y-3">
                @csrf @method('PATCH')
                <div class="relative">
                    <select name="status" class="w-full appearance-none rounded-2xl border border-gray-200 bg-white py-3 pl-4 pr-10 text-sm font-semibold text-gray-700 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="approved" {{ $event->status=='approved'?'selected':'' }}>✅ Approved</option>
                        <option value="pending" {{ $event->status=='pending'?'selected':'' }}>⏳ Pending</option>
                        <option value="rejected" {{ $event->status=='rejected'?'selected':'' }}>❌ Rejected</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-2xl text-sm font-semibold hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection