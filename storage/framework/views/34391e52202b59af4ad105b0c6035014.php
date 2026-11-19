<?php $__env->startSection('title', 'Temukan acara kampus'); ?>

<?php $__env->startSection('content'); ?>
    
    <section class="hero">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <h1 class="display-title mb-3">Temukan acaranya, daftar, lalu hadir dengan satu kode.</h1>
                <p class="lead-soft mb-4">
                    Seminar, webinar, workshop, kompetisi, sampai kegiatan organisasi mahasiswa ada di satu tempat.
                    Panitia tidak perlu lagi merekap pendaftar dan kehadiran secara manual.
                </p>

                <form method="GET" action="<?php echo e(route('events.index')); ?>" class="search-bar glass" role="search">
                    <input type="search" name="q" class="form-control grow" placeholder="Cari acara atau penyelenggara" aria-label="Kata kunci">
                    <select name="category" class="form-select" style="flex: 0 1 160px" aria-label="Kategori">
                        <option value="">Semua kategori</option>
                        <?php $__currentLoopData = \App\Models\Event::CATEGORIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($name); ?>"><?php echo e($name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button class="btn btn-glow" type="submit"><i class="bi bi-search"></i> Cari acara</button>
                </form>

                <div class="stat-inline mt-4">
                    <div><b><?php echo e(number_format($stats['events'])); ?></b><span>acara terdata</span></div>
                    <div><b><?php echo e(number_format($stats['participants'])); ?></b><span>peserta terdaftar</span></div>
                    <div><b><?php echo e(number_format($stats['registrations'])); ?></b><span>pendaftaran aktif</span></div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="ticket-stack" aria-hidden="<?php echo e($next ? 'false' : 'true'); ?>">
                    <div class="ticket ghost tone-coral" aria-hidden="true">
                        <div class="ticket-top"><span class="sk" style="width: 28%"></span><span class="sk" style="width: 74%; height: 16px"></span><span class="sk" style="width: 52%"></span></div>
                        <div class="ticket-bottom"><span class="sk" style="width: 46%; height: 18px; margin: 0"></span><div class="ticket-bar"></div></div>
                    </div>

                    <div class="ticket tone-<?php echo e($next->tone ?? 'violet'); ?>">
                        <div class="ticket-top">
                            <span class="pill pill-tone"><i class="bi <?php echo e($next->icon ?? 'bi-mic'); ?>"></i> <?php echo e($next->category ?? 'Seminar'); ?></span>
                            <h3><?php echo e($next->title ?? 'Acara pertamamu akan tampil di sini'); ?></h3>
                            <?php if($next): ?>
                                <div class="meta">
                                    <span><i class="bi bi-calendar-event"></i><?php echo e($next->event_date->translatedFormat('l, d F Y')); ?></span>
                                    <span><i class="bi bi-geo-alt"></i><?php echo e($next->location); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ticket-bottom">
                            <div>
                                <div class="ticket-label">Contoh kode registrasi</div>
                                <div class="ticket-code">EP-7K3QX9</div>
                            </div>
                            <div class="ticket-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="section">
        <div class="section-head">
            <h2 class="section-title">Jelajahi berdasarkan kategori</h2>
        </div>
        <div class="chips">
            <?php $__currentLoopData = \App\Models\Event::CATEGORIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('events.index', ['category' => $name])); ?>" class="chip tone-<?php echo e($meta['tone']); ?>">
                    <i class="bi <?php echo e($meta['icon']); ?>"></i> <?php echo e($name); ?>

                    <em><?php echo e($categoryCounts[$name] ?? 0); ?> mendatang</em>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    
    <section class="section">
        <div class="section-head">
            <h2 class="section-title">Acara yang akan datang</h2>
            <a href="<?php echo e(route('events.index')); ?>" class="fw-semibold">Lihat semua acara</a>
        </div>
        <?php if($upcoming->isEmpty()): ?>
            <div class="glass empty"><i class="bi bi-calendar-x"></i><p class="mt-2 mb-0 muted">Belum ada acara mendatang. Cek lagi sebentar lagi.</p></div>
        <?php else: ?>
            <div class="row g-4">
                <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4"><?php echo $__env->make('partials.event-card', ['event' => $event], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </section>

    
    <?php if($popular->isNotEmpty()): ?>
        <section class="section">
            <div class="section-head">
                <h2 class="section-title">Paling banyak diminati</h2>
                <span class="muted small">Berdasarkan jumlah pendaftar aktif</span>
            </div>
            <div class="row g-4">
                <?php $__currentLoopData = $popular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4"><?php echo $__env->make('partials.event-card', ['event' => $event], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if(auth()->guard()->guest()): ?>
        <section class="section">
            <div class="glass pad d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h2 class="section-title mb-1">Mengelola acara kampus?</h2>
                    <p class="muted mb-0">Buat akun panitia untuk membuka pendaftaran, memantau kuota, mencatat kehadiran, dan melihat laporan partisipasi.</p>
                </div>
                <a href="<?php echo e(route('register', ['role' => 'panitia'])); ?>" class="btn btn-glow btn-lg">Daftar sebagai panitia</a>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/home.blade.php ENDPATH**/ ?>