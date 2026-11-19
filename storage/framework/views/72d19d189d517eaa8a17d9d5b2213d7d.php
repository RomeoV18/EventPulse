<?php if(session('success')): ?>
    <div class="flash flash-success" role="status">
        <i class="bi bi-check-circle-fill"></i><span><?php echo e(session('success')); ?></span>
    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="flash flash-error" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i><span><?php echo e(session('error')); ?></span>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/partials/flash.blade.php ENDPATH**/ ?>