
<article class="event-card glass tone-<?php echo e($event->tone); ?>">
    <div class="event-cover">
        <i class="bi <?php echo e($event->icon); ?> cover-icon" aria-hidden="true"></i>
        <span class="pill" style="position:relative;background:rgba(10,8,40,.45);color:#fff;border-color:rgba(255,255,255,.3)"><?php echo e($event->category); ?></span>
        <div class="date-chip">
            <b><?php echo e($event->event_date->format('d')); ?></b>
            <small><?php echo e($event->event_date->translatedFormat('M Y')); ?></small>
        </div>
    </div>

    <div class="event-body">
        <h3 class="event-title"><a href="<?php echo e(route('events.show', $event)); ?>"><?php echo e($event->title); ?></a></h3>

        <div class="meta">
            <span><i class="bi bi-clock"></i><?php echo e($event->time_range); ?></span>
            <span><i class="bi bi-geo-alt"></i><?php echo e($event->location); ?></span>
        </div>

        <div class="mt-auto pt-2">
            <div class="meter <?php echo e($event->fill_percent >= 80 ? 'hot' : ''); ?>" role="progressbar"
                 aria-label="Kuota terpakai" aria-valuenow="<?php echo e($event->fill_percent); ?>" aria-valuemin="0" aria-valuemax="100">
                <span style="width: <?php echo e($event->fill_percent); ?>%"></span>
            </div>
            <div class="quota-line">
                <?php if($event->isFinished()): ?>
                    <span>Acara selesai</span>
                    <span><?php echo e($event->taken_count); ?> pendaftar</span>
                <?php elseif($event->is_full): ?>
                    <span class="text-warning fw-semibold">Kuota penuh</span>
                    <span><?php echo e($event->quota); ?> peserta</span>
                <?php else: ?>
                    <span>Sisa <?php echo e($event->remaining); ?> kursi</span>
                    <span><?php echo e($event->taken_count); ?>/<?php echo e($event->quota); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>
<?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/partials/event-card.blade.php ENDPATH**/ ?>