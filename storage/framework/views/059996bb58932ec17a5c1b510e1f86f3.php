<div class="nav-wrap">
    <div class="container">
        <nav class="nav-glass" id="mainNav" aria-label="Navigasi utama">
            <a href="<?php echo e(route('home')); ?>" class="brand">
                <span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span>
                EventPulse
            </a>

            <button type="button" class="nav-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">
                <i class="bi bi-list fs-5"></i>
            </button>

            <ul class="nav-links">
                <li><a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">Beranda</a></li>
                <li><a href="<?php echo e(route('events.index')); ?>" class="<?php echo e(request()->routeIs('events.*') ? 'active' : ''); ?>">Katalog acara</a></li>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isPanitia()): ?>
                        <li><a href="<?php echo e(route('panitia.dashboard')); ?>" class="<?php echo e(request()->routeIs('panitia.dashboard') ? 'active' : ''); ?>">Dashboard</a></li>
                        <li><a href="<?php echo e(route('panitia.events.index')); ?>" class="<?php echo e(request()->routeIs('panitia.events.*', 'panitia.attendees.*') ? 'active' : ''); ?>">Acara &amp; peserta</a></li>
                        <li><a href="<?php echo e(route('panitia.reports.index')); ?>" class="<?php echo e(request()->routeIs('panitia.reports.*') ? 'active' : ''); ?>">Laporan</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo e(route('peserta.dashboard')); ?>" class="<?php echo e(request()->routeIs('peserta.*') ? 'active' : ''); ?>">Dashboard</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <?php if(auth()->guard()->check()): ?>
                    <span class="user-chip" title="<?php echo e(auth()->user()->email); ?>">
                        <span class="avatar"><?php echo e(strtoupper(mb_substr(auth()->user()->name, 0, 1))); ?></span>
                        <?php echo e(\Illuminate\Support\Str::words(auth()->user()->name, 2, '')); ?>

                        <span class="pill pill-tone tone-<?php echo e(auth()->user()->isPanitia() ? 'coral' : 'cyan'); ?>"><?php echo e(auth()->user()->role); ?></span>
                    </span>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-glass btn-sm"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-glass btn-sm">Masuk</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-glow btn-sm">Buat akun</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/partials/navbar.blade.php ENDPATH**/ ?>