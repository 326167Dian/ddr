<!doctype html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="theme-color" content="#0b5d37">
    <title>{{ $title ?? 'DEWAN DAKWAH RISALAH' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('mobilekit/img/favicon.png') }}" sizes="32x32">
    <link rel="stylesheet" href="{{ asset('mobilekit/css/style.css') }}">
    <style>
        :root {
            --bs-primary: #0b5d37;
            --bs-primary-rgb: 11, 93, 55;
            --ddr-soft: #e7f3e8;
            --ddr-dark: #063a24;
            --ddr-gold: #87a03d;
        }

        body {
            background: radial-gradient(circle at 20% 5%, rgba(135, 160, 61, 0.25), transparent 34%),
                radial-gradient(circle at 80% 12%, rgba(11, 93, 55, 0.2), transparent 28%),
                linear-gradient(180deg, #f3faea 0%, #e2f1da 42%, #f8fbf6 100%);
        }

        .hero-cover {
            background-size: cover;
            background-position: center;
            border-radius: 14px;
            min-height: 220px;
            max-height: 72vh;
            aspect-ratio: 16 / 9;
            position: relative;
            overflow: hidden;
        }

        .hero-cover-centered {
            width: min(100%, 980px);
            margin-inline: auto;
            background-position: center center;
        }

        .hero-cover::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(6, 58, 36, 0.08), rgba(6, 58, 36, 0.78));
        }

        .hero-content {
            position: absolute;
            inset: auto 16px 16px 16px;
            z-index: 2;
            color: #fff;
        }

        .badge-ddr {
            background: var(--ddr-soft);
            color: var(--ddr-dark);
        }

        .org-logo {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: contain;
            background: #fff;
            padding: 4px;
        }

        .member-photo-preview {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 18px;
            border: 2px solid #fff;
            box-shadow: 0 8px 18px rgba(6, 58, 36, 0.12);
        }

        .article-body h1,
        .article-body h2,
        .article-body h3 {
            color: var(--ddr-dark);
            margin-bottom: .65rem;
        }

        .article-body p,
        .article-body ul,
        .article-body ol,
        .article-body blockquote {
            margin-bottom: .85rem;
        }

        .article-body img {
            max-width: 100%;
            border-radius: 12px;
        }

        .ddr-soft-card {
            border-radius: 14px;
            border: 1px solid rgba(11, 93, 55, 0.12);
            background: linear-gradient(130deg, rgba(255, 255, 255, 0.98), rgba(239, 248, 232, 0.98));
            box-shadow: 0 8px 18px rgba(6, 58, 36, 0.08);
        }

        .ddr-chip {
            border-radius: 99px;
            padding: 6px 12px;
            display: inline-flex;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .02em;
            background: rgba(135, 160, 61, 0.16);
            color: var(--ddr-dark);
        }

        .ddr-reveal {
            opacity: 0;
            transform: translateY(12px);
            animation: ddr-fade .45s ease forwards;
        }

        .ddr-reveal-delay-1 {
            animation-delay: .08s;
        }

        .ddr-reveal-delay-2 {
            animation-delay: .16s;
        }

        .ddr-reveal-delay-3 {
            animation-delay: .24s;
        }

        @media (max-width: 992px) {
            .hero-cover {
                aspect-ratio: 3 / 2;
                max-height: 62vh;
            }
        }

        @media (max-width: 768px) {
            .hero-cover {
                aspect-ratio: 4 / 3;
                min-height: 200px;
                max-height: 56vh;
            }
        }

        @keyframes ddr-fade {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#sidebarPanel" class="headerButton text-light" data-bs-toggle="offcanvas">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle d-flex align-items-center gap-2">
            <img src="{{ asset('logoddr.png') }}" alt="logo" class="org-logo">
        </div>
        <div class="right"></div>
    </div>

    <div id="appCapsule">
        @yield('content')

        <div class="appFooter mt-4">
            <img src="{{ asset('logoddr.png') }}" alt="logo" class="footer-logo mb-2">
            <div class="footer-title">{{ $profile->name ?? 'DEWAN DAKWAH RISALAH' }}</div>
            <div>{{ $profile->tagline ?? 'Gerakan dakwah, edukasi, dan pelayanan umat.' }}</div>
            @if(!empty($profile?->contact_email) || !empty($profile?->contact_phone) || !empty($profile?->address))
                <div class="mt-2 text-secondary">
                    @if(!empty($profile?->contact_email))
                        <div>Email: {{ $profile->contact_email }}</div>
                    @endif
                    @if(!empty($profile?->contact_phone))
                        <div>Telepon: {{ $profile->contact_phone }}</div>
                    @endif
                    @if(!empty($profile?->address))
                        <div>Alamat: {{ $profile->address }}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="appBottomMenu">
        <a href="{{ route('home') }}" class="item {{ request()->routeIs('home') ? 'active' : '' }}">
            <div class="col"><ion-icon name="home-outline"></ion-icon></div>
        </a>
        <a href="{{ route('structure') }}" class="item {{ request()->routeIs('structure') ? 'active' : '' }}">
            <div class="col"><ion-icon name="people-outline"></ion-icon></div>
        </a>
        <a href="{{ route('history') }}" class="item {{ request()->routeIs('history') ? 'active' : '' }}">
            <div class="col"><ion-icon name="time-outline"></ion-icon></div>
        </a>
        <a href="{{ route('activities') }}" class="item {{ request()->routeIs('activities') ? 'active' : '' }}">
            <div class="col"><ion-icon name="calendar-outline"></ion-icon></div>
        </a>
        <a href="{{ route('articles') }}" class="item {{ request()->routeIs('articles*') ? 'active' : '' }}">
            <div class="col"><ion-icon name="newspaper-outline"></ion-icon></div>
        </a>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarPanel">
        <div class="offcanvas-body">
            <div class="profileBox">
                <div class="image-wrapper">
                    <img src="{{ asset('logoddr.png') }}" alt="logo" class="imaged rounded">
                </div>
                <div class="in">
                    <strong>{{ $profile->name ?? 'DEWAN DAKWAH RISALAH' }}</strong>
                    <div class="text-muted">Organisasi Dakwah Keislaman</div>
                </div>
            </div>

            <ul class="listview flush transparent no-line image-listview mt-2">
                <li><a href="{{ route('home') }}" class="item"><div class="icon-box bg-primary"><ion-icon name="home-outline"></ion-icon></div><div class="in">Beranda</div></a></li>
                <li><a href="{{ route('structure') }}" class="item"><div class="icon-box bg-primary"><ion-icon name="people-outline"></ion-icon></div><div class="in">Struktur Organisasi</div></a></li>
                <li><a href="{{ route('history') }}" class="item"><div class="icon-box bg-primary"><ion-icon name="time-outline"></ion-icon></div><div class="in">Sejarah</div></a></li>
                <li><a href="{{ route('activities') }}" class="item"><div class="icon-box bg-primary"><ion-icon name="calendar-outline"></ion-icon></div><div class="in">Kegiatan</div></a></li>
                <li><a href="{{ route('articles') }}" class="item"><div class="icon-box bg-primary"><ion-icon name="newspaper-outline"></ion-icon></div><div class="in">Artikel</div></a></li>
            </ul>
        </div>
    </div>

    <script src="{{ asset('mobilekit/js/lib/bootstrap.min.js') }}"></script>
    <script src="{{ asset('mobilekit/js/base.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    @stack('scripts')
</body>

</html>
