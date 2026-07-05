@extends('layouts.mobile')

@section('content')
    <div class="section mt-3 ddr-reveal">
        <div class="hero-cover" style="background-image: url('{{ asset($profile->hero_image ?? 'mobilekit/img/sample/photo/wide4.jpg') }}')">
            <div class="hero-content">
                <span class="ddr-chip mb-2">Dashboard Utama</span>
                <h2 class="mb-1">{{ $profile->hero_title ?? 'Halal Bihalal Dewan Dakwah Risalah 1446 H' }}</h2>
                <p class="mb-0">{{ $profile->hero_subtitle ?? 'Menyatukan langkah dakwah dalam ukhuwah islamiyah.' }}</p>
            </div>
        </div>
    </div>

    <div class="section mt-3 ddr-reveal ddr-reveal-delay-1">
        <div class="card ddr-soft-card">
            <div class="card-body">
                <h6 class="card-subtitle">Tentang Organisasi</h6>
                <h5 class="card-title">{{ $profile->name ?? 'DEWAN DAKWAH RISALAH' }}</h5>
                <p class="mb-2">{{ $profile->about ?? 'Organisasi dakwah keislaman yang fokus pada pembinaan umat, edukasi, dan gerakan sosial.' }}</p>
                <p class="mb-1"><strong>Visi:</strong> {{ $profile->vision ?? '-' }}</p>
                <p class="mb-0"><strong>Misi:</strong></p>
                <div style="white-space: pre-line">{{ $profile->mission ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="section mt-3 ddr-reveal ddr-reveal-delay-2">
        <div class="row">
            <div class="col-6">
                <div class="card ddr-soft-card mb-2">
                    <div class="card-body">
                        <h6 class="text-primary mb-1">Nilai Utama</h6>
                        <h5 class="mb-0">Amanah</h5>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card ddr-soft-card mb-2">
                    <div class="card-body">
                        <h6 class="text-primary mb-1">Nilai Utama</h6>
                        <h5 class="mb-0">Tabligh</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card ddr-soft-card">
            <div class="card-body">
                <h6 class="card-subtitle">Orientasi Organisasi</h6>
                <p class="mb-0">DDR diproyeksikan menjadi institusi dakwah yang terkemuka: paling didengar, paling dikenal, paling berpengaruh, dan paling maju dalam pelayanan umat.</p>
            </div>
        </div>
    </div>

    <div class="section mt-3 ddr-reveal ddr-reveal-delay-3">
        <div class="section-title">Kegiatan Terdekat</div>
        @forelse($activities as $activity)
            <a href="{{ route('activities.show', $activity) }}" class="card ddr-soft-card mb-2">
                <div class="card-body">
                    <h5 class="mb-1">{{ $activity->title }}</h5>
                    <p class="text-secondary mb-1">{{ optional($activity->activity_date)->format('d M Y') }} · {{ $activity->location }}</p>
                    <p class="mb-0">{{ $activity->description }}</p>
                </div>
            </a>
        @empty
            <div class="card"><div class="card-body">Belum ada kegiatan.</div></div>
        @endforelse
    </div>

    <div class="section mt-3 mb-5 ddr-reveal ddr-reveal-delay-3">
        <div class="section-title">Artikel Terbaru</div>
        @forelse($articles as $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="card ddr-soft-card mb-2">
                <div class="card-body">
                    <h5 class="mb-1">{{ $article->title }}</h5>
                    <p class="mb-0 text-secondary">{{ $article->excerpt }}</p>
                </div>
            </a>
        @empty
            <div class="card"><div class="card-body">Belum ada artikel.</div></div>
        @endforelse
    </div>
@endsection
