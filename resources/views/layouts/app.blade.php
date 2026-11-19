<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b0922">
    <title>@yield('title', 'EventPulse') · EventPulse</title>

    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/eventpulse.css') }}">
    @stack('head')
</head>
<body>
    <div class="aurora" aria-hidden="true"><i></i><i></i><i></i></div>

    @include('partials.navbar')

    <main class="container pb-4">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span><strong class="text-white">EventPulse</strong> — manajemen acara dan analisis partisipasi.</span>
            <span>Dibuat dengan Laravel, MySQL, dan Bootstrap.</span>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function () {
            var nav = document.getElementById('mainNav');
            var btn = document.getElementById('navToggle');
            if (!nav || !btn) return;
            btn.addEventListener('click', function () {
                var open = nav.classList.toggle('open');
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
