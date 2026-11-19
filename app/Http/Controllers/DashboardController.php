<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\ParticipationStats;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Dashboard peserta: pendaftaran, status, jadwal, kode registrasi, riwayat. */
    public function peserta(Request $request)
    {
        $registrations = $request->user()->registrations()
            ->with('event')
            ->get();

        $upcoming = $registrations
            ->filter(fn ($r) => $r->isActive() && ! $r->event->isFinished())
            ->sortBy(fn ($r) => $r->event->event_date->format('Y-m-d') . $r->event->start_time)
            ->values();

        $history = $registrations
            ->reject(fn ($r) => $upcoming->contains('id', $r->id))
            ->sortByDesc(fn ($r) => $r->event->event_date->format('Y-m-d'))
            ->values();

        $stats = [
            'active'   => $registrations->filter(fn ($r) => $r->isActive())->count(),
            'upcoming' => $upcoming->count(),
            'attended' => $registrations->filter(fn ($r) => $r->hasAttended())->count(),
        ];

        return view('peserta.dashboard', compact('upcoming', 'history', 'stats'));
    }

    /** Dashboard panitia: ringkasan acara yang dikelola. */
    public function panitia(Request $request)
    {
        $events = $request->user()->events()
            ->withStats()
            ->orderBy('event_date')
            ->get();

        $stats = ParticipationStats::summarize($events);

        $upcoming = $events->filter(fn ($e) => $e->event_date->gte(today()))->values();
        $finished = $events->filter(fn ($e) => $e->event_date->lt(today()))
            ->sortByDesc('event_date')->take(4)->values();

        return view('panitia.dashboard', compact('events', 'stats', 'upcoming', 'finished'));
    }
}
