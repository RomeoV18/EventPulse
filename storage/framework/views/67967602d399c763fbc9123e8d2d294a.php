<?php $__env->startSection('title', 'Acara & peserta'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <h1 class="page-title mb-2">Acara &amp; peserta</h1>
            <?php echo $__env->make('partials.panitia-subnav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <a href="<?php echo e(route('panitia.events.create')); ?>" class="btn btn-glow"><i class="bi bi-plus-lg"></i> Buat acara</a>
    </div>

    <?php if($events->isEmpty()): ?>
        <div class="glass empty">
            <i class="bi bi-calendar-plus"></i>
            <h2 class="h5 mt-3">Kamu belum membuat acara</h2>
            <p class="muted">Mulai dengan judul, jadwal, lokasi, dan kuota peserta.</p>
            <a href="<?php echo e(route('panitia.events.create')); ?>" class="btn btn-glow">Buat acara</a>
        </div>
    <?php else: ?>
        <div class="glass table-wrap">
            <table class="table table-glass">
                <thead>
                    <tr><th>Acara</th><th>Jadwal</th><th style="min-width:170px">Kuota</th><th>Hadir</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="tone-<?php echo e($event->tone); ?>">
                            <td style="min-width: 240px">
                                <div class="fw-semibold"><?php echo e($event->title); ?></div>
                                <span class="pill pill-tone mt-1"><i class="bi <?php echo e($event->icon); ?>"></i> <?php echo e($event->category); ?></span>
                                <?php if($event->isFinished()): ?> <span class="pill pill-status-cancelled mt-1">Selesai</span> <?php endif; ?>
                            </td>
                            <td class="text-nowrap">
                                <?php echo e($event->event_date->translatedFormat('d M Y')); ?>

                                <div class="faint small"><?php echo e($event->time_range); ?></div>
                            </td>
                            <td>
                                <div class="meter <?php echo e($event->fill_percent >= 80 ? 'hot' : ''); ?>"><span style="width: <?php echo e($event->fill_percent); ?>%"></span></div>
                                <div class="quota-line"><span><?php echo e($event->taken_count); ?>/<?php echo e($event->quota); ?></span><span><?php echo e($event->fill_percent); ?>%</span></div>
                            </td>
                            <td><?php echo e($event->attended_count); ?></td>
                            <td>
                                <div class="row-actions">
                                    <a href="<?php echo e(route('panitia.attendees.index', $event)); ?>" class="btn btn-glass btn-sm"><i class="bi bi-people"></i> Peserta</a>
                                    <a href="<?php echo e(route('panitia.events.edit', $event)); ?>" class="btn btn-glass btn-sm"><i class="bi bi-pencil"></i> Ubah</a>
                                    <form method="POST" action="<?php echo e(route('panitia.events.destroy', $event)); ?>"
                                          onsubmit="return confirm('Hapus acara &quot;<?php echo e(addslashes($event->title)); ?>&quot; beserta seluruh data pendaftarannya? Tindakan ini tidak bisa dibatalkan.')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-soft-danger btn-sm" aria-label="Hapus <?php echo e($event->title); ?>"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center"><?php echo e($events->links()); ?></div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/panitia/events/index.blade.php ENDPATH**/ ?>