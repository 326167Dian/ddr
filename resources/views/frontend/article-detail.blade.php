@extends('layouts.mobile')

@section('content')
    <div class="section mt-3">
        <div class="card">
            @if($article->cover_image)
                <img src="{{ asset($article->cover_image) }}" class="card-img-top" alt="cover">
            @endif
            <div class="card-body">
                <h3 class="mb-1">{{ $article->title }}</h3>
                <p class="text-secondary mb-2">{{ optional($article->published_at)->format('d M Y H:i') }}</p>
                <p class="mb-2">{{ $article->excerpt }}</p>
                <div class="article-body">{!! $article->content !!}</div>
            </div>
        </div>
    </div>

    <div class="section mt-3 mb-5">
        <a href="{{ route('articles') }}" class="btn btn-primary btn-block">Kembali ke Daftar Artikel</a>
    </div>
@endsection
