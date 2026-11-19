<?php $__env->startSection('title', 'Peserta ' . $event->title); ?>

<?php $__env->startSection('content'); ?>
    <div class="mt-4 mb-2">
        <a href="<?php echo e(route('panitia.events.index')); ?>" class="muted"><i class="bi bi-chevron-left"></i> Kembali ke daftar acara</a>
    </div>

    <div class="page-head" style="margin-top: .6rem">
        <div class="tone-<?php echo e($event->tone); ?>">
            <span class="pill pill-tone mb-2"><i class="bi <?php echo e($event->icon); ?>"></i> <?php echo e($event->category); ?></span>
            <h1 class="page-title mb-1"><?php echo e($event->title); ?></h1>
            <p class="muted mb-0"><?php echo e($event->event_date->translatedFormat('l, d F Y')); ?> · <?php echo e($event->time_range); ?> · <?php echo e($event->location); ?></p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo e(route('panitia.events.edit', $event)); ?>" class="btn btn-glass"><i class="bi bi-pencil"></i> Ubah acara</a>
            <a href="<?php echo e(route('panitia.attendees.export', $event)); ?>" class="btn btn-glass"><i class="bi bi-download"></i> Ekspor CSV</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="glass kpi tone-violet"><span class="kpi-value"><?php echo e($event->taken_count); ?><small class="faint fs-6">/<?php echo e($event->quota); ?></small></span><span class="kpi-label">Pendaftar / kuota</span></div></div>
        <div class="col-6 col-lg-3"><div class="glass kpi tone-amber"><span class="kpi-value"><?php echo e($statusCounts['pending'] ?? 0); ?></span><span class="kpi-label">Menunggu konfirmasi</span></div></div>
        <div class="col-6 col-lg-3"><div class="glass kpi tone-cyan"><span class="kpi-value"><?php echo e($event->confirmed_count); ?></span><span class="kpi-label">Terkonfirmasi</span></div></div>
        <div class="col-6 col-lg-3"><div class="glass kpi tone-mint"><span class="kpi-value"><?php echo e($event->attended_count); ?></span><span class="kpi-label">Hadir</span><span class="kpi-note"><?php echo e(\App\Services\ParticipationStats::eventRate($event)); ?>% dari pendaftar</span></div></div>
    </div>

    
    <div class="glass pad mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <h2 class="h5 mb-1">Catat kehadiran dengan kode</h2>
                <p class="muted small mb-0">Minta peserta menunjukkan kode registrasinya (contoh: EP-7K3QX9). Presensi dibuka pada hari acara.</p>
            </div>
            <div class="col-lg-7">
                <form method="POST" action="<?php echo e(route('panitia.attendees.checkinCode', $event)); ?>" class="d-flex flex-wrap gap-2" novalidate>
                    <?php echo csrf_field(); ?>
                    <input type="text" name="code" value="<?php echo e(old('code')); ?>" class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="flex: 1 1 200px; text-transform: uppercase; letter-spacing: .08em; font-weight: 700"
                           placeholder="EP-XXXXXX" aria-label="Kode registrasi" autocomplete="off" required>
                    <button type="submit" class="btn btn-glow"><i class="bi bi-check2-circle"></i> Catat hadir</button>
                </form>
                <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>

    
    <form method="GET" action="<?php echo e(route('panitia.attendees.index', $event)); ?>" class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <input type="search" name="q" value="<?php echo e($keyword); ?>" class="form-control" style="max-width: 300px" placeholder="Cari nama, email, atau kode" aria-label="Cari peserta">
        <select name="status" class="form-select" style="max-width: 220px" aria-label="Filter status">
            <option value="">Semua status</option>
            <?php $__currentLoopData = \App\Models\Registration::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($key); ?>" <?php if($status === $key): echo 'selected'; endif; ?>><?php echo e($label); ?> (<?php echo e($statusCounts[$key] ?? 0); ?>)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="btn btn-glass" type="submit">Terapkan</button>
        <?php if($status || $keyword): ?>
            <a href="<?php echo e(route('panitia.attendees.index', $event)); ?>" class="btn btn-glass">Atur ulang</a>
        <?php endif; ?>

        <?php if(($statusCounts['pending'] ?? 0) > 0): ?>
            <button type="submit" form="confirm-all" class="btn btn-soft-ok ms-lg-auto">
                <i class="bi bi-check2-all"></i> Konfirmasi semua (<?php echo e($statusCounts['pending']); ?>)
            </button>
        <?php endif; ?>
    </form>
    <form id="confirm-all" method="POST" action="<?php echo e(route('panitia.attendees.confirmAll', $event)); ?>" class="d-none"><?php echo csrf_field(); ?></form>

    <?php if($registrations->isEmpty()): ?>
        <div class="glass empty">
            <i class="bi bi-people"></i>
            <h2 class="h5 mt-3"><?php echo e($status || $keyword ? 'Tidak ada pendaftar yang cocok' : 'Belum ada pendaftar'); ?></h2>
            <p class="muted mb-0"><?php echo e($status || $keyword ? 'Ubah atau atur ulang filter untuk melihat semua pendaftar.' : 'Pendaftar akan muncul di sini begitu peserta mendaftar.'); ?></p>
        </div>
    <?php else: ?>
        <div class="glass table-wrap">
            <table class="table table-glass">
                <thead>
                    <tr><th>Peserta</th><th>Kode</th><th>Status</th><th>Kehadiran</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="min-width: 220px">
                                <div class="fw-semibold"><?php echo e($reg->user->name); ?></div>
                                <div class="faint small"><?php echo e($reg->user->email); ?><?php if($reg->user->phone): ?> · <?php echo e($reg->user->phone); ?><?php endif; ?></div>
                            </td>
                            <td><span class="code-mono"><?php echo e($reg->code); ?></span></td>
                            <td><?php echo $__env->make('partials.status-pill', ['registration' => $reg], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            <td>
                                <?php if($reg->hasAttended()): ?>
                                    <span class="pill pill-present"><i class="bi bi-check2"></i> <?php echo e($reg->attended_at->format('H:i')); ?></span>
                                <?php else: ?>
                                    <span class="faint">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <?php if($reg->status === 'pending'): ?>
                                        <form method="POST" action="<?php echo e(route('panitia.attendees.status', $reg)); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="status" value="confirmed">
                                            <button class="btn btn-soft-ok btn-sm" type="submit">Konfirmasi</button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('panitia.attendees.status', $reg)); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="btn btn-soft-danger btn-sm" type="submit">Tolak</button>
                                        </form>
                                    <?php elseif($reg->status === 'confirmed' && ! $reg->hasAttended()): ?>
                                        <form method="POST" action="<?php echo e(route('panitia.attendees.checkin', $reg)); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <button class="btn btn-glow btn-sm" type="submit"><i class="bi bi-check2"></i> Hadir</button>
                                        </form>
                                    <?php elseif($reg->hasAttended()): ?>
                                        <form method="POST" action="<?php echo e(route('panitia.attendees.undoCheckin', $reg)); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-glass btn-sm" type="submit">Batalkan hadir</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center"><?php echo e($registrations->links()); ?></div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/panitia/attendees/index.blade.php ENDPATH**/ ?>