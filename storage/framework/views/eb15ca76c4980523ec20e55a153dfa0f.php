<?php $__env->startSection('title', 'Dashboard peserta'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <h1 class="page-title mb-1">Halo, <?php echo e(\Illuminate\Support\Str::words(auth()->user()->name, 1, '')); ?></h1>
            <p class="muted mb-0">Jadwal acara dan kode registrasimu ada di sini.</p>
        </div>
        <a href="<?php echo e(route('events.index')); ?>" class="btn btn-glow"><i class="bi bi-search"></i> Cari acara lain</a>
    </div>

    <div class="row g-3">
        <div class="col-sm-4"><div class="glass kpi tone-violet"><i class="bi bi-ticket-perforated kpi-icon"></i><span class="kpi-value"><?php echo e($stats['active']); ?></span><span class="kpi-label">Pendaftaran aktif</span></div></div>
        <div class="col-sm-4"><div class="glass kpi tone-cyan"><i class="bi bi-calendar-check kpi-icon"></i><span class="kpi-value"><?php echo e($stats['upcoming']); ?></span><span class="kpi-label">Acara mendatang</span></div></div>
        <div class="col-sm-4"><div class="glass kpi tone-mint"><i class="bi bi-patch-check kpi-icon"></i><span class="kpi-value"><?php echo e($stats['attended']); ?></span><span class="kpi-label">Acara yang kamu hadiri</span></div></div>
    </div>

    
    <section class="section">
        <div class="section-head"><h2 class="section-title">Jadwal dan kode registrasi</h2></div>

        <?php if($upcoming->isEmpty()): ?>
            <div class="glass empty">
                <i class="bi bi-ticket-perforated"></i>
                <h3 class="h5 mt-3">Belum ada acara yang akan kamu ikuti</h3>
                <p class="muted">Pilih acara di katalog, daftar, dan tiketmu akan muncul di sini.</p>
                <a href="<?php echo e(route('events.index')); ?>" class="btn btn-glow">Buka katalog acara</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-xl-4">
                        <?php echo $__env->make('partials.ticket', ['registration' => $reg], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="<?php echo e(route('events.show', $reg->event)); ?>" class="btn btn-glass btn-sm">Detail acara</a>
                            <?php if(! $reg->hasAttended()): ?>
                                <form method="POST" action="<?php echo e(route('registrations.cancel', $reg)); ?>" onsubmit="return confirm('Batalkan pendaftaran untuk acara ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-soft-danger btn-sm" type="submit">Batalkan pendaftaran</button>
                                </form>
                            <?php else: ?>
                                <span class="pill pill-present"><i class="bi bi-check2"></i> Sudah hadir</span>
                            <?php endif; ?>
                        </div>
                        <?php if($reg->status === 'pending'): ?>
                            <p class="form-hint">Kode baru bisa dipakai presensi setelah panitia mengonfirmasi pendaftaranmu.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </section>

    
    <section class="section">
        <div class="section-head"><h2 class="section-title">Riwayat keikutsertaan</h2></div>
        <?php if($history->isEmpty()): ?>
            <div class="glass empty"><p class="muted mb-0">Riwayat akan muncul setelah acara yang kamu ikuti selesai.</p></div>
        <?php else: ?>
            <div class="glass table-wrap">
                <table class="table table-glass">
                    <thead>
                        <tr><th>Acara</th><th>Tanggal</th><th>Kode</th><th>Status</th><th>Kehadiran</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><a href="<?php echo e(route('events.show', $reg->event)); ?>" class="text-white fw-semibold"><?php echo e($reg->event->title); ?></a><div class="faint small"><?php echo e($reg->event->category); ?> · <?php echo e($reg->event->location); ?></div></td>
                                <td class="text-nowrap"><?php echo e($reg->event->event_date->translatedFormat('d M Y')); ?></td>
                                <td><span class="code-mono"><?php echo e($reg->code); ?></span></td>
                                <td><?php echo $__env->make('partials.status-pill', ['registration' => $reg], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                <td>
                                    <?php if($reg->hasAttended()): ?>
                                        <span class="pill pill-present"><i class="bi bi-check2"></i> Hadir <?php echo e($reg->attended_at->format('H:i')); ?></span>
                                    <?php elseif($reg->status === 'confirmed'): ?>
                                        <span class="pill pill-absent">Tidak hadir</span>
                                    <?php else: ?>
                                        <span class="faint">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/peserta/dashboard.blade.php ENDPATH**/ ?>