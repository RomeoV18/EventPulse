@if (session('success'))
    <div class="flash flash-success" role="status">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
    </div>
@endif
@if (session('error'))
    <div class="flash flash-error" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i><span>{{ session('error') }}</span>
    </div>
@endif
