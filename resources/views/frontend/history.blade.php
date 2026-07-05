@extends('layouts.mobile')

@section('content')
    <div class="section mt-3 ddr-reveal">
        <div class="header-large-title">
            <h1 class="title">Sejarah Organisasi</h1>
            <h4 class="subtitle">Perjalanan DEWAN DAKWAH RISALAH</h4>
        </div>
    </div>

    <div class="section mt-2 ddr-reveal ddr-reveal-delay-1">
        <div class="card ddr-soft-card mb-2">
            <div class="card-body">
                <h6 class="card-subtitle">Karakter Lembaga</h6>
                <p class="mb-1">DDR berperan sebagai <strong>think tank</strong> yang memberi ide, gagasan, dan pemikiran dakwah.</p>
                <p class="mb-1">Dalam kondisi tertentu, lembaga juga dapat terlibat operasional sebagai katalisator yang bersifat adhoc.</p>
                <p class="mb-0">Tata kelola berjalan melalui musyawarah tahunan, rapat pleno pengurus, rapat pimpinan terbatas, rapat dewan pertimbangan, rapat dewan pakar, dan rapat bidang/departemen.</p>
            </div>
        </div>
    </div>

    <div class="section mt-2 mb-5 ddr-reveal ddr-reveal-delay-2">
        @forelse($histories as $history)
            <div class="card ddr-soft-card mb-2">
                <div class="card-body">
                    <span class="badge badge-ddr mb-1">{{ $history->year }}</span>
                    <h5>{{ $history->title }}</h5>
                    <p class="mb-0">{{ $history->description }}</p>
                </div>
            </div>
        @empty
            <div class="card"><div class="card-body">Data sejarah belum tersedia.</div></div>
        @endforelse
    </div>
@endsection
