@extends('layouts.app')

@section('title', 'Ubah acara')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title mb-2">Ubah acara</h1>
            @include('partials.panitia-subnav')
        </div>
        <a href="{{ route('panitia.attendees.index', $event) }}" class="btn btn-glass"><i class="bi bi-people"></i> Lihat peserta</a>
    </div>

    <div class="glass pad" style="max-width: 900px">
        @include('panitia.events._form', [
            'action' => route('panitia.events.update', $event),
            'method' => 'PUT',
            'submit' => 'Simpan perubahan',
        ])
    </div>
@endsection
