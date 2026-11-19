<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Menghitung ringkasan partisipasi dari kumpulan acara yang dimuat dengan
 * Event::withStats() (taken_count, confirmed_count, attended_count).
 */
class ParticipationStats
{
    public static function summarize(Collection $events): array
    {
        // Persentase kehadiran hanya bermakna untuk acara yang sudah/sedang berlangsung.
        $measured = $events->filter(fn ($e) => $e->event_date->lte(today()));
        $measuredTaken = (int) $measured->sum('taken_count');
        $measuredAttended = (int) $measured->sum('attended_count');

        return [
            'events'          => $events->count(),
            'registrants'     => (int) $events->sum('taken_count'),
            'attended'        => (int) $events->sum('attended_count'),
            'remaining'       => (int) $events
                ->filter(fn ($e) => $e->event_date->gte(today()))
                ->sum(fn ($e) => $e->remaining),
            'measured_events' => $measured->count(),
            'rate'            => $measuredTaken > 0
                ? (int) round($measuredAttended / $measuredTaken * 100)
                : 0,
        ];
    }

    /** Persentase kehadiran satu acara (hadir / pendaftar aktif). */
    public static function eventRate($event): int
    {
        return $event->taken_count > 0
            ? (int) round($event->attended_count / $event->taken_count * 100)
            : 0;
    }
}
