@extends('layouts.app')

@section('title', 'Dashboard peserta')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title mb-1">Halo, {{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}</h1>
            <p class="muted mb-0">Jadwal acara dan kode registrasimu ada di sini.</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn btn-glow"><i class="bi bi-search"></i> Cari acara lain</a>
    </div>

    <div class="row g-3">
        <div class="col-sm-4"><div class="glass kpi tone-violet"><i class="bi bi-ticket-perforated kpi-icon"></i><span class="kpi-value">{{ $stats['active'] }}</span><span class="kpi-label">Pendaftaran aktif</span></div></div>
        <div class="col-sm-4"><div class="glass kpi tone-cyan"><i class="bi bi-calendar-check kpi-icon"></i><span class="kpi-value">{{ $stats['upcoming'] }}</span><span class="kpi-label">Acara mendatang</span></div></div>
        <div class="col-sm-4"><div class="glass kpi tone-mint"><i class="bi bi-patch-check kpi-icon"></i><span class="kpi-value">{{ $stats['attended'] }}</span><span class="kpi-label">Acara yang kamu hadiri</span></div></div>
    </div>

    {{-- ============ Jadwal & tiket ============ --}}
    <section class="section">
        <div class="section-head"><h2 class="section-title">Jadwal dan kode registrasi</h2></div>

        @if ($upcoming->isEmpty())
            <div class="glass empty">
                <i class="bi bi-ticket-perforated"></i>
                <h3 class="h5 mt-3">Belum ada acara yang akan kamu ikuti</h3>
                <p class="muted">Pilih acara di katalog, daftar, dan tiketmu akan muncul di sini.</p>
                <a href="{{ route('events.index') }}" class="btn btn-glow">Buka katalog acara</a>
            </div>
        @else
            <div class="row g-4">
                @foreach ($upcoming as $reg)
                    <div class="col-md-6 col-xl-4">
                        @include('partials.ticket', ['registration' => $reg])
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('events.show', $reg->event) }}" class="btn btn-glass btn-sm">Detail acara</a>
                            @if (! $reg->hasAttended())
                                <form method="POST" action="{{ route('registrations.cancel', $reg) }}" onsubmit="return confirm('Batalkan pendaftaran untuk acara ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-soft-danger btn-sm" type="submit">Batalkan pendaftaran</button>
                                </form>
                            @else
                                <span class="pill pill-present"><i class="bi bi-check2"></i> Sudah hadir</span>
                            @endif
                        </div>
                        @if ($reg->status === 'pending')
                            <p class="form-hint">Kode baru bisa dipakai presensi setelah panitia mengonfirmasi pendaftaranmu.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ============ Riwayat ============ --}}
    <section class="section">
        <div class="section-head"><h2 class="section-title">Riwayat keikutsertaan</h2></div>
        @if ($history->isEmpty())
            <div class="glass empty"><p class="muted mb-0">Riwayat akan muncul setelah acara yang kamu ikuti selesai.</p></div>
        @else
            <div class="glass table-wrap">
                <table class="table table-glass">
                    <thead>
                        <tr><th>Acara</th><th>Tanggal</th><th>Kode</th><th>Status</th><th>Kehadiran</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $reg)
                            <tr>
                                <td><a href="{{ route('events.show', $reg->event) }}" class="text-white fw-semibold">{{ $reg->event->title }}</a><div class="faint small">{{ $reg->event->category }} · {{ $reg->event->location }}</div></td>
                                <td class="text-nowrap">{{ $reg->event->event_date->translatedFormat('d M Y') }}</td>
                                <td><span class="code-mono">{{ $reg->code }}</span></td>
                                <td>@include('partials.status-pill', ['registration' => $reg])</td>
                                <td>
                                    @if ($reg->hasAttended())
                                        <span class="pill pill-present"><i class="bi bi-check2"></i> Hadir {{ $reg->attended_at->format('H:i') }}</span>
                                    @elseif ($reg->status === 'confirmed')
                                        <span class="pill pill-absent">Tidak hadir</span>
                                    @else
                                        <span class="faint">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
