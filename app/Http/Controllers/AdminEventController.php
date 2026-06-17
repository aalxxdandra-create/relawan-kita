<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class AdminEventController extends Controller {

    public function developerDashboard() {
        $events = Event::with('user')->latest()->paginate(10);
        $stats = [
            'total' => Event::count(),
            'approved' => Event::where('status', 'approved')->count(),
            'pending' => Event::where('status', 'pending')->count(),
            'rejected' => Event::where('status', 'rejected')->count(),
        ];

        return view('developer.dashboard', compact('events', 'stats'));
    }

    public function index(Request $request) {
        $query = Event::with('user');

        if (auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title','like',"%$s%")
                  ->orWhere('organizer','like',"%$s%");
            });
        }
        if ($request->filled('status'))   $query->where('status',   $request->status);
        if ($request->filled('category')) $query->where('category', $request->category);

        $events = $query->latest()->paginate(10)->withQueryString();

        $baseQuery = Event::query();
        if (auth()->user()->isAdmin()) {
            $baseQuery->where('user_id', auth()->id());
        }

        $stats  = [
            'total'    => (clone $baseQuery)->count(),
            'approved' => (clone $baseQuery)->where('status','approved')->count(),
            'pending'  => (clone $baseQuery)->where('status','pending')->count(),
            'rejected' => (clone $baseQuery)->where('status','rejected')->count(),
        ];
        return view('admin.events.index', compact('events','stats'));
    }

    public function show(Event $event) {
        if (auth()->user()->isAdmin() && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        $event->load(['registrations' => fn($query) => $query->latest(), 'user']);
        return view('admin.events.show', compact('event'));
    }

    public function create() { return view('admin.events.create'); }

    public function store(Request $request) {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'location'     => 'required|string|max:255',
            'description'  => 'required|string',
            'event_date'   => 'required|date|after_or_equal:today',
            'event_time'   => 'required',
            'organizer'    => 'required|string|max:255',
            'requirements' => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
        ], [
            'event_date.after_or_equal' => 'Tanggal tidak valid: Event tidak dapat dibuat di masa lalu.'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        $validated['user_id'] = auth()->id();
        $validated['status']  = 'pending';
        Event::create($validated);
        return redirect()->route('admin.events.index')->with('success','Event berhasil diajukan!');
    }

    public function edit(Event $event) {
        if (auth()->user()->isAdmin() && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event) {
        if (auth()->user()->isAdmin() && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'location'     => 'required|string|max:255',
            'description'  => 'required|string',
            'event_date'   => 'required|date|after_or_equal:today',
            'event_time'   => 'required',
            'organizer'    => 'required|string|max:255',
            'requirements' => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
        ], [
            'event_date.after_or_equal' => 'Tanggal tidak valid: Event tidak dapat dibuat di masa lalu.'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        $event->update($validated);
        return redirect()->route('admin.events.show',$event)->with('success','Event berhasil diperbarui!');
    }

    public function destroy(Event $event) {
        if (auth()->user()->isAdmin() && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        if ($event->registrations()->exists()) {
            return redirect()->back()->with('error', 'Gagal menghapus: Terdapat pendaftar pada event ini.');
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success','Event berhasil dihapus!');
    }

    public function updateStatus(Request $request, Event $event) {
        if (!auth()->user()->isDeveloper()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin mengubah status event.');
        }

        $request->validate(['status' => 'required|in:approved,rejected,pending']);
        $event->update(['status' => $request->status]);
        $msgs = ['approved'=>'Event disetujui!','rejected'=>'Event ditolak.','pending'=>'Status dikembalikan ke pending.'];
        return redirect()->back()->with('success', $msgs[$request->status]);
    }

    public function updateRegistrationStatus(Request $request, Event $event, Registration $registration) {
        if (!auth()->user()->isDeveloper()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin mengubah status pendaftar.');
        }
        if ($registration->event_id !== $event->id) {
            abort(404);
        }
        $request->validate(['status' => 'required|in:approved,rejected,pending']);
        $registration->update(['status' => $request->status]);
        $msgs = ['approved'=>'Pendaftar disetujui!','rejected'=>'Pendaftar ditolak.','pending'=>'Status pendaftaran dikembalikan ke pending.'];
        return redirect()->back()->with('success', $msgs[$request->status]);
    }
}