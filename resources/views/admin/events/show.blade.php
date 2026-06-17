@extends('layouts.admin')
@section('title', 'Detail Event')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.events.index') }}" class="text-blue-600 hover:underline text-sm font-semibold">← Kembali</a>
</div>
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            @if($event->image_url)
                <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
            @endif
            <div class="p-8">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $event->title }}</h1>
                    @php $colors = ['approved'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $colors[$event->status] ?? '' }}">{{ ucfirst($event->status) }}</span>
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
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Pendaftar</h2>
                    <p class="text-sm text-gray-500">Total pendaftar: {{ $event->registrations->count() }}</p>
                </div>
                <div class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-4 py-2 text-sm font-semibold">
                    <i class="fas fa-users mr-2"></i>{{ $event->registrations->count() }} Pendaftar
                </div>
            </div>

            <div class="flex items-center gap-2 mb-4 text-xs text-gray-500">
                <span class="inline-flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>Pending</span>
                <span class="inline-flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>Rejected</span>
                <span class="inline-flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>Accepted</span>
            </div>

            @if($event->registrations->isNotEmpty())
                <div class="space-y-4">
                    @foreach($event->registrations as $r)
                        @php $regColors = ['approved'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                        <div class="rounded-3xl border border-gray-200 bg-gray-50 p-5 shadow-sm transition hover:shadow-md">
                            <div class="md:flex md:items-center md:justify-between gap-4">
                                <div class="space-y-2">
                                    <p class="text-lg font-semibold text-gray-900">{{ $r->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $r->email }} · {{ $r->phone }}</p>
                                </div>
                                <div class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $regColors[$r->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    <span class="w-2 h-2 rounded-full mr-2 {{ $r->status==='approved' ? 'bg-green-500' : ($r->status==='rejected' ? 'bg-red-500' : 'bg-yellow-400') }}"></span>
                                    {{ ucfirst($r->status) }}
                                </div>
                            </div>
                            <div class="mt-4 grid gap-4 lg:grid-cols-[1.5fr_auto] lg:items-end">
                                <div class="text-sm text-gray-500">
                                    <p>Daftar: <span class="font-semibold text-gray-900">{{ $r->created_at->format('d/m/Y H:i') }}</span></p>
                                </div>
                                @if(auth()->user()->isDeveloper())
                                    <form method="POST" action="{{ route('admin.events.registrations.status', [$event, $r]) }}" class="grid gap-3 sm:grid-cols-[1fr_auto]">
                                        @csrf @method('PATCH')
                                        <div class="relative">
                                            <select name="status" class="w-full rounded-2xl border border-gray-300 bg-white py-3 pl-4 pr-10 text-sm font-semibold text-gray-700 shadow-sm outline-none transition duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                                <option value="approved" {{ $r->status=='approved'?'selected':'' }}>✅ Approved</option>
                                                <option value="pending" {{ $r->status=='pending'?'selected':'' }}>⏳ Pending</option>
                                                <option value="rejected" {{ $r->status=='rejected'?'selected':'' }}>❌ Rejected</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full rounded-3xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition duration-200">
                                            Simpan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">Belum ada pendaftar.</p>
            @endif
        </div>
    </div>

    {{-- SIDEBAR AKSI --}}
    <div class="space-y-4">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-8">
            <h3 class="font-bold text-gray-800 mb-5 text-lg">Aksi</h3>
            <div class="space-y-4">
                <a href="{{ route('admin.events.edit', $event) }}" class="block w-full bg-yellow-500 text-white py-3 rounded-3xl text-center font-semibold hover:bg-yellow-600 transition text-sm shadow-sm">
                    <i class="fas fa-edit mr-2"></i>Edit Event
                </a>
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Hapus event ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-3xl font-semibold hover:bg-red-600 transition text-sm shadow-sm">
                        <i class="fas fa-trash mr-2"></i>Hapus Event
                    </button>
                </form>
            </div>
        </div>

        @if(auth()->user()->isDeveloper())
            {{-- Update Status --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-8">
                <h3 class="font-bold text-gray-800 mb-5 text-lg">Update Status</h3>
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
        @endif
    </div>
</div>
@endsection