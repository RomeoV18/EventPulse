<?php $__env->startSection('title', 'Katalog acara'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <h1 class="page-title mb-1">Katalog acara</h1>
            <p class="muted mb-0">Cari berdasarkan kata kunci, kategori, tanggal, atau lokasi.</p>
        </div>
    </div>

    <form method="GET" action="<?php echo e(route('events.index')); ?>" class="glass pad mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-4">
                <label for="q" class="form-label">Kata kunci</label>
                <input type="search" id="q" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" class="form-control" placeholder="Judul, deskripsi, atau penyelenggara">
            </div>
            <div class="col-sm-6 col-lg-2">
                <label for="category" class="form-label">Kategori</label>
                <select id="category" name="category" class="form-select">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php if(($filters['category'] ?? '') === $cat): echo 'selected'; endif; ?>><?php echo e($cat); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <label for="date" class="form-label">Mulai tanggal</label>
                <input type="date" id="date" name="date" value="<?php echo e($filters['date'] ?? ''); ?>" class="form-control">
            </div>
            <div class="col-lg-4">
                <label for="location" class="form-label">Lokasi</label>
                <input type="text" id="location" name="location" value="<?php echo e($filters['location'] ?? ''); ?>" class="form-control" placeholder="Contoh: Aula, Lab, Online">
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-3">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="past" value="1" id="past" <?php if($showPast): echo 'checked'; endif; ?>>
                <label class="form-check-label muted" for="past">Sertakan acara yang sudah selesai</label>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('events.index')); ?>" class="btn btn-glass">Atur ulang</a>
                <button type="submit" class="btn btn-glow"><i class="bi bi-funnel"></i> Terapkan filter</button>
            </div>
        </div>
    </form>

    <p class="muted mb-3" aria-live="polite"><strong class="text-white"><?php echo e($events->total()); ?></strong> acara ditemukan</p>

    <?php if($events->isEmpty()): ?>
        <div class="glass empty">
            <i class="bi bi-search"></i>
            <h2 class="h5 mt-3">Tidak ada acara yang cocok</h2>
            <p class="muted">Coba kurangi filter atau gunakan kata kunci yang lebih umum.</p>
            <a href="<?php echo e(route('events.index')); ?>" class="btn btn-glass">Tampilkan semua acara</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4"><?php echo $__env->make('partials.event-card', ['event' => $event], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-4 d-flex justify-content-center"><?php echo e($events->links()); ?></div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\eventpulse\resources\views/events/index.blade.php ENDPATH**/ ?>