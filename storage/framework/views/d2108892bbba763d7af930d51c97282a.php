<?php $__env->startSection('title', 'Masuk'); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-wrap">
        <div class="glass auth-card">
            <h1 class="page-title mb-1">Masuk ke EventPulse</h1>
            <p class="muted mb-4">Peserta melihat tiket dan jadwal. Panitia mengelola acara dan kehadiran.</p>

            <form method="POST" action="<?php echo e(route('login.attempt')); ?>" novalidate>
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="email"
                           class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="nama@kampus.ac.id">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata sandi</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label muted" for="remember">Ingat saya di perangkat ini</label>
                </div>
                <button type="submit" class="btn btn-glow btn-lg w-100">Masuk</button>
            </form>

            <p class="text-center muted mt-4 mb-0">Belum punya akun? <a href="<?php echo e(route('register')); ?>" class="fw-semibold">Buat akun</a></p>

            <div class="glass-soft pad-sm mt-4 small muted">
                <strong class="text-white">Akun demo</strong> (setelah <code>db:seed</code>):<br>
                Panitia: <code>panitia@eventpulse.test</code><br>
                Peserta: <code>peserta@eventpulse.test</code><br>
                Kata sandi keduanya: <code>password</code>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/auth/login.blade.php ENDPATH**/ ?>