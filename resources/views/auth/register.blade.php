@extends('layouts.app')

@section('title', 'Buat akun')

@section('content')
    @php $role = old('role', request('role') === 'panitia' ? 'panitia' : 'peserta'); @endphp

    <div class="auth-wrap">
        <div class="glass auth-card" style="max-width: 540px">
            <h1 class="page-title mb-1">Buat akun EventPulse</h1>
            <p class="muted mb-4">Pilih peran sesuai kebutuhanmu. Hak akses menyesuaikan peran.</p>

            <form method="POST" action="{{ route('register.store') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <span class="form-label d-block">Saya mendaftar sebagai</span>
                    <div class="role-choice">
                        <div>
                            <input type="radio" name="role" id="role-peserta" value="peserta" @checked($role === 'peserta')>
                            <label for="role-peserta"><strong><i class="bi bi-person"></i> Peserta</strong><span>Cari acara, daftar, dan hadir.</span></label>
                        </div>
                        <div>
                            <input type="radio" name="role" id="role-panitia" value="panitia" @checked($role === 'panitia')>
                            <label for="role-panitia"><strong><i class="bi bi-person-gear"></i> Panitia</strong><span>Kelola acara dan peserta.</span></label>
                        </div>
                    </div>
                    @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama lengkap atau nama organisasi</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-5">
                        <label for="phone" class="form-label">Telepon (opsional)</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                               class="form-control @error('phone') is-invalid @enderror">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Kata sandi</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @else <div class="form-hint">Minimal 8 karakter.</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Ulangi kata sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-glow btn-lg w-100">Buat akun</button>
            </form>

            <p class="text-center muted mt-4 mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Masuk</a></p>
        </div>
    </div>
@endsection
