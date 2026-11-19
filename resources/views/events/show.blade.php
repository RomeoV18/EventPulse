@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <div class="mt-4 mb-3">
        <a href="{{ route('events.index') }}" class="muted"><i class="bi bi-chevron-left"></i> Kembali ke katalog</a>
    </div>

    <div class="row g-4">
        {{-- ============ Detail ============ --}}
        <div class="col-lg-8">
            <article class="glass overflow-hidden tone-{{ $event->tone }}">
                <div class="event-cover" style="height: 170px; align-items: flex-end;">
                    <i class="bi {{ $event->icon }} cover-icon" style="font-size: 9rem;" aria-hidden="true"></i>
                    <span class="pill" style="position:relative;background:rgba(10,8,40,.45);color:#fff;border-color:rgba(255,255,255,.3)">
                        <i class="bi {{ $event->icon }}"></i> {{ $event->category }}
                    </span>
                    @if ($event->isFinished())
                        <span class="pill pill-status-cancelled" style="position:relative">Acara selesai</span>
                    @endif
                </div>

                <div class="pad">
                    <h1 class="page-title mb-1">{{ $event->title }}</h1>
                    <p class="muted mb-4">Diselenggarakan oleh {{ $event->organizer }}</p>

                    <div class="info-grid mb-4">
                        <div class="glass-soft"><small>Tanggal</small><strong><i class="bi bi-calendar-event"></i>{{ $event->event_date->translatedFormat('l, d F Y') }}</strong></div>
                        <div class="glass-soft"><small>Waktu</small><strong><i class="bi bi-clock"></i>{{ $event->time_range }}</strong></div>
                        <div class="glass-soft"><small>Lokasi</small><strong><i class="bi bi-geo-alt"></i>{{ $event->location }}</strong></div>
                        <div class="glass-soft"><small>Penyelenggara</small><strong><i class="bi bi-people"></i>{{ $event->organizer }}</strong></div>
                    </div>

                    <h2 class="h5 mb-2">Tentang acara</h2>
                    <div class="measure" style="color: var(--muted)">{!! nl2br(e($event->description)) !!}</div>
                </div>
            </article>
        </div>

        {{-- ============ Pendaftaran ============ --}}
        <div class="col-lg-4">
            <aside class="glass pad" style="position: sticky; top: 96px;">
                <div class="d-flex justify-content-between align-items-baseline mb-1">
                    <h2 class="h5 mb-0">Kuota peserta</h2>
                    <span class="muted small">{{ $event->taken_count }} dari {{ $event->quota }}</span>
                </div>
                <div class="meter {{ $event->fill_percent >= 80 ? 'hot' : '' }} mb-2" role="progressbar"
                     aria-valuenow="{{ $event->fill_percent }}" aria-valuemin="0" aria-valuemax="100">
                    <span style="width: {{ $event->fill_percent }}%"></span>
                </div>
                <p class="mb-3 {{ $event->is_full ? 'text-warning fw-semibold' : 'muted' }}">
                    @if ($event->is_full) Kuota penuh @else Tersisa {{ $event->remaining }} kursi @endif
                </p>
                <div class="divider"></div>

                @if ($event->isFinished())
                    <p class="muted mb-0">Acara ini sudah selesai, jadi pendaftaran sudah ditutup.</p>

                @elseif (! auth()->check())
                    <p class="muted">Masuk dulu untuk mendaftar dan mendapatkan kode registrasi.</p>
                    <a href="{{ route('login') }}" class="btn btn-glow w-100 mb-2">Masuk untuk mendaftar</a>
                    <a href="{{ route('register') }}" class="btn btn-glass w-100">Buat akun peserta</a>

                @elseif (auth()->user()->isPanitia())
                    @if ($event->user_id === auth()->id())
                        <p class="muted">Ini acara yang kamu kelola.</p>
                        <a href="{{ route('panitia.attendees.index', $event) }}" class="btn btn-glow w-100 mb-2"><i class="bi bi-people"></i> Kelola peserta</a>
                        <a href="{{ route('panitia.events.edit', $event) }}" class="btn btn-glass w-100"><i class="bi bi-pencil"></i> Ubah acara</a>
                    @else
                        <p class="muted mb-0">Akun panitia tidak dapat mendaftar ke acara. Gunakan akun peserta untuk mendaftar.</p>
                    @endif

                @elseif ($registration && $registration->isActive())
                    <p class="fw-semibold mb-2"><i class="bi bi-check-circle text-success"></i> Kamu sudah terdaftar</p>
                    @include('partials.ticket', ['registration' => $registration->setRelation('event', $event)])
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('peserta.dashboard') }}" class="btn btn-glass btn-sm flex-grow-1">Lihat tiket saya</a>
                        @if (! $registration->hasAttended())
                            <form method="POST" action="{{ route('registrations.cancel', $registration) }}" onsubmit="return confirm('Batalkan pendaftaran untuk acara ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-soft-danger btn-sm" type="submit">Batalkan</button>
                            </form>
                        @endif
                    </div>

                @elseif ($registration && $registration->status === 'rejected')
                    <p class="muted mb-0">Pendaftaranmu ditolak panitia. Hubungi penyelenggara jika menurutmu ini keliru.</p>

                @elseif ($event->is_full)
                    <button class="btn btn-glass w-100" disabled>Kuota penuh</button>

                @else
                    <form method="POST" action="{{ route('registrations.store', $event) }}">
                        @csrf
                        <button class="btn btn-glow btn-lg w-100" type="submit"><i class="bi bi-ticket-perforated"></i> Daftar sekarang</button>
                    </form>
                    <p class="form-hint mb-0">Kode registrasi langsung muncul setelah kamu mendaftar.</p>
                @endif
            </aside>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section class="section">
            <div class="section-head"><h2 class="section-title">Acara {{ strtolower($event->category) }} lainnya</h2></div>
            <div class="row g-4">
                @foreach ($related as $item)
                    <div class="col-md-6 col-lg-4">@include('partials.event-card', ['event' => $item])</div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
