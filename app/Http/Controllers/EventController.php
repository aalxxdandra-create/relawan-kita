<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class EventController extends Controller {

    public function index(Request $request) {
        $query = Event::approved();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title','like',"%$s%")
                  ->orWhere('location','like',"%$s%")
                  ->orWhere('organizer','like',"%$s%");
            });
        }
        if ($request->filled('category')) $query->where('category', $request->category);
        $events     = $query->orderBy('event_date','asc')->paginate(9)->withQueryString();
        $categories = Event::approved()->distinct()->pluck('category')->sort()->values();
        return view('events.index', compact('events','categories'));
    }

    public function show(Event $event) {
        if ($event->status !== 'approved') abort(404);
        $registrants = $event->registrations()->latest()->get();
        $count       = $registrants->count();
        return view('events.show', compact('event','registrants','count'));
    }

    public function create() {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengajukan event.');
        }
        return view('events.create');
    }

    public function store(Request $request) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengajukan event.');
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
            'image_url'    => 'nullable|url|max:500',
        ]);
        $validated['user_id'] = auth()->id();
        $validated['status']  = 'pending';
        Event::create($validated);
        return redirect()->route('events.index')->with(
            'success',
            'Pengajuan event berhasil dikirim. Event akan ditinjau sebelum ditampilkan ke publik.'
        );
    }

    public function register(Request $request, Event $event) {
        if ($event->status !== 'approved') {
            return response()->json(['success'=>false,'message'=>'Event tidak tersedia.'], 404);
        }
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);
        Registration::create(['event_id'=>$event->id, 'status' => 'pending'] + $validated);
        return response()->json(['success'=>true,'message'=>'Pendaftaran berhasil! Penyelenggara akan menghubungi Anda.']);
    }
}