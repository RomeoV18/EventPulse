@extends('layouts.app')

@section('title', 'Acara & peserta')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title mb-2">Acara &amp; peserta</h1>
            @include('partials.panitia-subnav')
        </div>
        <a href="{{ route('panitia.events.create') }}" class="btn btn-glow"><i class="bi bi-plus-lg"></i> Buat acara</a>
    </div>

    @if ($events->isEmpty())
        <div class="glass empty">
            <i class="bi bi-calendar-plus"></i>
            <h2 class="h5 mt-3">Kamu belum membuat acara</h2>
            <p class="muted">Mulai dengan judul, jadwal, lokasi, dan kuota peserta.</p>
            <a href="{{ route('panitia.events.create') }}" class="btn btn-glow">Buat acara</a>
        </div>
    @else
        <div class="glass table-wrap">
            <table class="table table-glass">
                <thead>
                    <tr><th>Acara</th><th>Jadwal</th><th style="min-width:170px">Kuota</th><th>Hadir</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr class="tone-{{ $event->tone }}">
                            <td style="min-width: 240px">
                                <div class="fw-semibold">{{ $event->title }}</div>
                                <span class="pill pill-tone mt-1"><i class="bi {{ $event->icon }}"></i> {{ $event->category }}</span>
                                @if ($event->isFinished()) <span class="pill pill-status-cancelled mt-1">Selesai</span> @endif
                            </td>
                            <td class="text-nowrap">
                                {{ $event->event_date->translatedFormat('d M Y') }}
                                <div class="faint small">{{ $event->time_range }}</div>
                            </td>
                            <td>
                                <div class="meter {{ $event->fill_percent >= 80 ? 'hot' : '' }}"><span style="width: {{ $event->fill_percent }}%"></span></div>
                                <div class="quota-line"><span>{{ $event->taken_count }}/{{ $event->quota }}</span><span>{{ $event->fill_percent }}%</span></div>
                            </td>
                            <td>{{ $event->attended_count }}</td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('panitia.attendees.index', $event) }}" class="btn btn-glass btn-sm"><i class="bi bi-people"></i> Peserta</a>
                                    <a href="{{ route('panitia.events.edit', $event) }}" class="btn btn-glass btn-sm"><i class="bi bi-pencil"></i> Ubah</a>
                                    <form method="POST" action="{{ route('panitia.events.destroy', $event) }}"
                                          onsubmit="return confirm('Hapus acara &quot;{{ addslashes($event->title) }}&quot; beserta seluruh data pendaftarannya? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-sm" aria-label="Hapus {{ $event->title }}"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center">{{ $events->links() }}</div>
    @endif
@endsection
