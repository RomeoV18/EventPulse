@extends('layouts.app')

@section('title', 'Laporan partisipasi')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title mb-2">Laporan partisipasi</h1>
            @include('partials.panitia-subnav')
        </div>
        <a href="{{ route('panitia.reports.export') }}" class="btn btn-glass"><i class="bi bi-download"></i> Unduh rekap CSV</a>
    </div>

    @if ($events->isEmpty())
        <div class="glass empty">
            <i class="bi bi-bar-chart-line"></i>
            <h2 class="h5 mt-3">Belum ada data untuk dilaporkan</h2>
            <p class="muted">Laporan terisi setelah kamu membuat acara dan peserta mulai mendaftar.</p>
            <a href="{{ route('panitia.events.create') }}" class="btn btn-glow">Buat acara</a>
        </div>
    @else
        <div class="row g-3">
            <div class="col-6 col-lg-3"><div class="glass kpi tone-violet"><i class="bi bi-calendar2-event kpi-icon"></i><span class="kpi-value">{{ $stats['events'] }}</span><span class="kpi-label">Total acara</span></div></div>
            <div class="col-6 col-lg-3"><div class="glass kpi tone-cyan"><i class="bi bi-person-check kpi-icon"></i><span class="kpi-value">{{ $stats['registrants'] }}</span><span class="kpi-label">Total pendaftar</span><span class="kpi-note">tidak termasuk yang batal/ditolak</span></div></div>
            <div class="col-6 col-lg-3"><div class="glass kpi tone-mint"><i class="bi bi-patch-check kpi-icon"></i><span class="kpi-value">{{ $stats['attended'] }}</span><span class="kpi-label">Peserta hadir</span></div></div>
            <div class="col-6 col-lg-3"><div class="glass kpi tone-amber"><i class="bi bi-percent kpi-icon"></i><span class="kpi-value">{{ $stats['rate'] }}%</span><span class="kpi-label">Persentase kehadiran</span><span class="kpi-note">hadir ÷ pendaftar, acara yang sudah berlangsung</span></div></div>
        </div>

        <div class="row g-4 mt-1">
            {{-- ============ Grafik pendaftar vs hadir ============ --}}
            <div class="col-lg-8">
                <section class="glass pad h-100">
                    <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                        <h2 class="h5 mb-0">Pendaftar dan kehadiran per acara</h2>
                        <div class="legend">
                            <span><i style="background: linear-gradient(90deg,#7c5cff,#3aa6f0)"></i>Pendaftar (terhadap kuota)</span>
                            <span><i style="background: var(--mint)"></i>Hadir</span>
                        </div>
                    </div>
                    @foreach ($chartEvents as $event)
                        @php
                            $regPct = $event->quota ? min(100, round($event->taken_count / $event->quota * 100)) : 0;
                            $attPct = $event->quota ? min(100, round($event->attended_count / $event->quota * 100)) : 0;
                        @endphp
                        <div class="bar-row">
                            <div class="bar-name">{{ \Illuminate\Support\Str::limit($event->title, 44) }}<small>{{ $event->event_date->translatedFormat('d M Y') }}</small></div>
                            <div class="bar-track" role="img" aria-label="{{ $event->taken_count }} pendaftar dan {{ $event->attended_count }} hadir dari kuota {{ $event->quota }}">
                                <span class="b-reg" style="width: {{ $regPct }}%"></span>
                                <span class="b-att" style="width: {{ $attPct }}%"></span>
                            </div>
                            <div class="bar-value">{{ $event->attended_count }}/{{ $event->taken_count }}<small>kuota {{ $event->quota }}</small></div>
                        </div>
                    @endforeach
                </section>
            </div>

            {{-- ============ Donut kehadiran + status ============ --}}
            <div class="col-lg-4">
                <section class="glass pad h-100">
                    <h2 class="h5 mb-3">Kehadiran keseluruhan</h2>
                    <div class="donut" style="--p: {{ $stats['rate'] }}" role="img" aria-label="Kehadiran {{ $stats['rate'] }} persen">
                        <div class="donut-label"><b>{{ $stats['rate'] }}%</b><small>hadir</small></div>
                    </div>
                    <div class="divider"></div>
                    <h3 class="h6 mb-2">Status pendaftaran</h3>
                    <div class="d-grid gap-2">
                        @foreach (\App\Models\Registration::STATUS_LABELS as $key => $label)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="pill pill-status-{{ $key }}">{{ $label }}</span>
                                <strong>{{ $statusCounts[$key] ?? 0 }}</strong>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>

        {{-- ============ Rekap kategori ============ --}}
        <section class="section">
            <div class="section-head"><h2 class="section-title">Rekap per kategori</h2></div>
            <div class="row g-3">
                @foreach ($byCategory as $name => $row)
                    @php $meta = \App\Models\Event::CATEGORIES[$name] ?? ['icon' => 'bi-calendar-event', 'tone' => 'violet']; @endphp
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="glass kpi tone-{{ $meta['tone'] }}">
                            <i class="bi {{ $meta['icon'] }} kpi-icon"></i>
                            <span class="kpi-label">{{ $name }}</span>
                            <span class="kpi-value">{{ $row['registrants'] }}</span>
                            <span class="kpi-note">pendaftar di {{ $row['events'] }} acara · {{ $row['attended'] }} hadir</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ============ Tabel rekap ============ --}}
        <section class="section">
            <div class="section-head"><h2 class="section-title">Rekapitulasi seluruh acara</h2></div>
            <div class="glass table-wrap">
                <table class="table table-glass">
                    <thead>
                        <tr><th>Acara</th><th>Tanggal</th><th>Kuota</th><th>Pendaftar</th><th>Terkonfirmasi</th><th>Hadir</th><th style="min-width:150px">Kehadiran</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            @php $rate = \App\Services\ParticipationStats::eventRate($event); @endphp
                            <tr>
                                <td style="min-width: 240px"><a href="{{ route('panitia.attendees.index', $event) }}" class="text-white fw-semibold">{{ $event->title }}</a><div class="faint small">{{ $event->category }}</div></td>
                                <td class="text-nowrap">{{ $event->event_date->translatedFormat('d M Y') }}</td>
                                <td>{{ $event->quota }}</td>
                                <td>{{ $event->taken_count }}</td>
                                <td>{{ $event->confirmed_count }}</td>
                                <td>{{ $event->attended_count }}</td>
                                <td>
                                    @if ($event->event_date->gt(today()))
                                        <span class="faint small">Belum berlangsung</span>
                                    @else
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="meter flex-grow-1"><span style="width: {{ $rate }}%; background: var(--mint)"></span></div>
                                            <strong class="small">{{ $rate }}%</strong>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
