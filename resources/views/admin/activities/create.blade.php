@extends('layouts.admin')

@section('content')
    <div class="card"><div class="card-body">
        <h3>Tambah Kegiatan</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.activities.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group basic"><label class="label">Judul</label><input name="title" class="form-control" value="{{ old('title') }}" required></div>
            <div class="form-group basic"><label class="label">Tanggal</label><input type="date" name="activity_date" class="form-control" value="{{ old('activity_date') }}"></div>
            <div class="form-group basic"><label class="label">Lokasi</label><input name="location" class="form-control" value="{{ old('location') }}"></div>
            <div class="form-group basic"><label class="label">Upload Gambar Utama</label><input type="file" name="image_file" class="form-control" accept="image/*"></div>
            <div class="form-group basic">
                <label class="label">Upload Galeri Kegiatan</label>
                <input type="file" name="gallery_files[]" class="form-control" accept="image/*" multiple>
                <small class="text-muted">Maksimal 10 foto galeri per kegiatan dan maksimal 1 MB per foto.</small>
            </div>
            <div class="form-group basic"><label class="label">Path Gambar</label><input name="image" class="form-control" value="{{ old('image') }}"></div>
            <div class="form-group basic">
                <label class="label">Link YouTube</label>
                <input type="text" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('youtube_url') }}">
                <small class="text-muted">Opsional. Boleh tanpa "https://", akan ditambahkan otomatis. Video akan ditampilkan di halaman detail kegiatan.</small>
            </div>
            <div class="form-group basic"><label class="label">Deskripsi</label><textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea></div>
            <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_published" value="1" checked><label class="form-check-label">Publish</label></div>
            <button class="btn btn-primary btn-block" type="submit">Simpan</button>
        </form>
    </div></div>
@endsection
