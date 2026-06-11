<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class AdminEventController extends Controller {

    public function index(Request $request) {
        $query = Event::with('user');
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
        $stats  = [
            'total'    => Event::count(),
            'approved' => Event::where('status','approved')->count(),
            'pending'  => Event::where('status','pending')->count(),
            'rejected' => Event::where('status','rejected')->count(),
        ];
        return view('admin.events.index', compact('events','stats'));
    }

    public function show(Event $event) {
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
            'event_date'   => 'required|date',
            'event_time'   => 'required',
            'organizer'    => 'required|string|max:255',
            'requirements' => 'nullable|string',
            'image_url'    => 'nullable|url|max:500',
        ]);
        $validated['user_id'] = auth()->id();
        $validated['status']  = 'approved';
        Event::create($validated);
        return redirect()->route('admin.events.index')->with('success','Event berhasil ditambahkan!');
    }

    public function edit(Event $event) { return view('admin.events.edit', compact('event')); }

    public function update(Request $request, Event $event) {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'location'     => 'required|string|max:255',
            'description'  => 'required|string',
            'event_date'   => 'required|date',
            'event_time'   => 'required',
            'organizer'    => 'required|string|max:255',
            'requirements' => 'nullable|string',
            'image_url'    => 'nullable|url|max:500',
            'status'       => 'required|in:pending,approved,rejected',
        ]);
        $event->update($validated);
        return redirect()->route('admin.events.show',$event)->with('success','Event berhasil diperbarui!');
    }

    public function destroy(Event $event) {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success','Event berhasil dihapus!');
    }

    public function updateStatus(Request $request, Event $event) {
        $request->validate(['status' => 'required|in:approved,rejected,pending']);
        $event->update(['status' => $request->status]);
        $msgs = ['approved'=>'Event disetujui!','rejected'=>'Event ditolak.','pending'=>'Status dikembalikan ke pending.'];
        return redirect()->back()->with('success', $msgs[$request->status]);
    }

    public function updateRegistrationStatus(Request $request, Event $event, Registration $registration) {
        if ($registration->event_id !== $event->id) {
            abort(404);
        }
        $request->validate(['status' => 'required|in:approved,rejected,pending']);
        $registration->update(['status' => $request->status]);
        $msgs = ['approved'=>'Pendaftar disetujui!','rejected'=>'Pendaftar ditolak.','pending'=>'Status pendaftaran dikembalikan ke pending.'];
        return redirect()->back()->with('success', $msgs[$request->status]);
    }
}