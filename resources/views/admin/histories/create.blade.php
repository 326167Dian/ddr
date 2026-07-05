@extends('layouts.admin')

@section('content')
    <div class="card"><div class="card-body">
        <h3>Tambah Sejarah</h3>
        <form method="POST" action="{{ route('admin.histories.store') }}">
            @csrf
            <div class="form-group basic"><label class="label">Tahun</label><input name="year" class="form-control" value="{{ old('year') }}" required></div>
            <div class="form-group basic"><label class="label">Judul</label><input name="title" class="form-control" value="{{ old('title') }}" required></div>
            <div class="form-group basic"><label class="label">Deskripsi</label><textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea></div>
            <div class="form-group basic"><label class="label">Urutan</label><input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}"></div>
            <button class="btn btn-primary btn-block" type="submit">Simpan</button>
        </form>
    </div></div>
@endsection
