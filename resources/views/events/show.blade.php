@extends('layouts.app')
@section('title', $event->title)
@section('content')
<section class="bg-gray-100 py-6">
    <div class="max-w-4xl mx-auto px-4">
        <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Kegiatan
        </a>
    </div>
</section>

<section class="py-10">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="relative h-80">
                @if($event->image_url)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-emerald-100 flex items-center justify-center">
                        <i class="fas fa-image text-8xl text-gray-300"></i>
                    </div>
                @endif
                <div class="absolute top-4 right-4 bg-white/90 px-4 py-2 rounded-full text-sm font-bold text-gray-800">
                    {{ $event->category }}
                </div>
            </div>

            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $event->title }}</h1>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                            <div><p class="text-xs text-gray-500 uppercase font-semibold">Tanggal</p><p class="font-bold text-gray-900">{{ $event->formatted_date }}</p></div>
                        </div>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-clock text-emerald-600 text-xl"></i>
                            <div><p class="text-xs text-gray-500 uppercase font-semibold">Waktu</p><p class="font-bold text-gray-900">{{ $event->formatted_time }} WIB</p></div>
                        </div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-xl border border-red-200">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-map-marker-alt text-red-600 text-xl"></i>
                            <div><p class="text-xs text-gray-500 uppercase font-semibold">Lokasi</p><p class="font-bold text-gray-900">{{ $event->location }}</p></div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Deskripsi</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $event->description }}</p>
                </div>

                @if($event->organizer)
                <div class="mb-6 bg-gray-50 p-5 rounded-xl border">
                    <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2"><i class="fas fa-building text-blue-600"></i> Penyelenggara</h3>
                    <p class="text-gray-600">{{ $event->organizer }}</p>
                </div>
                @endif

                @if($event->requirements)
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2"><i class="fas fa-list-check text-emerald-600"></i> Persyaratan</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $event->requirements }}</p>
                </div>
                @endif

                <div class="mb-8 bg-green-50 p-5 rounded-xl border border-green-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2"><i class="fas fa-users text-green-600"></i> Pendaftar Relawan</h3>
                    <p class="text-gray-700 font-semibold mb-3">Total: <span class="text-green-600">{{ $count }} orang</span></p>
                    @if($registrants->isNotEmpty())
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($registrants as $r)
                                <div class="bg-white p-2 rounded-lg border border-gray-200 text-sm text-gray-700">
                                    <i class="fas fa-user text-green-500 mr-1"></i>{{ $r->name }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic text-sm">Belum ada pendaftar.</p>
                    @endif
                </div>

                <div class="flex gap-4 pt-6 border-t">
                    <button onclick="openModal()" class="flex-1 bg-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-700 transition shadow-lg">
                        <i class="fas fa-check-circle mr-2"></i> Daftar Sekarang
                    </button>
                    <a href="{{ route('events.index') }}" class="flex-1 bg-gray-100 text-gray-700 py-4 rounded-xl font-bold text-lg hover:bg-gray-200 transition text-center border">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MODAL DAFTAR --}}
<div id="registrationModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
        <div class="bg-blue-600 p-6 text-center text-white">
            <i class="fas fa-hands-helping text-3xl mb-2"></i>
            <h3 class="text-xl font-bold">Gabung Relawan</h3>
            <p class="text-blue-100 text-sm">Sedikit bantuanmu berarti besar bagi mereka.</p>
        </div>
        <button onclick="closeModal()" class="absolute top-4 right-4 text-white/80 hover:text-white"><i class="fas fa-times text-xl"></i></button>
        <div class="p-6">
            <div id="formStatus" class="hidden mb-4 p-4 rounded-xl text-sm font-medium"></div>
            <form id="volunteerForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Masukkan nama lengkap" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required placeholder="nama@email.com" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp</label>
                    <input type="tel" name="phone" required placeholder="0812xxxx" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <button type="submit" id="submitBtn" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                    Kirim Pendaftaran <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openModal() {
    const m = document.getElementById('registrationModal');
    m.classList.remove('hidden'); m.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    const m = document.getElementById('registrationModal');
    m.classList.add('hidden'); m.classList.remove('flex');
    document.body.style.overflow = 'auto';
}
document.getElementById('volunteerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const status = document.getElementById('formStatus');
    const data = new FormData(this);
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    try {
        const res = await fetch("{{ route('events.register', $event) }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: data
        });
        const result = await res.json();
        status.classList.remove('hidden');
        if (result.success) {
            status.className = 'mb-4 p-4 rounded-xl text-sm font-medium bg-green-50 text-green-700 border border-green-200';
            status.innerHTML = '✅ ' + result.message;
            if (typeof showToast === 'function') {
                showToast('success', result.message);
            }
            this.reset();
            setTimeout(closeModal, 3000);
        } else {
            status.className = 'mb-4 p-4 rounded-xl text-sm font-medium bg-red-50 text-red-700 border border-red-200';
            status.innerHTML = '❌ ' + (result.message || 'Terjadi kesalahan.');
            if (typeof showToast === 'function') {
                showToast('error', result.message || 'Terjadi kesalahan.');
            }
        }
    } catch(err) {
        status.classList.remove('hidden');
        status.className = 'mb-4 p-4 rounded-xl text-sm font-medium bg-red-50 text-red-700 border border-red-200';
        status.innerHTML = '❌ Terjadi kesalahan koneksi.';
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Kirim Pendaftaran <i class="fas fa-paper-plane ml-2"></i>';
    }
});
</script>
@endpush
@endsection