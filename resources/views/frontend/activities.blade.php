@extends('layouts.mobile')

@section('content')
    <div class="section mt-3">
        <div class="header-large-title">
            <h1 class="title">Kegiatan Organisasi</h1>
            <h4 class="subtitle">Agenda dakwah dan sosial</h4>
        </div>
    </div>

    <div class="section mt-2 mb-5">
        @forelse($activities as $activity)
            <a href="{{ route('activities.show', $activity) }}" class="card mb-2">
                @if($activity->image)
                    <img src="{{ asset($activity->image) }}" class="card-img-top" alt="kegiatan">
                @endif
                <div class="card-body">
                    <h5>{{ $activity->title }}</h5>
                    <p class="text-secondary mb-1">{{ optional($activity->activity_date)->format('d M Y') }} · {{ $activity->location }}</p>
                    <p class="mb-0">{{ $activity->description }}</p>
                </div>
            </a>
        @empty
            <div class="card"><div class="card-body">Belum ada kegiatan yang dipublikasikan.</div></div>
        @endforelse
    </div>
@endsection
