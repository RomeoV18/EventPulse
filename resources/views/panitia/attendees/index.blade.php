@extends('layouts.app')

@section('title', 'Peserta ' . $event->title)

@section('content')
    <div class="mt-4 mb-2">
        <a href="{{ route('panitia.events.index') }}" class="muted"><i class="bi bi-chevron-left"></i> Kembali ke daftar acara</a>
    </div>

    <div class="page-head" style="margin-top: .6rem">
        <div class="tone-{{ $event->tone }}">
            <span class="pill pill-tone mb-2"><i class="bi {{ $event->icon }}"></i> {{ $event->category }}</span>
            <h1 class="page-title mb-1">{{ $event->title }}</h1>
            <p class="muted mb-0">{{ $event->event_date->translatedFormat('l, d F Y') }} · {{ $event->time_range }} · {{ $event->location }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('panitia.events.edit', $event) }}" class="btn btn-glass"><i class="bi bi-pencil"></i> Ubah acara</a>
            <a href="{{ route('panitia.attendees.export', $event) }}" class="btn btn-glass"><i class="bi bi-download"></i> Ekspor CSV</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="glass kpi tone-violet"><span class="kpi-value">{{ $event->taken_count }}<small class="faint fs-6">/{{ $event->quota }}</small></span><span class="kpi-label">Pendaftar / kuota</span></div></div>
        <div class="col-6 col-lg-3"><div class="glass kpi tone-amber"><span class="kpi-value">{{ $statusCounts['pending'] ?? 0 }}</span><span class="kpi-label">Menunggu konfirmasi</span></div></div>
        <div class="col-6 col-lg-3"><div class="glass kpi tone-cyan"><span class="kpi-value">{{ $event->confirmed_count }}</span><span class="kpi-label">Terkonfirmasi</span></div></div>
        <div class="col-6 col-lg-3"><div class="glass kpi tone-mint"><span class="kpi-value">{{ $event->attended_count }}</span><span class="kpi-label">Hadir</span><span class="kpi-note">{{ \App\Services\ParticipationStats::eventRate($event) }}% dari pendaftar</span></div></div>
    </div>

    {{-- ============ Presensi dengan kode ============ --}}
    <div class="glass pad mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <h2 class="h5 mb-1">Catat kehadiran dengan kode</h2>
                <p class="muted small mb-0">Minta peserta menunjukkan kode registrasinya (contoh: EP-7K3QX9). Presensi dibuka pada hari acara.</p>
            </div>
            <div class="col-lg-7">
                <form method="POST" action="{{ route('panitia.attendees.checkinCode', $event) }}" class="d-flex flex-wrap gap-2" novalidate>
                    @csrf
                    <input type="text" name="code" value="{{ old('code') }}" class="form-control @error('code') is-invalid @enderror" style="flex: 1 1 200px; text-transform: uppercase; letter-spacing: .08em; font-weight: 700"
                           placeholder="EP-XXXXXX" aria-label="Kode registrasi" autocomplete="off" required>
                    <button type="submit" class="btn btn-glow"><i class="bi bi-check2-circle"></i> Catat hadir</button>
                </form>
                @error('code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- ============ Filter ============ --}}
    <form method="GET" action="{{ route('panitia.attendees.index', $event) }}" class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <input type="search" name="q" value="{{ $keyword }}" class="form-control" style="max-width: 300px" placeholder="Cari nama, email, atau kode" aria-label="Cari peserta">
        <select name="status" class="form-select" style="max-width: 220px" aria-label="Filter status">
            <option value="">Semua status</option>
            @foreach (\App\Models\Registration::STATUS_LABELS as $key => $label)
                <option value="{{ $key }}" @selected($status === $key)>{{ $label }} ({{ $statusCounts[$key] ?? 0 }})</option>
            @endforeach
        </select>
        <button class="btn btn-glass" type="submit">Terapkan</button>
        @if ($status || $keyword)
            <a href="{{ route('panitia.attendees.index', $event) }}" class="btn btn-glass">Atur ulang</a>
        @endif

        @if (($statusCounts['pending'] ?? 0) > 0)
            <button type="submit" form="confirm-all" class="btn btn-soft-ok ms-lg-auto">
                <i class="bi bi-check2-all"></i> Konfirmasi semua ({{ $statusCounts['pending'] }})
            </button>
        @endif
    </form>
    <form id="confirm-all" method="POST" action="{{ route('panitia.attendees.confirmAll', $event) }}" class="d-none">@csrf</form>

    @if ($registrations->isEmpty())
        <div class="glass empty">
            <i class="bi bi-people"></i>
            <h2 class="h5 mt-3">{{ $status || $keyword ? 'Tidak ada pendaftar yang cocok' : 'Belum ada pendaftar' }}</h2>
            <p class="muted mb-0">{{ $status || $keyword ? 'Ubah atau atur ulang filter untuk melihat semua pendaftar.' : 'Pendaftar akan muncul di sini begitu peserta mendaftar.' }}</p>
        </div>
    @else
        <div class="glass table-wrap">
            <table class="table table-glass">
                <thead>
                    <tr><th>Peserta</th><th>Kode</th><th>Status</th><th>Kehadiran</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($registrations as $reg)
                        <tr>
                            <td style="min-width: 220px">
                                <div class="fw-semibold">{{ $reg->user->name }}</div>
                                <div class="faint small">{{ $reg->user->email }}@if ($reg->user->phone) · {{ $reg->user->phone }}@endif</div>
                            </td>
                            <td><span class="code-mono">{{ $reg->code }}</span></td>
                            <td>@include('partials.status-pill', ['registration' => $reg])</td>
                            <td>
                                @if ($reg->hasAttended())
                                    <span class="pill pill-present"><i class="bi bi-check2"></i> {{ $reg->attended_at->format('H:i') }}</span>
                                @else
                                    <span class="faint">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    @if ($reg->status === 'pending')
                                        <form method="POST" action="{{ route('panitia.attendees.status', $reg) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button class="btn btn-soft-ok btn-sm" type="submit">Konfirmasi</button>
                                        </form>
                                        <form method="POST" action="{{ route('panitia.attendees.status', $reg) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="btn btn-soft-danger btn-sm" type="submit">Tolak</button>
                                        </form>
                                    @elseif ($reg->status === 'confirmed' && ! $reg->hasAttended())
                                        <form method="POST" action="{{ route('panitia.attendees.checkin', $reg) }}">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-glow btn-sm" type="submit"><i class="bi bi-check2"></i> Hadir</button>
                                        </form>
                                    @elseif ($reg->hasAttended())
                                        <form method="POST" action="{{ route('panitia.attendees.undoCheckin', $reg) }}">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-glass btn-sm" type="submit">Batalkan hadir</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center">{{ $registrations->links() }}</div>
    @endif
@endsection
