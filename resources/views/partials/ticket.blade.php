{{-- Tiket kaca berisi kode registrasi. Variabel: $registration (dengan relasi event) --}}
@php
    $e = $registration->event;
    $done = ! $registration->isActive() || $e->isFinished();
@endphp
<div class="ticket tone-{{ $e->tone }} {{ $done ? 'is-done' : '' }}">
    <div class="ticket-top">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <span class="pill pill-tone"><i class="bi {{ $e->icon }}"></i> {{ $e->category }}</span>
            @include('partials.status-pill', ['registration' => $registration])
        </div>
        <h3><a href="{{ route('events.show', $e) }}" class="text-white">{{ $e->title }}</a></h3>
        <div class="meta">
            <span><i class="bi bi-calendar-event"></i>{{ $e->event_date->translatedFormat('l, d F Y') }}</span>
            <span><i class="bi bi-clock"></i>{{ $e->time_range }}</span>
            <span><i class="bi bi-geo-alt"></i>{{ $e->location }}</span>
        </div>
    </div>
    <div class="ticket-bottom">
        <div>
            <div class="ticket-label">Kode registrasi untuk presensi</div>
            <div class="ticket-code">{{ $registration->code }}</div>
        </div>
        <div class="ticket-bar" aria-hidden="true"></div>
    </div>
</div>
