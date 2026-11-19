@extends('layouts.app')

@section('title', 'Buat acara')

@section('content')
    <div class="page-head">
        <div>
            <h1 class="page-title mb-2">Buat acara</h1>
            @include('partials.panitia-subnav')
        </div>
    </div>

    <div class="glass pad" style="max-width: 900px">
        @include('panitia.events._form', [
            'action' => route('panitia.events.store'),
            'method' => 'POST',
            'submit' => 'Simpan acara',
        ])
    </div>
@endsection
