{{-- Variabel: $event, $categories, $action, $method (POST|PUT), $submit --}}
@php
    $val = fn ($key, $default = '') => old($key, $event->{$key} ?? $default);
    $date = old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : '');
    $start = old('start_time', $event->start_time ? substr($event->start_time, 0, 5) : '');
    $end = old('end_time', $event->end_time ? substr($event->end_time, 0, 5) : '');
@endphp

<form method="POST" action="{{ $action }}" novalidate>
    @csrf
    @if ($method !== 'POST') @method($method) @endif

    <div class="row g-3">
        <div class="col-12">
            <label for="title" class="form-label">Judul acara</label>
            <input type="text" id="title" name="title" value="{{ $val('title') }}" required maxlength="150" class="form-control @error('title') is-invalid @enderror">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label for="category" class="form-label">Kategori</label>
            <select id="category" name="category" required class="form-select @error('category') is-invalid @enderror">
                <option value="" disabled @selected($val('category') === '')>Pilih kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" @selected($val('category') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-8">
            <label for="organizer" class="form-label">Penyelenggara</label>
            <input type="text" id="organizer" name="organizer" value="{{ $val('organizer') }}" required maxlength="120" class="form-control @error('organizer') is-invalid @enderror">
            @error('organizer') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label for="location" class="form-label">Lokasi</label>
            <input type="text" id="location" name="location" value="{{ $val('location') }}" required maxlength="150" class="form-control @error('location') is-invalid @enderror" placeholder="Nama ruangan, gedung, atau tautan online">
            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label for="event_date" class="form-label">Tanggal</label>
            <input type="date" id="event_date" name="event_date" value="{{ $date }}" required class="form-control @error('event_date') is-invalid @enderror">
            @error('event_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-6 col-md-3">
            <label for="start_time" class="form-label">Jam mulai</label>
            <input type="time" id="start_time" name="start_time" value="{{ $start }}" required class="form-control @error('start_time') is-invalid @enderror">
            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-6 col-md-3">
            <label for="end_time" class="form-label">Jam selesai</label>
            <input type="time" id="end_time" name="end_time" value="{{ $end }}" required class="form-control @error('end_time') is-invalid @enderror">
            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-2">
            <label for="quota" class="form-label">Kuota</label>
            <input type="number" id="quota" name="quota" value="{{ $val('quota', 100) }}" required min="1" class="form-control @error('quota') is-invalid @enderror">
            @error('quota') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea id="description" name="description" rows="6" required class="form-control @error('description') is-invalid @enderror" placeholder="Apa yang akan dipelajari atau dilakukan peserta, siapa pembicaranya, dan apa yang perlu dibawa.">{{ $val('description') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 justify-content-end mt-4">
        <a href="{{ route('panitia.events.index') }}" class="btn btn-glass">Batal</a>
        <button type="submit" class="btn btn-glow">{{ $submit }}</button>
    </div>
</form>
