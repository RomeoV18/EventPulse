<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#0b0922">
    <title><?php echo $__env->yieldContent('title', 'EventPulse'); ?> · EventPulse</title>

    <link rel="stylesheet" href="<?php echo e(asset('vendor/bootstrap/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/icons/bootstrap-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/eventpulse.css')); ?>">
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body>
    <div class="aurora" aria-hidden="true"><i></i><i></i><i></i></div>

    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="container pb-4">
        <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="site-footer">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span><strong class="text-white">EventPulse</strong> — manajemen acara dan analisis partisipasi.</span>
            <span>Dibuat dengan Laravel, MySQL, dan Bootstrap.</span>
        </div>
    </footer>

    <script src="<?php echo e(asset('vendor/bootstrap/bootstrap.bundle.min.js')); ?>"></script>
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
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/layouts/app.blade.php ENDPATH**/ ?>