<div class="nav-wrap">
    <div class="container">
        <nav class="nav-glass" id="mainNav" aria-label="Navigasi utama">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span>
                EventPulse
            </a>

            <button type="button" class="nav-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">
                <i class="bi bi-list fs-5"></i>
            </button>

            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'active' : '' }}">Katalog acara</a></li>
                @auth
                    @if (auth()->user()->isPanitia())
                        <li><a href="{{ route('panitia.dashboard') }}" class="{{ request()->routeIs('panitia.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                        <li><a href="{{ route('panitia.events.index') }}" class="{{ request()->routeIs('panitia.events.*', 'panitia.attendees.*') ? 'active' : '' }}">Acara &amp; peserta</a></li>
                        <li><a href="{{ route('panitia.reports.index') }}" class="{{ request()->routeIs('panitia.reports.*') ? 'active' : '' }}">Laporan</a></li>
                    @else
                        <li><a href="{{ route('peserta.dashboard') }}" class="{{ request()->routeIs('peserta.*') ? 'active' : '' }}">Dashboard</a></li>
                    @endif
                @endauth
            </ul>

            <div class="nav-actions">
                @auth
                    <span class="user-chip" title="{{ auth()->user()->email }}">
                        <span class="avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        {{ \Illuminate\Support\Str::words(auth()->user()->name, 2, '') }}
                        <span class="pill pill-tone tone-{{ auth()->user()->isPanitia() ? 'coral' : 'cyan' }}">{{ auth()->user()->role }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-glass btn-sm"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-glass btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-glow btn-sm">Buat akun</a>
                @endauth
            </div>
        </nav>
    </div>
</div>
