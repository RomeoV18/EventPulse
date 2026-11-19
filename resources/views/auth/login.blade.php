@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="auth-wrap">
        <div class="glass auth-card">
            <h1 class="page-title mb-1">Masuk ke EventPulse</h1>
            <p class="muted mb-4">Peserta melihat tiket dan jadwal. Panitia mengelola acara dan kehadiran.</p>

            <form method="POST" action="{{ route('login.attempt') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                           class="form-control @error('email') is-invalid @enderror" placeholder="nama@kampus.ac.id">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata sandi</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label muted" for="remember">Ingat saya di perangkat ini</label>
                </div>
                <button type="submit" class="btn btn-glow btn-lg w-100">Masuk</button>
            </form>

            <p class="text-center muted mt-4 mb-0">Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Buat akun</a></p>

            <div class="glass-soft pad-sm mt-4 small muted">
                <strong class="text-white">Akun demo</strong> (setelah <code>db:seed</code>):<br>
                Panitia: <code>panitia@eventpulse.test</code><br>
                Peserta: <code>peserta@eventpulse.test</code><br>
                Kata sandi keduanya: <code>password</code>
            </div>
        </div>
    </div>
@endsection
