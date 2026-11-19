<?php $__env->startSection('title', 'Laporan partisipasi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <h1 class="page-title mb-2">Laporan partisipasi</h1>
            <?php echo $__env->make('partials.panitia-subnav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <a href="<?php echo e(route('panitia.reports.export')); ?>" class="btn btn-glass"><i class="bi bi-download"></i> Unduh rekap CSV</a>
    </div>

    <?php if($events->isEmpty()): ?>
        <div class="glass empty">
            <i class="bi bi-bar-chart-line"></i>
            <h2 class="h5 mt-3">Belum ada data untuk dilaporkan</h2>
            <p class="muted">Laporan terisi setelah kamu membuat acara dan peserta mulai mendaftar.</p>
            <a href="<?php echo e(route('panitia.events.create')); ?>" class="btn btn-glow">Buat acara</a>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <div class="col-6 col-lg-3"><div class="glass kpi tone-violet"><i class="bi bi-calendar2-event kpi-icon"></i><span class="kpi-value"><?php echo e($stats['events']); ?></span><span class="kpi-label">Total acara</span></div></div>
            <div class="col-6 col-lg-3"><div class="glass kpi tone-cyan"><i class="bi bi-person-check kpi-icon"></i><span class="kpi-value"><?php echo e($stats['registrants']); ?></span><span class="kpi-label">Total pendaftar</span><span class="kpi-note">tidak termasuk yang batal/ditolak</span></div></div>
            <div class="col-6 col-lg-3"><div class="glass kpi tone-mint"><i class="bi bi-patch-check kpi-icon"></i><span class="kpi-value"><?php echo e($stats['attended']); ?></span><span class="kpi-label">Peserta hadir</span></div></div>
            <div class="col-6 col-lg-3"><div class="glass kpi tone-amber"><i class="bi bi-percent kpi-icon"></i><span class="kpi-value"><?php echo e($stats['rate']); ?>%</span><span class="kpi-label">Persentase kehadiran</span><span class="kpi-note">hadir ÷ pendaftar, acara yang sudah berlangsung</span></div></div>
        </div>

        <div class="row g-4 mt-1">
            
            <div class="col-lg-8">
                <section class="glass pad h-100">
                    <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                        <h2 class="h5 mb-0">Pendaftar dan kehadiran per acara</h2>
                        <div class="legend">
                            <span><i style="background: linear-gradient(90deg,#7c5cff,#3aa6f0)"></i>Pendaftar (terhadap kuota)</span>
                            <span><i style="background: var(--mint)"></i>Hadir</span>
                        </div>
                    </div>
                    <?php $__currentLoopData = $chartEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $regPct = $event->quota ? min(100, round($event->taken_count / $event->quota * 100)) : 0;
                            $attPct = $event->quota ? min(100, round($event->attended_count / $event->quota * 100)) : 0;
                        ?>
                        <div class="bar-row">
                            <div class="bar-name"><?php echo e(\Illuminate\Support\Str::limit($event->title, 44)); ?><small><?php echo e($event->event_date->translatedFormat('d M Y')); ?></small></div>
                            <div class="bar-track" role="img" aria-label="<?php echo e($event->taken_count); ?> pendaftar dan <?php echo e($event->attended_count); ?> hadir dari kuota <?php echo e($event->quota); ?>">
                                <span class="b-reg" style="width: <?php echo e($regPct); ?>%"></span>
                                <span class="b-att" style="width: <?php echo e($attPct); ?>%"></span>
                            </div>
                            <div class="bar-value"><?php echo e($event->attended_count); ?>/<?php echo e($event->taken_count); ?><small>kuota <?php echo e($event->quota); ?></small></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </section>
            </div>

            
            <div class="col-lg-4">
                <section class="glass pad h-100">
                    <h2 class="h5 mb-3">Kehadiran keseluruhan</h2>
                    <div class="donut" style="--p: <?php echo e($stats['rate']); ?>" role="img" aria-label="Kehadiran <?php echo e($stats['rate']); ?> persen">
                        <div class="donut-label"><b><?php echo e($stats['rate']); ?>%</b><small>hadir</small></div>
                    </div>
                    <div class="divider"></div>
                    <h3 class="h6 mb-2">Status pendaftaran</h3>
                    <div class="d-grid gap-2">
                        <?php $__currentLoopData = \App\Models\Registration::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="pill pill-status-<?php echo e($key); ?>"><?php echo e($label); ?></span>
                                <strong><?php echo e($statusCounts[$key] ?? 0); ?></strong>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            </div>
        </div>

        
        <section class="section">
            <div class="section-head"><h2 class="section-title">Rekap per kategori</h2></div>
            <div class="row g-3">
                <?php $__currentLoopData = $byCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $meta = \App\Models\Event::CATEGORIES[$name] ?? ['icon' => 'bi-calendar-event', 'tone' => 'violet']; ?>
                    <div class="col-sm-6 col-lg-4 col-xl">
                        <div class="glass kpi tone-<?php echo e($meta['tone']); ?>">
                            <i class="bi <?php echo e($meta['icon']); ?> kpi-icon"></i>
                            <span class="kpi-label"><?php echo e($name); ?></span>
                            <span class="kpi-value"><?php echo e($row['registrants']); ?></span>
                            <span class="kpi-note">pendaftar di <?php echo e($row['events']); ?> acara · <?php echo e($row['attended']); ?> hadir</span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>

        
        <section class="section">
            <div class="section-head"><h2 class="section-title">Rekapitulasi seluruh acara</h2></div>
            <div class="glass table-wrap">
                <table class="table table-glass">
                    <thead>
                        <tr><th>Acara</th><th>Tanggal</th><th>Kuota</th><th>Pendaftar</th><th>Terkonfirmasi</th><th>Hadir</th><th style="min-width:150px">Kehadiran</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $rate = \App\Services\ParticipationStats::eventRate($event); ?>
                            <tr>
                                <td style="min-width: 240px"><a href="<?php echo e(route('panitia.attendees.index', $event)); ?>" class="text-white fw-semibold"><?php echo e($event->title); ?></a><div class="faint small"><?php echo e($event->category); ?></div></td>
                                <td class="text-nowrap"><?php echo e($event->event_date->translatedFormat('d M Y')); ?></td>
                                <td><?php echo e($event->quota); ?></td>
                                <td><?php echo e($event->taken_count); ?></td>
                                <td><?php echo e($event->confirmed_count); ?></td>
                                <td><?php echo e($event->attended_count); ?></td>
                                <td>
                                    <?php if($event->event_date->gt(today())): ?>
                                        <span class="faint small">Belum berlangsung</span>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="meter flex-grow-1"><span style="width: <?php echo e($rate); ?>%; background: var(--mint)"></span></div>
                                            <strong class="small"><?php echo e($rate); ?>%</strong>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/panitia/reports/index.blade.php ENDPATH**/ ?>