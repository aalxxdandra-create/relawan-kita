@extends('layouts.admin')
@section('title', 'Edit Event')
@section('content')
<div class="max-w-3xl">
    <div class="mb-6"><a href="{{ route('admin.events.show', $event) }}" class="text-blue-600 hover:underline text-sm font-semibold">← Kembali</a></div>
    <div class="bg-white rounded-2xl shadow-sm border p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Event</h2>
        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
                @foreach($errors->all() as $e)<p class="text-sm text-red-700">{{ $e }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Organisasi *</label>
                    <input type="text" name="organizer" value="{{ old('organizer', $event->organizer) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Kategori *</label>
                    <select name="category" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        @foreach(['Pendidikan','Lingkungan','Kesehatan','Sosial','Olahraga','Lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ old('category',$event->category)==$cat?'selected':'' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Judul Event *</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal *</label>
                    <input type="date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Waktu *</label>
                    <input type="time" name="event_time" value="{{ old('event_time', $event->event_time) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi *</label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi *</label>
                <textarea name="description" rows="5" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">{{ old('description', $event->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Persyaratan</label>
                <textarea name="requirements" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">{{ old('requirements', $event->requirements) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Upload Gambar Baru</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-700 border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            @if($event->image_url)
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Gambar Saat Ini</label>
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-56 object-cover rounded-2xl border border-gray-200">
                </div>
            @endif
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    <option value="approved" {{ old('status',$event->status)=='approved'?'selected':'' }}>✅ Approved</option>
                    <option value="pending" {{ old('status',$event->status)=='pending'?'selected':'' }}>⏳ Pending</option>
                    <option value="rejected" {{ old('status',$event->status)=='rejected'?'selected':'' }}>❌ Rejected</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition text-sm">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('admin.events.show', $event) }}" class="bg-gray-100 text-gray-700 px-8 py-2.5 rounded-xl font-bold hover:bg-gray-200 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection