<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /** kategori beserta ikon Bootstrap Icons dan kelas warna */
    public const CATEGORIES = [
        'Seminar'    => ['icon' => 'bi-mic',            'tone' => 'violet'],
        'Webinar'    => ['icon' => 'bi-camera-video',   'tone' => 'cyan'],
        'Workshop'   => ['icon' => 'bi-tools',          'tone' => 'amber'],
        'Kompetisi'  => ['icon' => 'bi-trophy',         'tone' => 'coral'],
        'Organisasi' => ['icon' => 'bi-people',         'tone' => 'mint'],
    ];

    protected $fillable = [
        'user_id', 'title', 'description', 'category', 'location',
        'organizer', 'event_date', 'start_time', 'end_time', 'quota',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'quota' => 'integer',
        ];
    }

    /* ---------- Relasi ---------- */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /* ---------- Scope ---------- */

    /** Tambahkan hitungan taken_count (pendaftar aktif) dan attended_count. */
    public function scopeWithStats(Builder $query): Builder
    {
        return $query->withCount([
            'registrations as taken_count' => fn ($q) => $q->whereIn('status', Registration::ACTIVE_STATUSES),
            'registrations as confirmed_count' => fn ($q) => $q->where('status', Registration::STATUS_CONFIRMED),
            'registrations as attended_count' => fn ($q) => $q->whereNotNull('attended_at'),
        ]);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereDate('event_date', '>=', today());
    }

    /** Pencarian & filter katalog: kata kunci, kategori, tanggal, lokasi. */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, function ($q, $keyword) {
                $q->where(function ($w) use ($keyword) {
                    $w->where('title', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%")
                      ->orWhere('organizer', 'like', "%{$keyword}%");
                });
            })
            ->when($filters['category'] ?? null, fn ($q, $c) => $q->where('category', $c))
            ->when($filters['location'] ?? null, fn ($q, $l) => $q->where('location', 'like', "%{$l}%"))
            ->when($filters['date'] ?? null, fn ($q, $d) => $q->whereDate('event_date', '>=', $d));
    }

    /* ---------- Helper tampilan ---------- */

    public function isFinished(): bool
    {
        return $this->event_date->lt(today());
    }

    public function getRemainingAttribute(): int
    {
        $taken = $this->taken_count
            ?? $this->registrations()->whereIn('status', Registration::ACTIVE_STATUSES)->count();

        return max(0, $this->quota - $taken);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->remaining <= 0;
    }

    public function getFillPercentAttribute(): int
    {
        if ($this->quota <= 0) {
            return 0;
        }
        $taken = $this->quota - $this->remaining;

        return (int) min(100, round($taken / $this->quota * 100));
    }

    public function getTimeRangeAttribute(): string
    {
        return substr($this->start_time, 0, 5) . ' – ' . substr($this->end_time, 0, 5) . ' WIB';
    }

    public function getToneAttribute(): string
    {
        return self::CATEGORIES[$this->category]['tone'] ?? 'violet';
    }

    public function getIconAttribute(): string
    {
        return self::CATEGORIES[$this->category]['icon'] ?? 'bi-calendar-event';
    }
}
