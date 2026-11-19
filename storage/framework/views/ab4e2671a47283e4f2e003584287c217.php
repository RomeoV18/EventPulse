<?php $__env->startSection('title', 'Buat akun'); ?>

<?php $__env->startSection('content'); ?>
    <?php $role = old('role', request('role') === 'panitia' ? 'panitia' : 'peserta'); ?>

    <div class="auth-wrap">
        <div class="glass auth-card" style="max-width: 540px">
            <h1 class="page-title mb-1">Buat akun EventPulse</h1>
            <p class="muted mb-4">Pilih peran sesuai kebutuhanmu. Hak akses menyesuaikan peran.</p>

            <form method="POST" action="<?php echo e(route('register.store')); ?>" novalidate>
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <span class="form-label d-block">Saya mendaftar sebagai</span>
                    <div class="role-choice">
                        <div>
                            <input type="radio" name="role" id="role-peserta" value="peserta" <?php if($role === 'peserta'): echo 'checked'; endif; ?>>
                            <label for="role-peserta"><strong><i class="bi bi-person"></i> Peserta</strong><span>Cari acara, daftar, dan hadir.</span></label>
                        </div>
                        <div>
                            <input type="radio" name="role" id="role-panitia" value="panitia" <?php if($role === 'panitia'): echo 'checked'; endif; ?>>
                            <label for="role-panitia"><strong><i class="bi bi-person-gear"></i> Panitia</strong><span>Kelola acara dan peserta.</span></label>
                        </div>
                    </div>
                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama lengkap atau nama organisasi</label>
                    <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name"
                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email"
                               class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-5">
                        <label for="phone" class="form-label">Telepon (opsional)</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" autocomplete="tel"
                               class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Kata sandi</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
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
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php else: ?> <div class="form-hint">Minimal 8 karakter.</div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Ulangi kata sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-glow btn-lg w-100">Buat akun</button>
            </form>

            <p class="text-center muted mt-4 mb-0">Sudah punya akun? <a href="<?php echo e(route('login')); ?>" class="fw-semibold">Masuk</a></p>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/auth/register.blade.php ENDPATH**/ ?>