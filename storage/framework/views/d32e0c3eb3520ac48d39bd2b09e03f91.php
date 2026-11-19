<?php $__env->startSection('title', 'Buat acara'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <h1 class="page-title mb-2">Buat acara</h1>
            <?php echo $__env->make('partials.panitia-subnav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <div class="glass pad" style="max-width: 900px">
        <?php echo $__env->make('panitia.events._form', [
            'action' => route('panitia.events.store'),
            'method' => 'POST',
            'submit' => 'Simpan acara',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/panitia/events/create.blade.php ENDPATH**/ ?>