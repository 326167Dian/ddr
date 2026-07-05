@extends('layouts.admin')

@section('content')
    <div class="mb-3">
        <h3 class="mb-1">Dashboard Admin</h3>
        <p class="text-secondary mb-0">Ringkasan cepat pengelolaan konten DEWAN DAKWAH RISALAH.</p>
    </div>

    <div class="dashboard-grid">
        <div class="card stat-card"><div class="card-body"><div class="stat-meta">Pengurus Aktif</div><div class="stat-number">{{ $activeStructures }}</div><div class="stat-meta">dari {{ $totalStructures }} data struktur</div></div></div>
        <div class="card stat-card"><div class="card-body"><div class="stat-meta">Data Sejarah</div><div class="stat-number">{{ $totalHistories }}</div><div class="stat-meta">timeline organisasi tersimpan</div></div></div>
        <div class="card stat-card soft"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-2"><div><div class="stat-meta">Kegiatan Publish</div><div class="stat-number">{{ $publishedActivities }}</div></div><div class="text-end"><div class="stat-meta">Total</div><strong>{{ $totalActivities }}</strong></div></div><div class="mini-progress"><span style="width: {{ $totalActivities > 0 ? ($publishedActivities / $totalActivities) * 100 : 0 }}%"></span></div></div></div>
        <div class="card stat-card soft"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-2"><div><div class="stat-meta">Artikel Publish</div><div class="stat-number">{{ $publishedArticles }}</div></div><div class="text-end"><div class="stat-meta">Total</div><strong>{{ $totalArticles }}</strong></div></div><div class="mini-progress"><span style="width: {{ $totalArticles > 0 ? ($publishedArticles / $totalArticles) * 100 : 0 }}%"></span></div></div></div>
    </div>

    <div class="dashboard-panels">
        <div class="card panel-card">
            <div class="card-body">
                <h5 class="mb-2">Kegiatan Terbaru</h5>
                @forelse($recentActivities as $activity)
                    <div class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                        <div>
                            <strong>{{ $activity->title }}</strong>
                            <div class="text-secondary small">{{ $activity->location ?: 'Lokasi belum diisi' }}</div>
                        </div>
                        <span class="badge badge-primary">{{ optional($activity->activity_date)->format('d M Y') }}</span>
                    </div>
                @empty
                    <div class="text-secondary">Belum ada kegiatan.</div>
                @endforelse
            </div>
        </div>

        <div class="card panel-card">
            <div class="card-body">
                <h5 class="mb-2">Artikel Terbaru</h5>
                @forelse($recentArticles as $article)
                    <div class="mb-2 pb-2 border-bottom">
                        <strong>{{ $article->title }}</strong>
                        <div class="text-secondary small">{{ optional($article->published_at)->format('d M Y H:i') ?: 'Belum dijadwalkan' }}</div>
                    </div>
                @empty
                    <div class="text-secondary">Belum ada artikel.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
