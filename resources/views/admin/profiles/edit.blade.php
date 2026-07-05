@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-2">Profil Organisasi</h3>
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group basic"><label class="label">Nama</label><input class="form-control" name="name" value="{{ old('name', $profile->name) }}" required></div>
                <div class="form-group basic"><label class="label">Tagline</label><input class="form-control" name="tagline" value="{{ old('tagline', $profile->tagline) }}"></div>
                <div class="form-group basic"><label class="label">Judul Hero</label><input class="form-control" name="hero_title" value="{{ old('hero_title', $profile->hero_title) }}"></div>
                <div class="form-group basic"><label class="label">Subjudul Hero</label><input class="form-control" name="hero_subtitle" value="{{ old('hero_subtitle', $profile->hero_subtitle) }}"></div>
                <div class="form-group basic"><label class="label">Upload Logo Baru</label><input type="file" class="form-control" name="logo_file" accept="image/*"></div>
                <div class="form-group basic"><label class="label">Upload Gambar Halaman Depan</label><input type="file" class="form-control" name="hero_file" accept="image/*"></div>
                <div class="form-group basic"><label class="label">Path Gambar Depan (halamandepan.png)</label><input class="form-control" name="hero_image" value="{{ old('hero_image', $profile->hero_image) }}"></div>
                <div class="form-group basic"><label class="label">Path Logo (logo_ddr.png)</label><input class="form-control" name="logo_path" value="{{ old('logo_path', $profile->logo_path) }}"></div>
                <div class="form-group basic"><label class="label">Visi</label><textarea class="form-control" rows="3" name="vision">{{ old('vision', $profile->vision) }}</textarea></div>
                <div class="form-group basic"><label class="label">Misi</label><textarea class="form-control" rows="4" name="mission">{{ old('mission', $profile->mission) }}</textarea></div>
                <div class="form-group basic"><label class="label">Tentang</label><textarea class="form-control" rows="4" name="about">{{ old('about', $profile->about) }}</textarea></div>
                <div class="form-group basic"><label class="label">Email</label><input type="email" class="form-control" name="contact_email" value="{{ old('contact_email', $profile->contact_email) }}"></div>
                <div class="form-group basic"><label class="label">Telepon</label><input class="form-control" name="contact_phone" value="{{ old('contact_phone', $profile->contact_phone) }}"></div>
                <div class="form-group basic"><label class="label">Alamat</label><textarea class="form-control" rows="2" name="address">{{ old('address', $profile->address) }}</textarea></div>
                <button type="submit" class="btn btn-primary btn-block">Simpan Profil</button>
            </form>
        </div>
    </div>
@endsection
