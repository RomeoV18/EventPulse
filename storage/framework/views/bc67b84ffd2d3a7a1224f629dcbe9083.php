<?php $__env->startSection('title', 'Ubah acara'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <h1 class="page-title mb-2">Ubah acara</h1>
            <?php echo $__env->make('partials.panitia-subnav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <a href="<?php echo e(route('panitia.attendees.index', $event)); ?>" class="btn btn-glass"><i class="bi bi-people"></i> Lihat peserta</a>
    </div>

    <div class="glass pad" style="max-width: 900px">
        <?php echo $__env->make('panitia.events._form', [
            'action' => route('panitia.events.update', $event),
            'method' => 'PUT',
            'submit' => 'Simpan perubahan',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/panitia/events/edit.blade.php ENDPATH**/ ?>