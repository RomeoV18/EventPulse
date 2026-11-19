<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Services\ParticipationStats;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $events = $request->user()->events()
            ->withStats()
            ->orderByDesc('event_date')
            ->get();

        $stats = ParticipationStats::summarize($events);

        // Rekap per kategori
        $byCategory = $events->groupBy('category')->map(fn ($group) => [
            'events'      => $group->count(),
            'registrants' => (int) $group->sum('taken_count'),
            'attended'    => (int) $group->sum('attended_count'),
        ])->sortByDesc('registrants');

        // Sebaran status pendaftaran
        $statusCounts = Registration::whereIn('event_id', $events->pluck('id'))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Grafik: 8 acara terbaru (urut kronologis)
        $chartEvents = $events->take(8)->sortBy('event_date')->values();

        return view('panitia.reports.index', compact('events', 'stats', 'byCategory', 'statusCounts', 'chartEvents'));
    }

    /** Unduh rekap seluruh acara sebagai CSV. */
    public function export(Request $request)
    {
        $events = $request->user()->events()->withStats()->orderBy('event_date')->get();

        return response()->streamDownload(function () use ($events) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Acara', 'Kategori', 'Tanggal', 'Lokasi', 'Kuota', 'Pendaftar aktif', 'Terkonfirmasi', 'Hadir', 'Kehadiran (%)']);
            foreach ($events as $e) {
                fputcsv($out, [
                    $e->title,
                    $e->category,
                    $e->event_date->format('Y-m-d'),
                    $e->location,
                    $e->quota,
                    $e->taken_count,
                    $e->confirmed_count,
                    $e->attended_count,
                    ParticipationStats::eventRate($e),
                ]);
            }
            fclose($out);
        }, 'laporan-eventpulse-' . now()->format('Ymd') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
