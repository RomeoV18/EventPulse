@extends('layouts.app')

@section('title', 'Dashboard panitia')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title mb-2">Dashboard panitia</h1>
            @include('partials.panitia-subnav')
        </div>
        <a href="{{ route('panitia.events.create') }}" class="btn btn-glow"><i class="bi bi-plus-lg"></i> Buat acara</a>
    </div>

    <div class="row g-3">
        <div class="col-6 col-lg-3"><div class="glass kpi tone-violet"><i class="bi bi-calendar2-event kpi-icon"></i><span class="kpi-value">{{ $stats['events'] }}</span><span class="kpi-label">Acara dikelola</span></div></div>
        <div class="col-6 col-lg-3"><div class="glass kpi tone-cyan"><i class="bi bi-person-check kpi-icon"></i><span class="kpi-value">{{ $stats['registrants'] }}</span><span class="kpi-label">Pendaftar aktif</span></div></div>
        <div class="col-6 col-lg-3"><div class="glass kpi tone-amber"><i class="bi bi-ticket-perforated kpi-icon"></i><span class="kpi-value">{{ $stats['remaining'] }}</span><span class="kpi-label">Kuota tersisa</span><span class="kpi-note">di acara mendatang</span></div></div>
        <div class="col-6 col-lg-3"><div class="glass kpi tone-mint"><i class="bi bi-graph-up-arrow kpi-icon"></i><span class="kpi-value">{{ $stats['rate'] }}%</span><span class="kpi-label">Kehadiran</span><span class="kpi-note">{{ $stats['measured_events'] }} acara yang sudah berlangsung</span></div></div>
    </div>

    <section class="section">
        <div class="section-head">
            <h2 class="section-title">Acara mendatang</h2>
            <a href="{{ route('panitia.events.index') }}" class="fw-semibold">Kelola semua acara</a>
        </div>

        @if ($upcoming->isEmpty())
            <div class="glass empty">
                <i class="bi bi-calendar-plus"></i>
                <h3 class="h5 mt-3">Belum ada acara mendatang</h3>
                <p class="muted">Buat acara pertama agar peserta bisa mulai mendaftar.</p>
                <a href="{{ route('panitia.events.create') }}" class="btn btn-glow">Buat acara</a>
            </div>
        @else
            <div class="d-grid gap-3">
                @foreach ($upcoming as $event)
                    <div class="glass pad-sm tone-{{ $event->tone }}">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-5">
                                <span class="pill pill-tone mb-1"><i class="bi {{ $event->icon }}"></i> {{ $event->category }}</span>
                                <h3 class="h6 mb-1" style="font-size:1.05rem">{{ $event->title }}</h3>
                                <div class="muted small">{{ $event->event_date->translatedFormat('l, d F Y') }} · {{ $event->time_range }}</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="meter {{ $event->fill_percent >= 80 ? 'hot' : '' }}"><span style="width: {{ $event->fill_percent }}%"></span></div>
                                <div class="quota-line">
                                    <span>{{ $event->taken_count }} pendaftar · {{ $event->confirmed_count }} terkonfirmasi</span>
                                    <span>{{ $event->is_full ? 'Penuh' : 'Sisa ' . $event->remaining }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 row-actions">
                                <a href="{{ route('panitia.attendees.index', $event) }}" class="btn btn-glass btn-sm"><i class="bi bi-people"></i> Peserta</a>
                                <a href="{{ route('panitia.events.edit', $event) }}" class="btn btn-glass btn-sm"><i class="bi bi-pencil"></i> Ubah</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    @if ($finished->isNotEmpty())
        <section class="section">
            <div class="section-head">
                <h2 class="section-title">Baru saja selesai</h2>
                <a href="{{ route('panitia.reports.index') }}" class="fw-semibold">Buka laporan lengkap</a>
            </div>
            <div class="glass table-wrap">
                <table class="table table-glass">
                    <thead><tr><th>Acara</th><th>Tanggal</th><th>Pendaftar</th><th>Hadir</th><th style="min-width:160px">Kehadiran</th></tr></thead>
                    <tbody>
                        @foreach ($finished as $event)
                            @php $rate = \App\Services\ParticipationStats::eventRate($event); @endphp
                            <tr>
                                <td class="fw-semibold">{{ $event->title }}</td>
                                <td class="text-nowrap">{{ $event->event_date->translatedFormat('d M Y') }}</td>
                                <td>{{ $event->taken_count }}</td>
                                <td>{{ $event->attended_count }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="meter flex-grow-1"><span style="width: {{ $rate }}%; background: var(--mint)"></span></div>
                                        <strong class="small">{{ $rate }}%</strong>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
