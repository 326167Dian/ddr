@extends('layouts.mobile')

@section('content')
    <div class="section mt-3">
        <div class="card ddr-soft-card">
            @if($activity->image)
                <img src="{{ asset($activity->image) }}" class="card-img-top" alt="kegiatan">
            @endif
            <div class="card-body">
                <h3 class="mb-1">{{ $activity->title }}</h3>
                <p class="text-secondary mb-2">{{ optional($activity->activity_date)->format('d M Y') }} · {{ $activity->location }}</p>
                <div style="white-space: pre-line">{{ $activity->description }}</div>
            </div>
        </div>

        @if(!empty($activity->gallery_images))
            <div class="card ddr-soft-card mt-3">
                <div class="card-body">
                    <h5 class="mb-3">Dokumentasi Foto</h5>
                    <div class="row g-2">
                        @foreach($activity->gallery_images as $galleryImage)
                            <div class="col-6 col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <img src="{{ asset($galleryImage) }}" class="card-img-top" alt="Dokumentasi kegiatan" style="height:160px; object-fit:cover;">
                                    <div class="card-body p-2 text-center">
                                        <a href="{{ asset($galleryImage) }}" class="btn btn-sm btn-primary w-100" download>Download</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="section mt-3 mb-5">
        <a href="{{ route('activities') }}" class="btn btn-primary btn-block">Kembali ke Daftar Kegiatan</a>
    </div>
@endsection
