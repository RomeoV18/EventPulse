<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $upcoming = Event::withStats()->upcoming()
            ->orderBy('event_date')->orderBy('start_time')
            ->take(6)->get();

        $popular = Event::withStats()->upcoming()
            ->orderByDesc('taken_count')->orderBy('event_date')
            ->take(3)->get();

        $categoryCounts = Event::upcoming()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $stats = [
            'events'        => Event::count(),
            'participants'  => User::where('role', User::ROLE_PESERTA)->count(),
            'registrations' => Registration::whereIn('status', Registration::ACTIVE_STATUSES)->count(),
        ];

        // Acara terdekat untuk "tiket" di hero
        $next = $upcoming->first();

        return view('home', compact('upcoming', 'popular', 'categoryCounts', 'stats', 'next'));
    }
}
