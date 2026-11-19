
<?php
    $e = $registration->event;
    $done = ! $registration->isActive() || $e->isFinished();
?>
<div class="ticket tone-<?php echo e($e->tone); ?> <?php echo e($done ? 'is-done' : ''); ?>">
    <div class="ticket-top">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <span class="pill pill-tone"><i class="bi <?php echo e($e->icon); ?>"></i> <?php echo e($e->category); ?></span>
            <?php echo $__env->make('partials.status-pill', ['registration' => $registration], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <h3><a href="<?php echo e(route('events.show', $e)); ?>" class="text-white"><?php echo e($e->title); ?></a></h3>
        <div class="meta">
            <span><i class="bi bi-calendar-event"></i><?php echo e($e->event_date->translatedFormat('l, d F Y')); ?></span>
            <span><i class="bi bi-clock"></i><?php echo e($e->time_range); ?></span>
            <span><i class="bi bi-geo-alt"></i><?php echo e($e->location); ?></span>
        </div>
    </div>
    <div class="ticket-bottom">
        <div>
            <div class="ticket-label">Kode registrasi untuk presensi</div>
            <div class="ticket-code"><?php echo e($registration->code); ?></div>
        </div>
        <div class="ticket-bar" aria-hidden="true"></div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/partials/ticket.blade.php ENDPATH**/ ?>