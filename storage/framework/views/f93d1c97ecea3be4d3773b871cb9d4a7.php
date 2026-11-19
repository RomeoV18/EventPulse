<nav class="subnav glass" aria-label="Menu panitia">
    <a href="<?php echo e(route('panitia.dashboard')); ?>" class="<?php echo e(request()->routeIs('panitia.dashboard') ? 'active' : ''); ?>"><i class="bi bi-grid"></i> Ringkasan</a>
    <a href="<?php echo e(route('panitia.events.index')); ?>" class="<?php echo e(request()->routeIs('panitia.events.*', 'panitia.attendees.*') ? 'active' : ''); ?>"><i class="bi bi-calendar2-event"></i> Acara &amp; peserta</a>
    <a href="<?php echo e(route('panitia.reports.index')); ?>" class="<?php echo e(request()->routeIs('panitia.reports.*') ? 'active' : ''); ?>"><i class="bi bi-bar-chart-line"></i> Laporan</a>
</nav>
<?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/partials/panitia-subnav.blade.php ENDPATH**/ ?>