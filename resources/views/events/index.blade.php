@extends('layouts.app')

@section('title', 'Katalog acara')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title mb-1">Katalog acara</h1>
            <p class="muted mb-0">Cari berdasarkan kata kunci, kategori, tanggal, atau lokasi.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('events.index') }}" class="glass pad mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-4">
                <label for="q" class="form-label">Kata kunci</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Judul, deskripsi, atau penyelenggara">
            </div>
            <div class="col-sm-6 col-lg-2">
                <label for="category" class="form-label">Kategori</label>
                <select id="category" name="category" class="form-select">
                    <option value="">Semua</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(($filters['category'] ?? '') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <label for="date" class="form-label">Mulai tanggal</label>
                <input type="date" id="date" name="date" value="{{ $filters['date'] ?? '' }}" class="form-control">
            </div>
            <div class="col-lg-4">
                <label for="location" class="form-label">Lokasi</label>
                <input type="text" id="location" name="location" value="{{ $filters['location'] ?? '' }}" class="form-control" placeholder="Contoh: Aula, Lab, Online">
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-3">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="past" value="1" id="past" @checked($showPast)>
                <label class="form-check-label muted" for="past">Sertakan acara yang sudah selesai</label>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('events.index') }}" class="btn btn-glass">Atur ulang</a>
                <button type="submit" class="btn btn-glow"><i class="bi bi-funnel"></i> Terapkan filter</button>
            </div>
        </div>
    </form>

    <p class="muted mb-3" aria-live="polite"><strong class="text-white">{{ $events->total() }}</strong> acara ditemukan</p>

    @if ($events->isEmpty())
        <div class="glass empty">
            <i class="bi bi-search"></i>
            <h2 class="h5 mt-3">Tidak ada acara yang cocok</h2>
            <p class="muted">Coba kurangi filter atau gunakan kata kunci yang lebih umum.</p>
            <a href="{{ route('events.index') }}" class="btn btn-glass">Tampilkan semua acara</a>
        </div>
    @else
        <div class="row g-4">
            @foreach ($events as $event)
                <div class="col-md-6 col-lg-4">@include('partials.event-card', ['event' => $event])</div>
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">{{ $events->links() }}</div>
    @endif
@endsection
