@extends('layouts.admin')

@section('content')
    <div class="card"><div class="card-body">
        <h3>Edit Struktur</h3>
        <form method="POST" action="{{ route('admin.structures.update', $structure) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group basic"><label class="label">Nama</label><input name="name" class="form-control" value="{{ old('name', $structure->name) }}" required></div>
            <div class="form-group basic"><label class="label">Jabatan</label><input name="position" class="form-control" value="{{ old('position', $structure->position) }}" required></div>
            <div class="form-group basic"><label class="label">Bio</label><textarea name="bio" class="form-control" rows="3">{{ old('bio', $structure->bio) }}</textarea></div>
            <div class="form-group basic"><label class="label">Upload Foto Pengurus</label><input type="file" name="photo_file" class="form-control" accept="image/*"></div>
            <div class="form-group basic"><label class="label">Path Foto</label><input name="photo" class="form-control" value="{{ old('photo', $structure->photo) }}"></div>
            @if($structure->photo)
                <div class="mb-2"><img src="{{ asset($structure->photo) }}" alt="foto pengurus" class="form-image-preview"></div>
            @endif
            <div class="form-group basic"><label class="label">Urutan</label><input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $structure->sort_order) }}"></div>
            <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $structure->is_active) ? 'checked' : '' }}><label class="form-check-label">Aktif</label></div>
            <button class="btn btn-primary btn-block" type="submit">Simpan Perubahan</button>
        </form>
    </div></div>
@endsection
