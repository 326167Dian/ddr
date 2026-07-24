@extends('layouts.mobile')

@section('content')
    <div class="section mt-3">
        <div class="header-large-title">
            <h1 class="title">Berita Organisasi</h1>
            <h4 class="subtitle">Informasi dan kabar terbaru</h4>
        </div>
    </div>

    <div class="section mt-2 mb-5">
        @forelse($news as $item)
            <a href="{{ route('news.show', $item->slug) }}" class="card mb-2">
                @if($item->image)
                    <div class="news-cover">
                        <img src="{{ asset($item->image) }}" class="news-cover-image" alt="gambar berita" loading="lazy">
                    </div>
                @endif
                <div class="card-body">
                    <h5>{{ $item->title }}</h5>
                    <p class="text-secondary mb-1">{{ optional($item->published_at)->format('d M Y H:i') }}</p>
                    <p class="mb-0">{{ $item->excerpt }}</p>
                </div>
            </a>
        @empty
            <div class="card"><div class="card-body">Belum ada berita yang dipublikasikan.</div></div>
        @endforelse
    </div>
@endsection

@push('styles')
    <style>
        .news-cover {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            background: #f8f9fa;
            overflow: hidden;
        }

        .news-cover-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
    </style>
@endpush
