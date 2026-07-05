@extends('layouts.admin')

@section('content')
    <div class="card"><div class="card-body">
        <h3>Edit Kegiatan</h3>
        <form method="POST" action="{{ route('admin.activities.update', $activity) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group basic"><label class="label">Judul</label><input name="title" class="form-control" value="{{ old('title', $activity->title) }}" required></div>
            <div class="form-group basic"><label class="label">Tanggal</label><input type="date" name="activity_date" class="form-control" value="{{ old('activity_date', optional($activity->activity_date)->format('Y-m-d')) }}"></div>
            <div class="form-group basic"><label class="label">Lokasi</label><input name="location" class="form-control" value="{{ old('location', $activity->location) }}"></div>
            <div class="form-group basic"><label class="label">Upload Gambar Baru</label><input type="file" name="image_file" class="form-control" accept="image/*"></div>
            <div class="form-group basic"><label class="label">Path Gambar</label><input name="image" class="form-control" value="{{ old('image', $activity->image) }}"></div>
            @if($activity->image)
                <div class="mb-2">
                    <img src="{{ asset($activity->image) }}" alt="preview" style="max-width: 180px; border-radius: 8px;">
                </div>
            @endif
            <div class="form-group basic"><label class="label">Deskripsi</label><textarea name="description" class="form-control" rows="4">{{ old('description', $activity->description) }}</textarea></div>
            <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $activity->is_published) ? 'checked' : '' }}><label class="form-check-label">Publish</label></div>
            <button class="btn btn-primary btn-block" type="submit">Simpan Perubahan</button>
        </form>
    </div></div>
@endsection
