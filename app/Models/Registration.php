<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Registration extends Model
{
    public const STATUS_PENDING   = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED  = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    /** Status yang memakai kuota. */
    public const ACTIVE_STATUSES = [self::STATUS_PENDING, self::STATUS_CONFIRMED];

    public const STATUS_LABELS = [
        self::STATUS_PENDING   => 'Menunggu konfirmasi',
        self::STATUS_CONFIRMED => 'Terkonfirmasi',
        self::STATUS_REJECTED  => 'Ditolak',
        self::STATUS_CANCELLED => 'Dibatalkan',
    ];

    protected $fillable = ['event_id', 'user_id', 'code', 'status', 'attended_at'];

    protected function casts(): array
    {
        return ['attended_at' => 'datetime'];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Buat kode unik, contoh: EP-7K3QX9 */
    public static function generateCode(): string
    {
        do {
            $code = 'EP-' . strtoupper(Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function isActive(): bool
    {
        return in_array($this->status, self::ACTIVE_STATUSES, true);
    }

    public function hasAttended(): bool
    {
        return $this->attended_at !== null;
    }
}
