<?php $__env->startSection('title', $event->title); ?>

<?php $__env->startSection('content'); ?>
    <div class="mt-4 mb-3">
        <a href="<?php echo e(route('events.index')); ?>" class="muted"><i class="bi bi-chevron-left"></i> Kembali ke katalog</a>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            <article class="glass overflow-hidden tone-<?php echo e($event->tone); ?>">
                <div class="event-cover" style="height: 170px; align-items: flex-end;">
                    <i class="bi <?php echo e($event->icon); ?> cover-icon" style="font-size: 9rem;" aria-hidden="true"></i>
                    <span class="pill" style="position:relative;background:rgba(10,8,40,.45);color:#fff;border-color:rgba(255,255,255,.3)">
                        <i class="bi <?php echo e($event->icon); ?>"></i> <?php echo e($event->category); ?>

                    </span>
                    <?php if($event->isFinished()): ?>
                        <span class="pill pill-status-cancelled" style="position:relative">Acara selesai</span>
                    <?php endif; ?>
                </div>

                <div class="pad">
                    <h1 class="page-title mb-1"><?php echo e($event->title); ?></h1>
                    <p class="muted mb-4">Diselenggarakan oleh <?php echo e($event->organizer); ?></p>

                    <div class="info-grid mb-4">
                        <div class="glass-soft"><small>Tanggal</small><strong><i class="bi bi-calendar-event"></i><?php echo e($event->event_date->translatedFormat('l, d F Y')); ?></strong></div>
                        <div class="glass-soft"><small>Waktu</small><strong><i class="bi bi-clock"></i><?php echo e($event->time_range); ?></strong></div>
                        <div class="glass-soft"><small>Lokasi</small><strong><i class="bi bi-geo-alt"></i><?php echo e($event->location); ?></strong></div>
                        <div class="glass-soft"><small>Penyelenggara</small><strong><i class="bi bi-people"></i><?php echo e($event->organizer); ?></strong></div>
                    </div>

                    <h2 class="h5 mb-2">Tentang acara</h2>
                    <div class="measure" style="color: var(--muted)"><?php echo nl2br(e($event->description)); ?></div>
                </div>
            </article>
        </div>

        
        <div class="col-lg-4">
            <aside class="glass pad" style="position: sticky; top: 96px;">
                <div class="d-flex justify-content-between align-items-baseline mb-1">
                    <h2 class="h5 mb-0">Kuota peserta</h2>
                    <span class="muted small"><?php echo e($event->taken_count); ?> dari <?php echo e($event->quota); ?></span>
                </div>
                <div class="meter <?php echo e($event->fill_percent >= 80 ? 'hot' : ''); ?> mb-2" role="progressbar"
                     aria-valuenow="<?php echo e($event->fill_percent); ?>" aria-valuemin="0" aria-valuemax="100">
                    <span style="width: <?php echo e($event->fill_percent); ?>%"></span>
                </div>
                <p class="mb-3 <?php echo e($event->is_full ? 'text-warning fw-semibold' : 'muted'); ?>">
                    <?php if($event->is_full): ?> Kuota penuh <?php else: ?> Tersisa <?php echo e($event->remaining); ?> kursi <?php endif; ?>
                </p>
                <div class="divider"></div>

                <?php if($event->isFinished()): ?>
                    <p class="muted mb-0">Acara ini sudah selesai, jadi pendaftaran sudah ditutup.</p>

                <?php elseif(! auth()->check()): ?>
                    <p class="muted">Masuk dulu untuk mendaftar dan mendapatkan kode registrasi.</p>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-glow w-100 mb-2">Masuk untuk mendaftar</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-glass w-100">Buat akun peserta</a>

                <?php elseif(auth()->user()->isPanitia()): ?>
                    <?php if($event->user_id === auth()->id()): ?>
                        <p class="muted">Ini acara yang kamu kelola.</p>
                        <a href="<?php echo e(route('panitia.attendees.index', $event)); ?>" class="btn btn-glow w-100 mb-2"><i class="bi bi-people"></i> Kelola peserta</a>
                        <a href="<?php echo e(route('panitia.events.edit', $event)); ?>" class="btn btn-glass w-100"><i class="bi bi-pencil"></i> Ubah acara</a>
                    <?php else: ?>
                        <p class="muted mb-0">Akun panitia tidak dapat mendaftar ke acara. Gunakan akun peserta untuk mendaftar.</p>
                    <?php endif; ?>

                <?php elseif($registration && $registration->isActive()): ?>
                    <p class="fw-semibold mb-2"><i class="bi bi-check-circle text-success"></i> Kamu sudah terdaftar</p>
                    <?php echo $__env->make('partials.ticket', ['registration' => $registration->setRelation('event', $event)], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <div class="d-flex gap-2 mt-3">
                        <a href="<?php echo e(route('peserta.dashboard')); ?>" class="btn btn-glass btn-sm flex-grow-1">Lihat tiket saya</a>
                        <?php if(! $registration->hasAttended()): ?>
                            <form method="POST" action="<?php echo e(route('registrations.cancel', $registration)); ?>" onsubmit="return confirm('Batalkan pendaftaran untuk acara ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-soft-danger btn-sm" type="submit">Batalkan</button>
                            </form>
                        <?php endif; ?>
                    </div>

                <?php elseif($registration && $registration->status === 'rejected'): ?>
                    <p class="muted mb-0">Pendaftaranmu ditolak panitia. Hubungi penyelenggara jika menurutmu ini keliru.</p>

                <?php elseif($event->is_full): ?>
                    <button class="btn btn-glass w-100" disabled>Kuota penuh</button>

                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('registrations.store', $event)); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-glow btn-lg w-100" type="submit"><i class="bi bi-ticket-perforated"></i> Daftar sekarang</button>
                    </form>
                    <p class="form-hint mb-0">Kode registrasi langsung muncul setelah kamu mendaftar.</p>
                <?php endif; ?>
            </aside>
        </div>
    </div>

    <?php if($related->isNotEmpty()): ?>
        <section class="section">
            <div class="section-head"><h2 class="section-title">Acara <?php echo e(strtolower($event->category)); ?> lainnya</h2></div>
            <div class="row g-4">
                <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4"><?php echo $__env->make('partials.event-card', ['event' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/events/show.blade.php ENDPATH**/ ?>