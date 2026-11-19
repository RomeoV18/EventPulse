{{-- Kartu acara. Variabel: $event (dimuat dengan Event::withStats()) --}}
<article class="event-card glass tone-{{ $event->tone }}">
    <div class="event-cover">
        <i class="bi {{ $event->icon }} cover-icon" aria-hidden="true"></i>
        <span class="pill" style="position:relative;background:rgba(10,8,40,.45);color:#fff;border-color:rgba(255,255,255,.3)">{{ $event->category }}</span>
        <div class="date-chip">
            <b>{{ $event->event_date->format('d') }}</b>
            <small>{{ $event->event_date->translatedFormat('M Y') }}</small>
        </div>
    </div>

    <div class="event-body">
        <h3 class="event-title"><a href="{{ route('events.show', $event) }}">{{ $event->title }}</a></h3>

        <div class="meta">
            <span><i class="bi bi-clock"></i>{{ $event->time_range }}</span>
            <span><i class="bi bi-geo-alt"></i>{{ $event->location }}</span>
        </div>

        <div class="mt-auto pt-2">
            <div class="meter {{ $event->fill_percent >= 80 ? 'hot' : '' }}" role="progressbar"
                 aria-label="Kuota terpakai" aria-valuenow="{{ $event->fill_percent }}" aria-valuemin="0" aria-valuemax="100">
                <span style="width: {{ $event->fill_percent }}%"></span>
            </div>
            <div class="quota-line">
                @if ($event->isFinished())
                    <span>Acara selesai</span>
                    <span>{{ $event->taken_count }} pendaftar</span>
                @elseif ($event->is_full)
                    <span class="text-warning fw-semibold">Kuota penuh</span>
                    <span>{{ $event->quota }} peserta</span>
                @else
                    <span>Sisa {{ $event->remaining }} kursi</span>
                    <span>{{ $event->taken_count }}/{{ $event->quota }}</span>
                @endif
            </div>
        </div>
    </div>
</article>
