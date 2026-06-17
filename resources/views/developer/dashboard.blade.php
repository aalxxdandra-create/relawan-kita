@extends('layouts.admin')
@section('title', 'Dashboard Developer')
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Event</p>
            <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Approved</p>
            <h3 class="mt-2 text-3xl font-bold text-green-600">{{ $stats['approved'] }}</h3>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Pending</p>
            <h3 class="mt-2 text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</h3>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Rejected</p>
            <h3 class="mt-2 text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Daftar Event</h2>
                <p class="text-sm text-gray-500">Kelola status dan review semua event</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Event</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Penyelenggara</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($events as $event)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.events.show', $event) }}" class="font-semibold text-blue-600 hover:underline">{{ $event->title }}</a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $event->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $event->formatted_date }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($event->status === 'approved') bg-green-100 text-green-700
                                    @elseif($event->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.events.show', $event) }}" class="text-sm font-semibold text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection