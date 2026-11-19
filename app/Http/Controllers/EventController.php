<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /** Katalog acara dengan pencarian & filter. */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'category', 'location', 'date']);

        // Abaikan nilai filter yang tidak valid agar halaman tetap tampil.
        if (! empty($filters['date']) && strtotime($filters['date']) === false) {
            unset($filters['date']);
        }
        if (! empty($filters['category']) && ! array_key_exists($filters['category'], Event::CATEGORIES)) {
            unset($filters['category']);
        }

        $showPast = $request->boolean('past');

        $events = Event::withStats()
            ->filter($filters)
            ->when(! $showPast, fn ($q) => $q->upcoming())
            ->when(
                $showPast,
                fn ($q) => $q->orderByDesc('event_date')->orderByDesc('start_time'),
                fn ($q) => $q->orderBy('event_date')->orderBy('start_time')
            )
            ->paginate(9)
            ->withQueryString();

        return view('events.index', [
            'events'     => $events,
            'filters'    => $filters,
            'showPast'   => $showPast,
            'categories' => array_keys(Event::CATEGORIES),
        ]);
    }

    /** Detail acara + tombol pendaftaran. */
    public function show(Event $event)
    {
        $event = Event::withStats()->with('owner')->findOrFail($event->id);

        $registration = auth()->check()
            ? $event->registrations()->where('user_id', auth()->id())->first()
            : null;

        $related = Event::withStats()->upcoming()
            ->where('category', $event->category)
            ->where('id', '!=', $event->id)
            ->orderBy('event_date')
            ->take(3)->get();

        return view('events.show', compact('event', 'registration', 'related'));
    }
}
