@extends('layouts.mobile')

@section('content')
    <div class="section mt-3">
        <div class="header-large-title">
            <h1 class="title">Artikel Organisasi</h1>
            <h4 class="subtitle">Publikasi pemikiran dan dakwah</h4>
        </div>
    </div>

    <div class="section mt-2 mb-5">
        @forelse($articles as $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="card mb-2">
                @if($article->cover_image)
                    <img src="{{ asset($article->cover_image) }}" class="card-img-top" alt="cover artikel">
                @endif
                <div class="card-body">
                    <h5>{{ $article->title }}</h5>
                    <p class="text-secondary mb-1">{{ optional($article->published_at)->format('d M Y H:i') }}</p>
                    <p class="mb-0">{{ $article->excerpt }}</p>
                </div>
            </a>
        @empty
            <div class="card"><div class="card-body">Belum ada artikel yang dipublikasikan.</div></div>
        @endforelse
    </div>
@endsection
