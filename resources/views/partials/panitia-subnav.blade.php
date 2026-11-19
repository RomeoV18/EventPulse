<nav class="subnav glass" aria-label="Menu panitia">
    <a href="{{ route('panitia.dashboard') }}" class="{{ request()->routeIs('panitia.dashboard') ? 'active' : '' }}"><i class="bi bi-grid"></i> Ringkasan</a>
    <a href="{{ route('panitia.events.index') }}" class="{{ request()->routeIs('panitia.events.*', 'panitia.attendees.*') ? 'active' : '' }}"><i class="bi bi-calendar2-event"></i> Acara &amp; peserta</a>
    <a href="{{ route('panitia.reports.index') }}" class="{{ request()->routeIs('panitia.reports.*') ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i> Laporan</a>
</nav>
