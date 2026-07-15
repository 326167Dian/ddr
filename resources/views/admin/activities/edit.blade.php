@extends('layouts.admin')

@section('content')
    <div class="card"><div class="card-body">
        <h3>Edit Kegiatan</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.activities.update', $activity) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group basic"><label class="label">Judul</label><input name="title" class="form-control" value="{{ old('title', $activity->title) }}" required></div>
            <div class="form-group basic"><label class="label">Tanggal</label><input type="date" name="activity_date" class="form-control" value="{{ old('activity_date', optional($activity->activity_date)->format('Y-m-d')) }}"></div>
            <div class="form-group basic"><label class="label">Lokasi</label><input name="location" class="form-control" value="{{ old('location', $activity->location) }}"></div>
            <div class="form-group basic"><label class="label">Upload Gambar Utama Baru</label><input type="file" name="image_file" class="form-control" accept="image/*"></div>
            <div class="form-group basic">
                <label class="label">Upload Galeri Kegiatan Baru</label>
                <input type="file" name="gallery_files[]" class="form-control" accept="image/*" multiple>
                <small class="text-muted">Maksimal 10 foto galeri per kegiatan dan maksimal 1 MB per foto.</small>
            </div>
            <div class="form-group basic"><label class="label">Path Gambar</label><input name="image" class="form-control" value="{{ old('image', $activity->image) }}"></div>
            @if($activity->image)
                <div class="mb-2">
                    <img src="{{ asset($activity->image) }}" alt="preview" style="max-width: 180px; border-radius: 8px;">
                </div>
            @endif
            <div class="form-group basic">
                <label class="label">Link YouTube</label>
                <input type="text" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('youtube_url', $activity->youtube_url) }}">
                <small class="text-muted">Opsional. Boleh tanpa "https://", akan ditambahkan otomatis. Video akan ditampilkan di halaman detail kegiatan.</small>
            </div>
            <div class="form-group basic"><label class="label">Deskripsi</label><textarea name="description" class="form-control" rows="4">{{ old('description', $activity->description) }}</textarea></div>
            <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $activity->is_published) ? 'checked' : '' }}><label class="form-check-label">Publish</label></div>
            <button class="btn btn-primary btn-block" type="submit">Simpan Perubahan</button>
        </form>

        @if(!empty($activity->gallery_images))
            <div class="form-group basic mt-3">
                <label class="label">Galeri Saat Ini</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($activity->gallery_images as $galleryIndex => $galleryImage)
                        <div class="text-center">
                            <img src="{{ asset($galleryImage) }}" alt="galeri kegiatan" style="width:120px; height:90px; object-fit:cover; border-radius:8px;">
                            <div class="mt-1 d-flex gap-2 justify-content-center">
                                <a href="{{ asset($galleryImage) }}" download class="btn btn-sm btn-outline-primary">Download</a>
                                <form method="POST" action="{{ route('admin.activities.gallery.remove', $activity) }}">
                                    @csrf
                                    <input type="hidden" name="index" value="{{ $galleryIndex }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div></div>
@endsection
