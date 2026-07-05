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
    </div>

    <div class="section mt-3 mb-5">
        <a href="{{ route('activities') }}" class="btn btn-primary btn-block">Kembali ke Daftar Kegiatan</a>
    </div>
@endsection
