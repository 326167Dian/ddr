@extends('layouts.mobile')

@section('content')
    <div class="section mt-3 ddr-reveal">
        <div class="header-large-title">
            <h1 class="title">Struktur Organisasi</h1>
            <h4 class="subtitle">Pengurus DEWAN DAKWAH RISALAH</h4>
        </div>
    </div>

    <div class="section mt-2 ddr-reveal ddr-reveal-delay-1">
        <div class="card ddr-soft-card mb-2">
            <div class="card-body">
                <h6 class="card-subtitle">Pola Organisasi</h6>
                <h5>Musyawarah Tahunan</h5>
                <p class="mb-0">Struktur DDR ditopang Dewan Pembina, Ketua Umum, Dewan Pakar, serta unsur pendukung dan unsur fungsional lintas departemen.</p>
            </div>
        </div>
    </div>

    <div class="section mt-2 mb-5 ddr-reveal ddr-reveal-delay-2">
        @forelse($structures as $member)
            <div class="card ddr-soft-card mb-2">
                <div class="card-body">
                    @if($member->photo)
                        <div class="mb-2">
                            <img src="{{ asset($member->photo) }}" alt="{{ $member->name }}" class="member-photo-preview">
                        </div>
                    @endif
                    <span class="badge badge-primary mb-1">{{ $member->position }}</span>
                    <h5 class="mb-1">{{ $member->name }}</h5>
                    <p class="mb-0">{{ $member->bio }}</p>
                </div>
            </div>
        @empty
            <div class="card"><div class="card-body">Data struktur belum tersedia.</div></div>
        @endforelse
    </div>
@endsection
